<?php
/**
 * unit-sql:/select.class.php
 *
 * @created   2016-11-28
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * select
 *
 * @created   2016-11-29
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class select extends OnePiece
{
	/**
	 * Get select sql statement.
	 *
	 * @param  array $args
	 * @param  db $db
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

		//	COLUMN
		if(!$column = self::_column($args, $db)){
			return false;
		}

		return "SELECT {$column} FROM {$table}";
	}

	/**
	 * Get column string.
	 *
	 * @param  array $args
	 * @param  PDO $pdo
	 * @return string
	 */
	static private function _column($args, $pdo)
	{
		if( $column = ifset($args['column']) ){
			if( is_string($column) ){
				$column = explode(',', $column);
			}
			foreach($column as $key => $val){
				$join[] = $pdo->quote(trim($val));
			}
			$result = join(', ', $join);
		}else{
			$result = '*';
		}
		return $result;
	}
}