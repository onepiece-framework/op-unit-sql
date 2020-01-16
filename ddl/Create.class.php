<?php
/**
 * unit-sql:/DDL/Create.class.php
 *
 * @created   2019-04-09  Correspond to IF.
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-04-09  OP\UNIT\SQL\DDL
 */
namespace OP\UNIT\SQL\DDL;

/** Used class
 *
 * @created   2019-04-09
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;
use OP\IF_SQL_DDL_CREATE;
use function OP\ifset;

/** Create
 *
 * @created   2019-04-09
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Create implements IF_SQL_DDL_CREATE
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Database
	 *
	 * @creation  2019-04-09
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

		//	...
		include_once(__DIR__.'/Column.class.php');
		include_once(__DIR__.'/Index.class.php');
	}

	/** Generate Create database SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Database(array $config)
	{

	}

	/** Generate Create table SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Table(array $config)
	{
		//	...
		$prod    = $this->_DB->Config()['prod'];
		$database= ifset($config['database']);
		$table   = ifset($config['table']   );
		$engine  = ifset($config['engine']  , 'InnoDB'            );
		$charset = ifset($config['charset'] , 'utf8mb4'           );
		$collate = ifset($config['collate'] , 'utf8mb4_general_ci');

		//	...
		if( isset($config['fields']) ){
			$fields = $config['fields'];
		}else if( isset($config['field']) ){
			$fields = $config['field'];
		}else if( isset($config['columns']) ){
			$fields = $config['columns'];
		}else if( isset($config['column']) ){
			$fields = $config['column'];
		}else{
			throw new Exception("Has not been set field. ($database, $table)");
		}

		//	...
		$col = $ind = [];
		foreach( $fields as $name => $field ){
			//	...
			$field['field'] = $name;
			$col[] = $this->Column()->Field($field, $this->_DB, null);

			//	...
			if( isset($field['key']) /* or isset($field['index']) */ ){
				$ind[] = $this->Column()->Index($field, $this->_DB);
			}
		}
		$columns = join(', ', array_merge($col, $ind));

		//	...
		$database = $this->_DB->Quote($database);
		$table    = $this->_DB->Quote($table);
		$engine   = $this->_DB->Quote($engine);
		$charset  = $this->_DB->Quote($charset);
		$collate  = $this->_DB->Quote($collate);

		//	...
		switch( $prod ){
			case 'mysql':
				//	...
				$table = "{$database}.{$table}";

				//	...
				$option = "ENGINE=$engine DEFAULT CHARSET=$charset COLLATE $collate";
				break;

			case 'pgsql':
				$option = null;
				break;

			default:
				$option = null;
		};

		//	...
		if( $config['if_not_exists'] ?? null ){
			$table = "IF NOT EXISTS {$table}";
		};

		//	...
		return "CREATE TABLE $table ($columns) $option";
	}

	/** Generate Create Column SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array		 $config
	 * @return   Column
	 */
	public function Column(array $config=[])
	{
		//	...
		$column = new Column($this->_DB);

		//	...
		if( $config ){
			return $column->Create($config);
		}else{
			return $column;
		};
	}

	/** Generate Create Index SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array		 $config
	 * @return   string		 $sql
	 */
	public function Index(array $config=[])
	{
		//	...
		$index = new Index($this->_DB);

		//	...
		if( $config ){
			return $index->Create($config);
		}else{
			return $index;
		};
	}

	/** Generate Create user SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function User(array $config)
	{
		//	...
		include_once(__DIR__.'/User.class.php');

		//	...
		$user = new User($this->_DB);

		//	...
		return $user->Create($config);
	}

	/** Generate Create password SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Password(array $config)
	{
		//	...
		include_once(__DIR__.'/User.class.php');

		//	...
		$user = new User($this->_DB);

		//	...
		return $user->Password($config);
	}
}
