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
		if(!$table = dml::table($args, $db) ){
			return false;
		}

		//	COLUMN
		if(!$column = self::_column($args, $db)){
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

		//	OFFSET
		$offset = dml::offset($args, $db);

		return "SELECT {$column} FROM {$table} WHERE {$where} LIMIT {$limit} OFFSET {$offset}";
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
				$val = trim($val);
				if( strpos($val, ' ') !== false ){
					Notice::Set("Not secure. ($val)");
					continue;
				}
				if( is_string($key) ){
					$join[] = strtoupper($key)."($val)";
				}else{
					$join[] = $pdo->quote(trim($val));
				}
			}
			$result = join(', ', $join);
		}else{
			$result = '*';
		}
		return $result;
	}
}