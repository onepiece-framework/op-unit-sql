<?php
/**
 * unit-sql:/dml.class.php
 *
 * @created   2016-11-30
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * dml
 *
 * @created   2016-11-30
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class dml extends OnePiece
{
	/**
	 * Get table condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function table($args, $db)
	{
		if( $table = ifset($args['table']) ){
			$table = $db->Quote($table);
		}else{
			Notice::Set("Has not been set table name.");
		}
		return $table;
	}

	/**
	 * Get set condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function set($args, $db)
	{
		foreach( $args['set'] as $column => $value ){
			$column	 = $db->Quote($column);
			$value	 = $db->GetPDO()->quote($value);
			$join[] = "{$column} = {$value}";
		}
		return join(', ', $join);
	}

	/**
	 * Get where condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function where($args, $db)
	{
		//	...
		if( empty($args['where']) ){
			Notice::Set("Has not been set where condition. ({$args['table']})");
			return false;
		}

		//	...
		foreach( $args['where'] as $column => $condition ){
			if( is_array($condition) ){
				$evalu = ifset($condition['evalu'], '=');
				$value = ifset($condition['value']);
			}else{
				$evalu = '=';
				$value = $condition;
			}
			$column	 = $db->Quote($column);
			$value	 = $db->GetPDO()->quote($value);
			$evalu	 = $db->GetPDO()->quote($evalu);
			$evalu	 = substr($evalu, 1, -1);
			$join[] = "{$column} {$evalu} {$value}";
		}
		return join(' AND ', $join);
	}

	/**
	 * Get limit condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function limit($args, $db)
	{
		if( empty($args['limit']) ){
			Notice::Set("Has not been set limit condition. ({$args['table']})");
		}
		return (int)$args['limit'];
	}

	/**
	 * Get offset condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function offset($args, $db)
	{
		return (int)ifset($args['offset']);
	}
}