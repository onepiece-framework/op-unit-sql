<?php
/**
 * unit-sql:/insert.class.php
 *
 * @created   2016-11-28
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * insert
 *
 * @created   2016-11-29
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class insert extends OnePiece
{
	/**
	 * Get insert sql statement.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		//	TABLE
		if( $table = ifset($args['table']) ){
			$table = $db->Quote($table);
		}else{
			Notice::Set("Has not been set table name.");
			return false;
		}

		//	SET
		if(!$set = dml::set($args, $db)){
			Notice::Set("Has not been set condition. ($table)");
			return false;
		}

		return "INSERT INTO {$table} SET {$set}";
	}
}