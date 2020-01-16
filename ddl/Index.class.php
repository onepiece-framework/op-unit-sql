<?php
/**
 * unit-sql:/Index.class.php
 *
 * @created   2017-12-13
 * @updated   2019-04-09  Correspond to IF.
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2017-12-13  OP\UNIT\SQL
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

/** Index
 *
 * @created   2017-12-13
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Index
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Database
	 *
	 * @created   2019-04-15
	 * @var      \OP\UNIT\Database
	 */
	private $_DB;

	/** Construct.
	 *
	 * @created   2019-04-15
	 * @param   &IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		//	...
		$this->_DB = & $_DB;
	}

	/** Create index
	 *
	 * @param	 array		 $config
	 * @return	 string
	 */
	function Create($config)
	{
		//	...
		$database = $config['database'] ?? null;
		$table    = $config['table']    ?? null;
		$name     = $config['name']     ?? null;
		$type     = $config['type']     ?? null;
		$columns  = $config['column']   ?? $config['columns'] ?? null;
	//	$comment  = $config['comment']  ?? null;

		//	...
		$database = $this->_DB->Quote($database);
		$table    = $this->_DB->Quote($table);
		$name     = $this->_DB->Quote($name);
		$type     = self::Type($type);

		//	...
		if( is_string($columns) ){
			$columns = explode(',', $columns);
		};

		//	...
		$join = [];
		foreach( $columns as $column ){
			$join[] = $this->_DB->Quote($column);
		}
		$columns = join(',', $join);

		//	...
		return "ALTER TABLE $database.$table ADD $type $name ($columns)";
	}

	/** Drop index
	 *
	 * @param	 string		 $database
	 * @param	 string		 $table
	 * @param	 string		 $name
	 * @param	 string		 $type
	 * @return	 string		 $sql
	 */
	function Drop($database, $table, $name, $type)
	{
		//	...
		$database = $this->_DB->Quote($database);
		$table    = $this->_DB->Quote($table);

		//	...
		switch( strtolower($type) ){
			case 'pri':
				$specify = 'PRIMARY KEY';
				break;
		}

		//	...
		return "ALTER TABLE $database.$table DROP $specify";
	}

	/** Get index type by filed type.
	 *
	 * @param  string $field_type
	 * @param  string $index_key_type
	 * @return string
	 */
	static function Type($index_key_type, $field_type=null)
	{
		//	...
		switch( $key = strtoupper($index_key_type) ){
			case 'AI':
			case 'PRI':
			case 'PKEY':
			case 'PRIMARY':
			case 'PRIMARY KEY':
				$key = 'PRIMARY KEY';
				break;

			case 'UNI':
			case 'UNIQUE':
				$key = 'UNIQUE';
				break;

			case 'MUL':
				switch( strtolower($field_type) ){
					case 'char':
					case 'varchar':
					case 'text':
						$key = 'FULLTEXT';
						break;
					default:
						$key = 'INDEX';
				}
				break;

			case 'INDEX':
			case 'SPATIAL':
			case 'FULLTEXT':
				break;

			default:
				throw new Exception("Has not been support this key. ($key)");
		}

		//	...
		return $key;
	}
}

/*
 ALTER TABLE `test`.`t_test` ADD PRIMARY KEY(`ai`);
 ALTER TABLE `test`.`t_test` ADD UNIQUE(`id`);
 ALTER TABLE `test`.`t_test` ADD UNIQUE `unique` (`id`);
 */
