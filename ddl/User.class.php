<?php
/**
 * unit-sql:/User.class.php
 *
 * @created   2017-12-19
 * @updated   2019-04-09  Correspond to IF.
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2017-12-19  OP\UNIT\SQL
 * @updated   2019-04-09  OP\UNIT\SQL\DDL
 */
namespace OP\UNIT\SQL\DDL;

/** Used class
 *
 * @created   2019-04-08
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;
use function OP\ifset;

/** User
 *
 * @created   2017-12-19
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class User
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

	/** Create user.
	 *
	 * @param  array  $config
	 * @return string $sql
	 */
	function Create($config)
	{
		//	...
		$host = ifset($config['host']);
		$user = ifset($config['user']);

		//	...
		if( !$host or !$user ){
			throw new Exception("Has not been set host name or user name. ($host, $user)");
		}

		//	...
		if( strlen($user) > 16 ){
			throw new Exception("User name is too long. (Maximum 16 character: $user)");
		}

		//	...
		$host = $this->_DB->PDO()->Quote($host);
		$user = $this->_DB->PDO()->Quote($user);

		//	...
		if( $password = ifset($config['password']) ){
			$identified = "BY " . $this->_DB->PDO()->Quote($password);
		}else{
			$identified = 'WITH mysql_native_password';
		}

		//		CREATE USER  'user'@'host'  IDENTIFIED WITH mysql_native_password;
		return "CREATE USER {$user}@{$host} IDENTIFIED $identified";
	}

	/** Set password.
	 *
	 * @param  array  $config
	 * @return string $sql
	 */
	function Password($config)
	{
		//	...
		$host = ifset($config['host']);
		$user = ifset($config['user']);
		$password = ifset($config['password']);

		//	...
		if( !$host or !$user or !$password ){
			throw new Exception("Has not been set host name or user name or password. ($host, $user, $password)");
		}

		//	...
		$version = $this->_DB->Version();
		$host	 = $this->_DB->PDO()->Quote($host);
		$user	 = $this->_DB->PDO()->Quote($user);
		$password= $this->_DB->PDO()->Quote($password);

		//	...
		if( version_compare($version, '5.7.0') >= 0) {
			$sql = "ALTER USER {$user}@{$host} identified BY {$password}";
		}else{
			$sql = "SET PASSWORD FOR {$user}@{$host} = PASSWORD({$password})";
		}

		//	...
		return $sql;
	}

	/** Drop user.
	 *
	 * @param  array  $config
	 * @return string $sql
	 */
	function Drop($config)
	{
		//	...
		$host    = $config['host']    ?? null;
		$user    = $config['user']    ?? null;
		$cascade = $config['cascade'] ?? null;

		//	...
		$host = $this->_DB->PDO()->Quote($host);
		$user = $this->_DB->PDO()->Quote($user);

		//	...
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				//	...
				$user = "{$user}@{$host}";
				break;

			case 'pgsql':
				break;

			case 'oracle':
				$user .= $cascade ? ' CASCADE': '';
				break;

			case 'mssql':
				break;

			default:
				throw new Exception("Has not been support this product. ($prod)");
		};

		//	...
		return "DROP USER {$user}";
	}
}
