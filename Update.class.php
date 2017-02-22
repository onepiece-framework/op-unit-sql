<?php
/**
 * unit-sql:/Update.class.php
 *
 * @created   2016-11-30
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace SQL;

/** Update
 *
 * @created   2016-11-30
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
	use \OP_CORE;

	/** Get update sql statement.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		//	TABLE
		if(!$table = dml::table($args, $db) ){
			return false;
		}

		//	SET
		if(!$set = dml::set($args, $db)){
			\Notice::Set("Has not been set condition. ($table)");
			return false;
		}

		//	WHERE
		if(!$where = dml::where($args, $db) ){
			return false;
		}

		//	LIMIT
		if(!$limit = dml::limit($args, $db)){
			return false;
		}

		return "UPDATE {$table} SET {$set} WHERE {$where} LIMIT {$limit}";
	}
}