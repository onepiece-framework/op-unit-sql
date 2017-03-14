<?php
/**
 * unit-sql:/Show.class.php
 *
 * @created   2016-12-07
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace SQL;

/** Show
 *
 * @created   2016-12-07
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Show
{
	/** trait
	 *
	 */
	use \OP_CORE;

	/** Get show sql statement.
	 *
	 * @param  array $args
	 * @param  db    $db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		if( $database = ifset($args['database']) ){
			$database = $db->Quote($database);
			if( $table = ifset($args['table']) ){
				$table = $db->Quote($table);
				if( ifset($args['index']) ){
					//	Indexes list.
					$query = "SHOW INDEX FROM {$database}.{$table}";
				}else{
					//	Tables list.
					$query = "SHOW FULL COLUMNS FROM {$database}.{$table}";
				}
			}else{
				//	Databases list.
				$query = "SHOW TABLES FROM {$database}";
			}
		}else{
			$query = 'SHOW DATABASES';
		}

		return $query;
	}
}