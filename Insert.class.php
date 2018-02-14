<?php
/**
 * unit-sql:/Insert.class.php
 *
 * @created   2016-11-28
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace SQL;

/** Insert
 *
 * @created   2016-11-29
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
	use \OP_CORE;

	/** Get insert sql statement.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		//	...
		if(!$db){
			\Notice::Set("Has not been set database object.");
			return false;
		}

		//	TABLE
		if( $table = ifset($args['table']) ){
			$table = $db->Quote($table);
		}else{
			\Notice::Set("Has not been set table name.");
			return false;
		}

		//	SET
		if(!$set = DML::set($args, $db)){
			return false;
		}

		//	ON DUPLICATE KEY UPDATE
		if( $keys   = ifset($args['update']) ){
			$update = "ON DUPLICATE KEY UPDATE ";
			$temp   = [];
			$temp['table'] = $args['table'];
			foreach( explode(',', $keys) as $key ){
				$key = trim($key);
				$temp['set'][$key] = $args['set'][$key];
			}
			$update .= DML::Set($temp, $db);
		}else{
			$update  = null;
		}

		//	...
		return "INSERT INTO {$table} SET {$set} {$update}";
	}
}
