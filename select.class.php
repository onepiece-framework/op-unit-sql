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
			Notice::Set("Has not been set column. (SELECT ? FROM $table)");
			return false;
		}

		//	WHERE
		if(!$where = _where::Get($args, $db) ){
			Notice::Set("Has not been set where condition. (SELECT $column FROM $table)");
			return false;
		}

		//	LIMIT
		if(!$limit = self::_limit($args)){
			Notice::Set("Has not been set limit condition. (SELECT $column FROM $table WHERE {$where})");
			return false;
		}

		return "SELECT {$column} FROM {$table} WHERE {$where} LIMIT {$limit}";
	}

	/**
	 * Get column condition.
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

	/**
	 * Get limit condition.
	 *
	 */
	static function _limit($args)
	{
		return ifset($args['limit']);
	}
}