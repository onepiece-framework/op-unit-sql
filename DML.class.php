<?php
/**
 * unit-sql:/DML.class.php
 *
 * @created   2016-11-30
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace SQL;

/** DML
 *
 * @created   2016-11-30
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class DML
{
	/** trait
	 *
	 */
	use \OP_CORE;

	/** Get table condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Table($args, $db)
	{
		if( $table = ifset($args['table']) ){
			$table = $db->Quote($table);
		}else{
			\Notice::Set("Has not been set table name.");
		}
		return $table;
	}

	/** Get set condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Set($args, $db)
	{
		foreach( $args['set'] as $column => $value ){
			$column	 = $db->Quote($column);
			$value	 = $db->GetPDO()->quote($value);
			$join[] = "{$column} = {$value}";
		}
		return join(', ', $join);
	}

	/** Get where condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Where($args, $db)
	{
		//	...
		if( empty($args['where']) ){
			\Notice::Set("Has not been set where condition. ({$args['table']})");
			return false;
		}

		//	...
		foreach( $args['where'] as $column => $condition ){
			if( is_array($condition) ){
				$evalu = ifset($condition['evalu'], '=');
				$value = ifset($condition['value']);
			}else{
				if( $condition === null ){
					$evalu = 'is null';
				}else{
					$evalu = '=';
				}
				$value = $condition;
			}
			$column	 = $db->Quote($column);
			$value	 = $db->GetPDO()->quote($value);
			$evalu	 = $db->GetPDO()->quote($evalu);
			$evalu	 = substr($evalu, 1, -1);
			switch( $evalu = strtoupper($evalu) ){
				case 'IS NULL':
				case 'IS NOT NULL':
					$join[] = "{$column} {$evalu}";
					break;

				case 'BETWEEN':
					list($st, $en) = explode('TO', substr($value, 1, -1));
					$st = $db->GetPDO()->quote(trim($st));
					$en = $db->GetPDO()->quote(trim($en));
					$join[] = "{$column} {$evalu} {$st} AND {$en}";
					break;

				case 'IN':
					foreach( explode(',', substr($value, 1, -1)) as $v ){
						$in[] = $v;
					}
					$join[] = "{$column} {$evalu} ('".join("', '", $in)."')";
					break;

				default:
					$join[] = "{$column} {$evalu} {$value}";
			}
		}
		return '('.join(' AND ', $join).')';
	}

	/** Get limit condition.
	 *
	 * @param  array
	 * @return string
	 */
	static function Limit($args)
	{
		if( empty($args['limit']) ){
			\Notice::Set("Has not been set limit condition. ({$args['table']})");
		}
		return (int)$args['limit'];
	}

	/** Get offset condition.
	 *
	 * @param  array
	 * @return string
	 */
	static function Offset($args)
	{
		return (int)$args['offset'];
	}

	/** Get order condition.
	 *
	 * @param  array
	 * @param  db
	 * @return string
	 */
	static function Order($args, $db)
	{
		foreach( explode(',', $args['order']) as $value ){
			list($field, $value) = explode(' ', $value);
			$field  = $db->Quote($field);
			$field .= $value === 'desc' ? ' DESC': '';
			$join[] = $field;
		}

		//	...
		return "ORDER BY ".join(', ', $join);
	}
}