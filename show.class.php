<?php
/**
 * unit-sql:/show.class.php
 *
 * @created   2016-12-07
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * show
 *
 * @created   2016-12-07
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class show extends OnePiece
{
	/**
	 * Get show sql statement.
	 *
	 * @param  array $args
	 * @param  db    $db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		if( $database = ifset($args['database']) ){
			$database = $db->Quote($database);
			$sql = "SHOW TABLES FROM {$database}";
		}else{
			$sql = 'SHOW DATABASES';
		}

		return $sql;
	}
}