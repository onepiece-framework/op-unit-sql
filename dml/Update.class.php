<?php
/**
 * unit-sql:/DML/Update.class.php
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

/** Update
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Update
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
	static function SQL($config, IF_DATABASE $_DB)
	{
		//	...
		foreach( ['table','set','where','limit'] as $key ){
			if(!isset($config[$key]) ){
				throw new Exception("Has not been set \"{$key}\".");
			};
		};

		//	TABLE
		$table = Common::Table($config['table'], $_DB);

		//	WHERE
		$where = Common::Where($config['where'], $_DB);

		//	LIMIT
		$limit = Common::Limit($config['limit'], $_DB);

		//	SET
		$set   = Common::Set(Common::SetUniform($config['set']), $_DB);

		//	OFFSET
		if( isset($config['offset']) ){
			throw new Exception("OFFSET can not be used in UPDATE.");
		};

		//	...
		if( 'mysql' === ($prod = $_DB->Config()['prod']) ){
			//	LIMIT
			$limit = Common::Limit($config['limit'], $_DB);

			//	ORDER
			$order = isset($config['order']) ? Common::Order($config['order'], $_DB) : null;
		}else{
			//	...
			if( isset($config['limit']) or isset($config['order']) ){
				throw new Exception("This product has not been support LIMIT and ORDER. ({$prod})");
			};

			//	...
			$order = $limit = null;
		};

		//	...
		return "UPDATE {$table} {$set} WHERE {$where} {$order} {$limit}";
	}
}
