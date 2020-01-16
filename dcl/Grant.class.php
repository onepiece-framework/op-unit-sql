<?php
/**
 * unit-sql:/Grant.class.php
 *
 * @created   2017-12-19
 * @updated   2019-04-09
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2017-12-19  OP\UNIT\SQL
 * @updated   2019-04-09  OP\UNIT\SQL\DCL
 */
namespace OP\UNIT\SQL\DCL;

/** Used class
 *
 * @created   2019-04-08
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;
use function OP\ifset;

/** Grant
 *
 * @created   2017-12-19
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Grant
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Database
	 *
	 * @creation  2019-04-09
	 * @var      \OP\UNIT\Database
	 */
	private $_DB;

	/** Construct.
	 *
	 * @created  2019-04-09
	 * @param    IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		$this->_DB = & $_DB;
	}

	/** Grant to Privilege.
	 *
	 * <pre>
	 * Has not been support to privilege to each column yet.
	 * </pre>
	 *
	 * @param  array  $config
	 */
	function Privilege($config)
	{
		//	...
		$host = $user = null;
		foreach( ['host','user'] as $key ){
			if( isset($config[$key]) ){
				${$key} = $this->_DB->PDO()->Quote($config[$key]);
			}else{
				throw new Exception("Has not been set $key.");
			}
		}

		//	...
		$database = $table = null;
		foreach( ['database','table'] as $key ){
			if( isset($config[$key]) ){
				${$key} = $config[$key] === '*' ? '*': $this->_DB->Quote($config[$key]);
			}else{
				throw new Exception("Has not been set $key.");
			}
		}

		//	...
		switch( $type = gettype( ifset($config['privileges']) ) ){
			case 'string':
				$privileges = explode(',', $config['privileges']);
				break;

			case 'array':
				$privileges = $config['privileges'];
				break;

			default:
				throw new Exception("Has not been set this privilege type. ($type)");
		}

		//	...
		$join = $m = null;

		//	...
		foreach( $privileges as $privilege ){
			$privilege = trim($privilege);
			$privilege = strtoupper($privilege);
			if( preg_match('/[^A-Z]/', $privilege, $m) ){
				throw new Exception("Illegal privilege. ({$m[1]})");
			}
			$join[] = $privilege;
		}
		$privileges = join(', ', $join);

		//	...
		if( strlen($user) > 16 ){
			throw new Exception("User name is too long. (Maximum 16 character: $user)");
		}

		/*
		 REVOKE ALL PRIVILEGES ON `testcase`.`t_test` FROM 'testcase'@'localhost';
		 GRANT SELECT (`ai`, `id`), UPDATE (`ai`) ON `testcase`.`t_test` TO 'testcase'@'localhost';
		 */
		//		GRANT SELECT,INSERT ON  `database`.*      TO  'user'@'host';
		return "GRANT {$privileges} ON {$database}.{$table} TO {$user}@{$host}";
	}
}
