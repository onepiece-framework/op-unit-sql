<?php
/**
 * unit-sql:/delete.class.php
 *
 * @created   2016-12-01
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * delete
 *
 * @created   2016-12-01
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class delete extends OnePiece
{
	/**
	 * Get delete sql statement.
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

		//	WHERE
		if(!$where = dml::where($args, $db) ){
			return false;
		}

		//	LIMIT
		if(!$limit = dml::limit($args, $db)){
			return false;
		}

		return "DELETE FROM {$table} WHERE {$where} LIMIT {$limit}";
	}
}