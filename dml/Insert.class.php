<?php
/**
 * unit-sql:/DML/Insert.class.php
 *
 * @creation  2019-03-04
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @creation  2019-03-04
 */
namespace OP\UNIT\SQL\DML;

/** Used class
 *
 * @created   2019-03-04
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;

/** Insert
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Insert
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Generate SQL.
	 *
	 * @param array       $config
	 * @param IF_DATABASE $_DB
	 */
	static function SQL(array $config, IF_DATABASE $_DB)
	{
		//	...
		if(!isset($config['table']) ){
			throw new Exception("Has not been set table name.");
		};

		//	...
		switch( $_DB->Config()['prod'] ){
			case 'mysql':
				return self::_MySQL($config, $_DB);

			default:
				return self::_Other($config, $_DB);
		};
	}

	static private function _MySQL(array $config, IF_DATABASE $_DB)
	{
		//	Init
		$fields = $values = $set = $update = null;

		//	TABLE
		$table = Common::Table($config['table'] ?? null, $_DB);

		//	SET
		$set = Common::Set($config['set'] ?? null, $_DB);

		//	...
		if( $config['update'] ?? null ){
			$update = self::_Update($config, $_DB);
		};

		//	...
		return "INSERT INTO {$table} {$fields} {$values} {$set} {$update}";
	}

	static private function _Other(array $config, IF_DATABASE $_DB)
	{

	}

	static private function _Update(array $config, IF_DATABASE $_DB)
	{
		//	...
		$sets = null;

		//	...
		foreach( explode(',', $config['update'] ?? null) as $field ){
			//	...
			foreach( $config['set'] as $set ){
				//	...
				$set   = trim($set);
				$field = trim($field);

				//	...
				if( strpos($set, $field) === 0 ){
					$sets[] = $set;
				};
			};
		};

		//	...
		return "ON DUPLICATE KEY UPDATE " . substr(Common::Set($sets, $_DB), 4);
	}
}
