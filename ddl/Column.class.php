<?php
/**
 * unit-sql:/ddl/Column.class.php
 *
 * @created   2017-12-01
 * @updated   2019-04-09  Correspond to IF.
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2017-12-19  OP\UNIT\SQL
 * @updated   2019-04-09  OP\UNIT\SQL\DDL
 */
namespace OP\UNIT\SQL\DDL;

/** Used class
 *
 * @created   2019-04-09
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;
use function OP\ifset;

/** Column
 *
 * @created   2017-12-01
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Column
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Database
	 *
	 * @created   2019-04-09
	 * @var      \OP\UNIT\Database
	 */
	private $_DB;

	/** Construct.
	 *
	 * @created  2019-04-09
	 * @param   &IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		//	...
		$this->_DB = & $_DB;
	}

	/** Create new column
	 *
	 * @param  array  $config
	 * @return string $sql
	 */
	static function Create($config)
	{
		//	...
		if( 'auto_increment' === strtolower(ifset($config['extra'])) ){
			/**
			 *  First, AI is must be exists PKEY. But, not create yet. (Contradiction of SQL)
			 *  AI is to modify column after create column and PKEY index.
			 */
		//	unset($config['extra']);
		}

		//	...
		return self::_Generate($config, 'ADD');
	}

	/** Change exists column
	 *
	 * @param  array  $config
	 * @return string $sql
	 */
	function Change($config)
	{
		return $this->_Generate($config, 'MODIFY');
	}

	/** Generate Alter SQL.
	 *
	 * @param  array  $config
	 * @param  string $verb
	 * @return string $sql
	 */
	private function _Generate($config, $verb)
	{
		//	...
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				return self::_Generate_MySQL( $config, $verb);

			case 'pgsql':
				return self::_Generate_PgSQL( $config, $verb);

			case 'sqlite':
				return self::_Generate_SQLite($config, $verb);

			default:
				throw new \Exception("This product has not been support. ($prod)");
		};
	}

	/** Generate Alter MySQL.
	 *
	 * @param	 array		 $config
	 * @param	 string		 $verb
	 * @return	 string
	 */
	private function _Generate_MySQL($config, $verb)
	{
		//	...
		$database = $this->_DB->Quote($config['database']);
		$table    = $this->_DB->Quote($config['table']);

		//	...
		$first   = ifset($config['first']);
		$after   = ifset($config['after']);

		//	...
		if(!$first = $first ? 'FIRST': null ){
			$after = ifset($config['after']) ? "AFTER ".$this->_DB->Quote($after): null;
		}

		//	...
		$common = self::Field($config, $this->_DB, $verb);
		$index  = self::Index($config, $this->_DB, $verb);

		//	"PRIMARY KEY" can not been change.
		if( $verb === 'MODIFY' and strpos($index, 'PRIMARY KEY') === 0 ){
			$index = null;
		}

		//	...
		if( $verb === 'MODIFY' ){
			$index = null;
		}

		//	...
	//	$pkey  = ($config['key'] === 'pri') ? ", ADD PRIMARY KEY ({$field})": null;
		$extra = ($config['extra'] ?? null) ?   strtoupper($config['extra']): null;

		//	...
		return "ALTER TABLE $database.$table $verb $common $extra $first $after $index";
	}

	/** Generate Alter PostgreSQL.
	 *
	 * @param	 string		 $database
	 * @param	 string		 $table
	 * @param	 string		 $field
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @param	 string		 $verb
	 * @return	 string
	 */
	private function _Generate_PgSQL($database, $table, $field, $config, $DB, $verb)
	{
		//	...
		$database = $this->_DB->Quote($database);
		$table    = $this->_DB->Quote($table);
		$field    = $this->_DB->Quote($field);

		//	...
		$common   = self::Field($config, $this->_DB, $verb);

		//	...
		switch( $verb ){
			case 'ADD':
				break;

			case 'MODIFY':
				$verb = 'ALTER COLUMN';
				break;

			case 'RENAME':
				break;
		};

		/**
		 * Change:
		 * ALTER TABLE "t_testcase" ALTER COLUMN "text" INT
		 * ALTER TABLE テーブル名     ALTER COLUMN カラム名 TYPE データ型
		 * ALTER TABLE table_name ALTER COLUMN column_name TYPE numeric(10,2);
		 *
		 * Rename:
		 * ALTER TABLE table_name RENAME COLUMN old_name TO new_name;
		 */
		return "ALTER TABLE $table $verb $common";
	}

	/** Generate Alter SQLite.
	 *
	 * @param	 string		 $database
	 * @param	 string		 $table
	 * @param	 string		 $field
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @param	 string		 $verb
	 * @return	 string
	 */
	private function _Generate_SQLite($database, $table, $field, $config, $DB, $verb)
	{
		//	...
		$database = $this->_DB->Quote($database);
		$table    = $this->_DB->Quote($table);
		$field    = $this->_DB->Quote($field);

		//	...
		$common   = self::Field($config, $this->_DB, $verb);

		//	...
		switch( $verb ){
			case 'ADD':
				break;

			case 'MODIFY':
				throw new \Exception("SQLite can not change the definition of a column.");
				break;

			case 'RENAME':
				break;
		};

		//	...
		return "ALTER TABLE $table $verb $common";
	}

	/** Generate each field SQL.
	 *
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @param	 string		 $verb
	 * @throws	\Exception	 $e
	 * @return	 string		 $query
	 */
	function Field($config, $DB, $verb)
	{
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				return self::_Field_MySQL($config, $this->_DB, $verb);

			case 'pgsql':
				return self::_Field_PgSQL($config, $this->_DB, $verb);

			case 'sqlite':
				return self::_Field_SQLite($config, $this->_DB, $verb);

			default:
				throw new \Exception("This product has not been support. ($prod)");
		};
	}

	/** Generate MySQL Field.
	 *
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @param	 string		 $verb
	 * @throws	\Exception	 $e
	 * @return	 string		 $query
	 */
	private function _Field_MySQL($config, $DB, $verb)
	{
		$field   = ifset($config['field']  );
		$type    = ifset($config['type']   );
		$unsigned= ifset($config['unsigned']);
		$length  = ifset($config['length'] );
		$default = ifset($config['default']);
		$extra   = ifset($config['extra']  );
		$null    = ifset($config['null']   );
		$comment = ifset($config['comment'], "''" );
		$charset = ifset($config['charset']);
		$collate = ifset($config['collate']);

		//	Escape
		$field   = $this->_DB->Quote($field);
		$comment = $this->_DB->PDO()->Quote($comment);
		$default = $default === null ? null: "DEFAULT ".$this->_DB->PDO()->Quote($default);

		//	extra is use to auto increment only.
		switch( $extra = strtoupper($extra) ){
			case 'AUTO_INCREMENT':
				break;
			default:
				$extra = null;
		}

		//	set or enum
		$option  = ifset($config['option'] );
		$options = ifset($config['options'], $option);

		//	...
		if( $null !== null ){
			$null  = $null ? 'NULL': 'NOT NULL';
		}

		//	...
		switch( $type = strtoupper($type) ){
			case 'INT':
			case 'TINYINT':
			case 'SMALLINT':
			case 'MEDIUMINT':
			case 'BIGINT':
				$length = (int)$length;
			break;

			case 'SET':
			case 'ENUM':
				if( !$length ){ $length = $options; }
				if( $type !== 'INT' ){
					$join = [];
					foreach( explode(',', $length) as $value ){
						$join[] = $this->_DB->PDO()->quote( trim($value) );
					}
					$length = join(',', $join);
				}
			break;

			case 'CHAR':
			case 'VARCHAR':
				if( $length ){
					$length = (int)$length;
				}else{
					throw new \Exception("Has not been set length. ($field, $type)");
				}
			break;

			//	...
			case 'TIMESTAMP':
				//	...
				if( 'CURRENT_TIMESTAMP' === strtoupper($config['default']) ){
					$null    = null;
					$default = null;
				};

				//	...
				if( $null !== 'NULL' and $default === null ){
					$default = 'DEFAULT CURRENT_TIMESTAMP';
					$extra   = 'ON UPDATE CURRENT_TIMESTAMP';
				};
			break;

			default:
				//	Why use regx?
				if( strpos($type, 'UNSIGNED') ){
					//	break
				}else
				if( preg_match('/[^A-Z]/', $type) ){
					throw new \Exception("Has not been support this type. ($type)");
				}
			break;
		}

		//	...
		if( $length ){
			$type = "$type($length)";
		}

		//	...
		if( $unsigned ){
			$type .= " UNSIGNED";
		}

		//	...
		$charset = self::Charset($charset, $collate);

		//	...
		return "$field $type $charset $default $extra $null COMMENT $comment";
	}

	/** Generate PgSQL Field.
	 *
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @throws	\Exception	 $e
	 * @return	 string		 $query
	 */
	private function _Field_PgSQL($config, $DB, $verb)
	{
		//	...
		$field   = ifset($config['field']  );
		$type    = ifset($config['type']   );
		$length  = ifset($config['length'] );

		//	...
		$field   = $this->_DB->Quote($field);
		$type    = strtoupper($type);
		$length  = (int)($length);

		//	...
		if( $length ){
			$type = "$type($length)";
		};

		/**
		 * @see https://qiita.com/seiro/items/ade4c220dfe4acb0ef4b
		 */
		if( $verb === 'MODIFY' ){
			$type = "TYPE $type";
		};

		//	...
		return "$field $type";
	}

	/** Generate SQLite Field.
	 *
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $DB
	 * @throws	\Exception	 $e
	 * @return	 string		 $query
	 */
	private function _Field_SQLite($config, $DB, $verb)
	{
		//	...
		$field   = ifset($config['field']  );
		$type    = ifset($config['type']   );
		$length  = ifset($config['length'] );

		//	...
		if( $config['ai'] ?? null ){
			$type = 'ai';
		};

		//	...
		$field   = $this->_DB->Quote($field);
		$type    = strtoupper($type);
		$length  = (int)($length);

		//	...
		if( $type === 'AI' ){
			$type   = 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL';
			$length = null;
		};

		//	...
		if( $config['created'] ?? null ){
			$type = "DATETIME DEFAULT CURRENT_TIMESTAMP";
		};

		//	...
		if( $length ){
			$type = "$type($length)";
		};

		//	...
		if( $verb === 'MODIFY' ){
			$type = "TYPE $type";
		};

		//	...
		return "$field $type";
	}

	/** Get column charset
	 *
	 * @param	 string		 $charset
	 * @param	 string		 $collate
	 * @return	 NULL|string $string
	 */
	static function Charset($charset, $collate)
	{
		//	...
		if( !$charset and !$collate ){
			return null;
		}

		//	...
		switch( $charset ){
			case 'ascii':
				if(!$collate ){
					$collate = 'ascii_general_ci';
				}
				break;

			case 'utf8':
			case 'utf-8':
				$charset = 'utf8mb4';
				$collate = 'utf8mb4_general_ci';
				break;

			default:
			if( $collate ){
				list($charset) = explode('_', $collate);
			}else{
				return null;
			}
		}

		//	...
		return "CHARACTER SET {$charset} COLLATE {$collate}";
	}

	/** Get index string.
	 *
	 * @param	 array		 $config
	 * @param	 IF_DATABASE $db
	 * @param	 string		 $verb
	 * @throws	\Exception
	 * @return	 NULL|string
	 */
	static function Index($config, $db, $verb=null)
	{
		//	...
		if( empty($config['key']) ){
			return null;
		};

		//	...
		if( empty($config['field']) ){
			throw new \Exception('Empty field name or index key type.');
		}

		//	...
		$field = $db->Quote($config['field']);

		//	...
		include_once(__DIR__.'/Index.class.php');
		$key = Index::Type($config['key'], $config['type']);

		//	...
		if( $key === 'PRIMARY KEY' and $verb === 'ADD' ){
			$key = ", ADD PRIMARY KEY";
		};

		//	...
		return "$key($field)";
	}

	/** Calc integer length.
	 *
	 * @param	 string		 $type
	 * @param	 boolean	 $unsigned
	 * @param	 integer	 $length
	 * @return	 integer	 $length
	 */
	static function Length($type, $unsigned)
	{
		//	...
		switch( strtolower($type) ){
			case 'tinyint':
				$length = 4;
				break;

			case 'smallint':
				$length = 6;
				break;

			case 'mediumint':
				$length = 8;
				break;

			case 'int':
				$length = 11;
				break;

			case 'bigint':
				$length = 20;
				break;

			case 'float':
				$length = 0;
				break;

			default:
		}

		//	...
		if( $unsigned and $length ){
			$length--;
		}

		//	...
		return $length ?? null;
	}
}
