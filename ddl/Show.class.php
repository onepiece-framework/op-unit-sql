<?php
/**
 * unit-sql:/DDL/Show.class.php
 *
 * @created   2019-04-08  Correspond to IF.
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-04-08
 */
namespace OP\UNIT\SQL\DDL;

/** Used class
 *
 * @created   2019-04-08
 */
use Exception;
use OP\OP_CORE;
use OP\IF_DATABASE;
use OP\IF_SQL_DDL_SHOW;

/** Show
 *
 * @created   2019-04-08
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class Show implements IF_SQL_DDL_SHOW
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
	 * @param    IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		$this->_DB = & $_DB;
	}

	/** Generate Show Database SQL.
	 *
	 * @created  ????
	 * @copied   2019-04-08
	 * @param    array       $config
	 * @return   string      $sql
	 */
	public function Database(array $config=[])
	{
		//	...
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				$sql = 'SHOW DATABASES';
				break;

			case 'pgsql':
				$sql = 'SELECT * FROM "pg_database"';
				break;

			default:
				throw new Exception("Has not been support this product. ($prod)");
		};

		//	...
		return $sql;
	}

	/** Generate Show Table SQL.
	 *
	 * @created  ????
	 * @copied   2019-04-09
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Table(array $config=[])
	{
		//	...
		$database = $config['database'];

		//	...
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				$database = $this->_DB->Quote($database);
				$sql = "SHOW TABLES FROM {$database}";
				break;

			case 'pgsql':
				$sql = 'SELECT * FROM "pg_stat_user_tables"';
				break;

			case 'sqlite':
				$sql = "SELECT * FROM 'sqlite_master' WHERE type='table'";
				break;

			default:
				throw new Exception("Has not been support this product. ($prod)");
		};

		//	...
		return $sql;
	}

	/** Generate Show Column SQL.
	 *
	 * @created  ????
	 * @copied   2019-04-09
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Column(array $config=[])
	{
		//	...
		$database = $config['database'] ?? null;
		$table    = $config['table']    ?? null;

		//	Loop at each key string.
		foreach(['database','table'] as $key){
			//	Check if string has quote.
			if( preg_match('/^[^_a-z]/i', ${$key}) ){
				//	Trim quote string. `t_table` --> t_table
				${$key} = substr(${$key}, 1, strlen(${$key})-2);
			};
		};

		//	Branch to each database.
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				$database = $this->_DB->Quote($database);
				$table    = $this->_DB->Quote($table   );
				$sql = "SHOW FULL COLUMNS FROM {$database}.{$table}";
				break;

			case 'pgsql':
				$table    = $this->_DB->PDO()->quote($table);
				$sql = "SELECT * FROM information_schema.columns WHERE table_name = {$table}";
				break;

			case 'sqlite':
				$table    = $this->_DB->Quote($table);
				$sql = "PRAGMA TABLE_INFO({$table})";
				break;

			default:
				throw new Exception("This product has not been support. ($prod)");
		};

		//	...
		return $sql;
	}

	/** Generate Show Index SQL.
	 *
	 * @created  ????
	 * @copied   2019-04-09
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Index(array $config=[])
	{
		if( 1 ){
			$database = $this->_DB->Quote($config['database']);
			$table    = $this->_DB->Quote($config['table']   );
			return "SHOW INDEX FROM {$database}.{$table}";
		}else{
			/*
			//	...
			if( $database ){
				$database = 'table_schema='.$db->PDO()->Quote($database);
			};

			//	...
			if( $table ){
				$database.= ' AND ';
				$database.= 'table_name='  .$db->PDO()->Quote($table);
			};

			//	...
			return "SELECT * FROM information_schema.statistics WHERE {$database}";
			*/
		};
	}

	/** Generate Show Variables SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Variables(array $config=[])
	{

	}

	/** Generate Show Status SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Status(array $config=[])
	{

	}

	/** Generate Show Grants SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array      $config
	 * @return   string     $sql
	 */
	public function Grants(array $config=[])
	{
		$user = $this->_DB->PDO()->Quote($config['user']);
		$host = $this->_DB->PDO()->Quote($config['host']);
		return "SHOW GRANTS FOR {$user}@{$host}";
	}

	/** Generate Show User SQL.
	 *
	 * @creation  ????
	 * @updation  2019-04-09
	 * @param     array       $config
	 * @return    string      $sql
	 */
	public function User(array $config=[])
	{
		//	...
		switch( $prod = $this->_DB->Config()['prod'] ){
			case 'mysql':
				//	MySQL 5.6
				$sql = "SELECT `host`, `user`, `password` FROM `mysql`.`user`";
				//	MySQL 5.7
				//	$sql = "SELECT `host`, `user`             FROM `mysql`.`user`";
				break;

			case 'pgsql':
				$sql = 'SELECT * FROM "pg_shadow"';
				break;

			default:
				$sql = false;
				throw new Exception("This product has not been support yet. ($prod)");
		}

		//	...
		return $sql;
	}

	/** Generate Show Password SQL.
	 *
	 * @created   ????-??-??  OP\UNIT\SQL\Select
	 * @updated   2019-04-09  OP\UNIT\SQL\DDL\Show
	 * @param     array       $config
	 * @return    string      $sql
	 */
	public function Password(array $config=[])
	{
		$password = $this->_DB->PDO()->Quote($config['password']);
		return "SELECT PASSWORD({$password})";
	}
}
