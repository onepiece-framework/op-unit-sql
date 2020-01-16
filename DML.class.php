<?php
/**
 * unit-sql:/DML.class.php
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
namespace OP\UNIT\SQL;

/** Used class
 *
 * @created   2019-03-04
 */
use OP\OP_CORE;
use OP\IF_DATABASE;
use OP\IF_SQL_DML;

/** DML
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class DML implements IF_SQL_DML
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** IF_DATABASE
	 *
	 * @var IF_DATABASE
	 */
	private $_DB;

	/** Construct.
	 *
	 * @creation 2019-01-08
	 * @param    IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		//	...
		include_once(__DIR__.'/dml/Common.class.php');

		//	...
		$this->_DB = & $_DB;
	}

	/** Generate Insert SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Insert(array $config)
	{
		//	...
		include_once(__DIR__.'/dml/Insert.class.php');

		//	...
		return DML\Insert::SQL($config, $this->_DB);
	}

	/** Generate Select SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Select(array $config)
	{
		//	...
		include_once(__DIR__.'/dml/Select.class.php');

		//	...
		return DML\Select::SQL($config, $this->_DB);
	}

	/** Generate Update SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Update(array $config)
	{
		//	...
		include_once(__DIR__.'/dml/Update.class.php');

		//	...
		return DML\Update::SQL($config, $this->_DB);
	}

	/** Generate Delete SQL.
	 *
	 * @creation 2019-01-08
	 * @param	 array		 $config
	 * @return	 string		 $sql
	 */
	public function Delete(array $config)
	{
		//	...
		include_once(__DIR__.'/dml/Delete.class.php');

		//	...
		return DML\Delete::SQL($config, $this->_DB);
	}
}
