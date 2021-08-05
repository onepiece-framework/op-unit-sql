<?php
/**
 * unit-sql:/DML/Common.class.php
 *
 * @creation  2019-03-04
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @creation  2019-03-04
 */
namespace OP\UNIT\SQL\DML;

/** Used class
 *
 * @created   2019-03-04
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;

/** Common
 *
 * @creation  2019-03-04
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Common
{
	/** trait
	 *
	 */
	use OP_CORE;

	static private function _Table(string $table, IF_DATABASE $_DB)
	{
		//	database_name.table_name
		if( $pos = strpos($table, '.') ){
			//	database_name.table_name --> database_name, table_name
			$database = substr($table, 0, $pos);
			$table    = substr($table, $pos+1);
		};

		//	table_name --> `table_name`
		$table = $_DB->Quote($table);

		/*
		//	If exists database name.
		if( empty($database) and isset($config['database']) and $_DB->Config()['prod'] === 'mysql' ){
			$database = $config['database'];
		};
		*/

		//	`table_name` --> `database_name`.`table_name`
		if( $database ?? null ){
			$table = $_DB->Quote($database).'.'.$table;
		};

		//	...
		return $table;
	}

	/** Join each table.
	 *
	 * @param   string|array
	 * @param   IF_DATABASE
	 * @return  string
	 */
	static private function _Table_Joins($tables, IF_DATABASE $_DB)
	{
		//	...
		if( is_string($tables) ){
			$tables = explode(',', $tables);
		}

		//	...
		$join = [];

		//	...
		foreach( $tables as $table ){
			$join[] = self::_Table_Join(trim($table), $_DB, count($join));
		};

		//	...
		return join(' ', $join);
	}

	static private function _Table_Join(string $table, IF_DATABASE $_DB, $flag)
	{
		//	...
		$join = $match = null;

		//	...
		preg_match("/([\w\.]+)\s*([<>=]+)\s*([\w\.]+)/", $table, $match);

		//	...
		$join['left']  = explode('.', $match[1]);
		$join['right'] = explode('.', $match[3]);

		//	...
		switch( $match[2] ){
			case '=':
			case '<=':
				$eval = 'LEFT';
				break;
			case '=>':
				$eval = 'RIGHT';
				break;
			case '>=<':
				$eval = 'INNER';
				break;
			case '<=>':
				$eval = 'OUTER';
				break;
		};

		//	...
		$table1 = $_DB->Quote($join['left'][0] );
		$field1 = $_DB->Quote($join['left'][1] );
		$table2 = $_DB->Quote($join['right'][0]);
		$field2 = $_DB->Quote($join['right'][1]);

		//	...
		$table0 = $flag ? null: $table1;

		//	...
		return "$table0 $eval JOIN $table2 ON $table1.$field1 = $table2.$field2";
	}

	/** Table
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Table($table, IF_DATABASE $_DB)
	{
		//	...
		if( is_array($table) or strpos($table, '=') ){
			return self::_Table_Joins($table, $_DB);
		}else{
			return self::_Table($table, $_DB);
		};
	}

	/** Set
	 *
	 * @param   array       $config
	 * @param   IF_DATABASE $_DB
	 * @return  string
	 */
	static function Set($set, IF_DATABASE $_DB)
	{
		//	...
		$join  = [];

		//	...
		foreach( $set as $field => $array ){
			//	...
			$field = $_DB->Quote($field);
			$evalu = $array['evalu'];
			$value = $array['value'];

			//	...
			if( $value === null ){
				$evalu = '=';
				$value = 'NULL';
			}else if( $evalu === '+' or $evalu === '-' ){
				$value = "{$field} {$evalu} " . (int)$value;
			}else{
				$evalu = '=';
				$value = $_DB->PDO()->quote($value);
			};

			//	...
			$join[] = "{$field} = {$value}";
		};

		//	...
		return 'SET ' . join(', ', $join);
	}

	/** To uniform old style and new style.
	 *
	 * @param  array $set
	 * @return array $set
	 */
	static function SetUniform($set)
	{
		//	...
		$_set  = null;
		$match = null;

		//	...
		foreach( $set as $field => $value ){
			//	...
			if( is_array($value) ){
				$evalu = $value['evalu'];
				$value = $value['value'];
			}else if( is_numeric($field) ){
				if( preg_match('/([_a-z0-9]+)\s*(=|\+|-|is)\s*(.+)/is', trim($value), $match) ){
					//	...
					$field = $match[1];
					$evalu = $match[2];
					$value = $match[3];

					//	...
					if(($evalu === 'is') and ('null' === strtolower($value))){
						$value = null;
					};
				}else{
					throw new Exception("Not match format. ($value)");
				}
			}else{
				$evalu = '=';
				$field = trim($field);
			};

			//	...
			$_set[$field] = ['evalu'=>$evalu, 'value'=>$value];
		};

		//	...
		return $_set;
	}

	/** Values
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Values($values, IF_DATABASE $_DB)
	{

	}

	/** Field
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Field($fields, IF_DATABASE $_DB)
	{
		//	...
		$join = [];

		//	String to array.
		if( is_string($fields ?? null) ){
			$fields = explode(',', $fields);
		};

		//	Each fields.
		foreach( $fields ?? [] as $field ){
			//	...
			list($field, $alias) = explode(' as ', $field . ' as ');

			//	Separate function.
			if( $pos1 = strpos($field, '(') and $pos2 = strpos($field, ')') ){
				$func = substr($field,       0,         $pos1);
				$field= substr($field, $pos1+1, $pos2-$pos1-1);
			}else{
				$func = null;
			}

			//	Correspond include table name or multi field name.
			$field = self::_Field_Escape($field, $_DB);

			//	If has function.
			if( $func ){
				$func  = strtoupper($func);
				$field = "{$func}($field)";
			};

			//	If has alias name.
			if( $alias ){
				$alias = $_DB->Quote(trim($alias));
			};

			//	...
			$join[] = $alias ? "$field AS $alias": $field;
		};

		//	...
		return count($join) ? join(', ', $join): '*';
	}

	/** Escape field name.
	 *
	 * @param  string      $field
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static private function _Field_Escape(string $field, IF_DATABASE $_DB)
	{
		//	If asterisk
		if( $field === '*' ){
			return $field;
		};

		//	...
		$join = [];

		//	Comma separator is used concat and concat group.
		foreach( explode(',', $field) as $field ){
			//	If has table name.
			if( strpos($field, '.') ){
				//	Has table name.
				list($table, $field) = explode('.', $field);
				$table = $_DB->Quote(trim($table));
				$field = $_DB->Quote(trim($field));
				$field = "{$table}.{$field}";
			}else{
				//	...
				$field = trim($field);

				//	...
				if( ($field[0] === '"') and  ($field[strlen($field)-1] === '"') ){
					//	concat string.
				}else{
					//	Field name only.
					$field = $_DB->Quote($field);
				}
			};

			//	...
			$join[] = $field;
		};

		//	...
		return join(', ', $join);
	}

	/** Where
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Where($where, $_DB)
	{
		/*
		if(!is_array($where) ){
			throw new \Exception("Where is not array. ($where)");
		};
		*/

		//	...
		$join  = [];
		$match = null;

		//	Each where.

		/*
		foreach( $where as $field => $str ){
			//	Which new or old format.
			if( preg_match('/(\w+\.?\w*)\s+([^\s]+)\s+(.+)/i', $str, $match) ){
				//	New format.
				$field = $match[1];
				$evalu = $match[2];
				$value = $match[3];
				$value = trim($value);
			}else{
				//	Old format.
				$evalu = '=';
				$value = $str;
			};
		*/

		//	...
		if( is_string($where) ){
			$where = [$where];
		}

		//	...
		foreach($where as $key => $str){

			//	If old style.
			if( is_string($key) ){
				if( $str === null ){
					$str = " $key is null ";
				}else{
					if(!is_string($str) and !is_int($str) ){
						$type = gettype($str);
						$json = json_encode($str);
						throw new \Exception("Arguments is not string. ($key, $type, $json)");
					}
					$str = " $key = $str ";
				};
			};

			//	...
			if( preg_match('/(\w+\.?\w*)\s+([^\s]+)\s+(.+)/i', $str, $match) ){
				//	t_table.field (=|is) value
			}else if( preg_match('/(\w+\.?\w*)\s*([=<>]+)\s*(.+)/i', $str, $match) ){
				//	t_table.field=value
			}else{
				throw new Exception("Unmatch where format. ($str)");
			};

			//	Force quote.
			$field = $match[1];
			$evalu = $match[2];
			$value = $match[3];
			$value = trim($value);

			//	...
			if( strpos($field, '.') ){
				//	t_table.field_name
				list($table,$field) = explode('.', $field);
				$field = $_DB->Quote($table) .'.'. $_DB->Quote($field);
			}else{
				//	filed name only.
				$field = $_DB->Quote($field);
			};

			//	Force quote.
			$value = $_DB->PDO()->quote($value);

			//	Switch by evalution.
			switch( $evalu = strtoupper($evalu) ){
				//	NULL
				case '!IS':
				case 'NOT':
					$evalu = 'IS NOT';
					//	break;
				case 'IS':
					if( 'NULL' === strtoupper(substr($value, 1, -1)) ){
						$value = 'NULL';
					};
					break;

				//	IN
				case '!IN':
				case 'NOTIN':
					$evalu = 'NOT IN';
					//	break;
				case 'IN':
					$j = [];
					foreach( explode(',', $value) as $v ){
						$v = $_DB->PDO()->quote(trim($v));
					};
					$value = '('.join(',', $j).')';
					break;

				//	LIKE
				case '!LIKE':
				case 'NOTLIKE':
					$evalu = 'NOT LIKE';
					//	break;
				case 'LIKE':
					$value = str_replace('_', '\_', $value);
					break;

				//	BETWEEN
				case 'BETWEEN':
					break;

					//	...
				case '=':
				case '>':
				case '<':
				case '>=':
				case '<=':
				case '!=':
					break;

					//	...
				default:
					throw new Exception("This evaluation was not supported. ($evalu)");
					return false;
			};

			//	...
			$join[] = "{$field} {$evalu} {$value}";
		};

		//	...
		return '('.join(' AND ', $join).')';
	}

	/** Limit
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Limit($limit, $_DB)
	{
		//	...
		if( empty($limit) ){
			throw new \Exception("Has not been set limit.");
		};

		//	...
		if( -1 === (int)$limit){
			return null;
		}

		//	...
		$limit = (int)$limit;

		//	...
		return "LIMIT {$limit}";
	}

	/** Offset
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Offset($offset, $_DB)
	{
		//	...
		if( empty($offset) ){
			return null;
		};

		//	...
		$offset = (int)$offset;

		//	...
		return "OFFSET {$offset}";
	}

	/** Order
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Order($order, $_DB)
	{
		//	...
		if( empty($order) ){
			return null;
		};

		//	...
		if( is_string($order) ){
			$order = explode(',', $order);
		};

		//	...
		$join = [];

		//	...
		foreach( $order as $value ){
			//	...
			list($field, $aorde) = explode(' ', trim($value).' ');

			//	...
			if( empty($field) ){
				continue;
			}

			//	...
			if( strpos($field, '.') ){
				//	table.field --> table, field
				list($table, $field) = explode('.', $field);
				$field  = $_DB->Quote($table).'.'.$_DB->Quote($field);
			}else{
				//	...
				$field  = $_DB->Quote($field);
			};

			//	...
			$field .= $aorde === 'desc' ? ' DESC':'';

			//	...
			$join[] = $field;
		}

		//	...
		return "ORDER BY ".join(', ', $join);
	}

	/** Group
	 *
	 * @param  array       $config
	 * @param  IF_DATABASE $_DB
	 * @return string
	 */
	static function Group($group, $_DB)
	{
		//	...
		if( empty($group) ){
			return null;
		};

		//	If has table name.
		if( strpos($group, '.') ){
			//	Has table name.
			list($table, $field) = explode('.', $group);
			$table = $_DB->Quote(trim($table));
			$field = $_DB->Quote(trim($field));
			$group = "{$table}.{$field}";
		}else{
			//	Field name only.
			$group = $_DB->Quote(trim($group));
		};

		//	...
		return "GROUP BY {$group}";
	}
}
