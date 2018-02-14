<?php
/**
 * unit-sql:/Select.class.php
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

/** Select
 *
 * @created   2016-11-29
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
	use \OP_CORE;

	/** Get select sql statement.
	 *
	 * @param  array $args
	 * @param  db $db
	 * @return string
	 */
	static function Get($args, $db=null)
	{
		//	...
		$args = Escape($args);

		//	...
		if(!$db){
			\Notice::Set("Has not been set database object.");
			return false;
		}

		//	TABLE
		if(!$table = DML::Table($args, $db) ){
			return false;
		}

		//	COLUMN
		if(!$column = self::_Column($args, $db)){
			return false;
		}

		//	WHERE
		if(!$where = DML::Where($args, $db) ){
			return false;
		}

		//	LIMIT
		if(!$limit = DML::Limit($args, $db)){
			return false;
		}

		//	OFFSET
		$offset = isset($args['offset']) ? DML::Offset($args, $db): 0;

		//	ORDER
		$order  = isset($args['order'])  ? DML::Order($args,  $db): null;

		//	...
		return "SELECT {$column} FROM {$table} WHERE {$where} {$order} LIMIT {$limit} OFFSET {$offset}";
	}

	/** Get column condition.
	 *
	 * @param  array $args
	 * @param  PDO $pdo
	 * @return string
	 */
	static private function _Column($args, $pdo)
	{
		if( $column = ifset($args['column']) ){
			if( is_string($column) ){
				$column = explode(',', $column);
			}
			foreach($column as $key => $val){
				$val = trim($val);
				if( strpos($val, ' ') !== false ){
					\Notice::Set("Not secure. ($val)");
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