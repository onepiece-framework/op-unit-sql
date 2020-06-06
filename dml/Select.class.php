<?php
/**
 * unit-sql:/DML/Select.class.php
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

/** Select
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Select
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
		foreach( ['table','where','limit'] as $key ){
			if(!isset($config[$key]) ){
				throw new Exception("Has not been set \"{$key}\".");
			};
		};

		//	Pager
		if( isset($config['pager']) ){
			require_once(__DIR__.'/function/Pager.php');
			Pager($config);
		}

		//	TABLE
		$table = Common::Table($config['table'], $_DB);

		//	Field
		$field = Common::Field($config['field'] ?? null, $_DB);

		//	WHERE
		$where = Common::Where($config['where'], $_DB);

		//	LIMIT
		$limit = Common::Limit($config['limit'], $_DB);

		//	GROUP
		$group  = isset($config['group'] ) ? Common::Group ($config['group'] , $_DB) : null;

		//	ORDER
		$order  = isset($config['order'] ) ? Common::Order ($config['order'] , $_DB) : null;

		//	OFFSET
		$offset = isset($config['offset']) ? Common::Offset($config['offset'], $_DB) : null;

		//	...
		return "SELECT {$field} FROM {$table} WHERE {$where} {$group} {$order} {$limit} {$offset}";
	}
}
