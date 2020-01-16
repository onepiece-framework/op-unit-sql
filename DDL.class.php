<?php
/**
 * unit-sql:/DDL.class.php
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
use OP\IF_SQL_DDL;
use OP\IF_SQL_DDL_DROP;

/** DDL
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class DDL implements IF_SQL_DDL
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
		$this->_DB = & $_DB;
	}

	/** Generate Show Object.
	 *
	 * @creation 2019-01-08
	 * @param    array
	 * @return   DDL\Show
	 */
	public function Show()
	{
		//	...
		include_once(__DIR__.'/ddl/Show.class.php');

		//	...
		return new DDL\Show($this->_DB);
	}

	/** Generate Create Object.
	 *
	 * @creation 2019-01-08
	 * @param    array
	 * @return    DDL\Create
	 */
	public function Create()
	{
		//	...
		include_once(__DIR__.'/ddl/Create.class.php');

		//	...
		return new DDL\Create($this->_DB);
	}

	/** Generate Drop Object.
	 *
	 * @creation 2019-01-08
	 * @param    array
	 * @return   IF_SQL_DDL_DROP
	 */
	public function Drop()
	{

	}

	/** Generate Alter Object.
	 *
	 * @creation 2019-01-08
	 * @param    array
	 * @return   DDL\Alter
	 */
	public function Alter()
	{
		//	...
		include_once(__DIR__.'/ddl/Alter.class.php');

		//	...
		return new DDL\Alter($this->_DB);
	}

	/** Generate Truncate SQL.
	 *
	 * @creation 2019-01-08
	 * @param    array
	 * @return   string
	 */
	public function Truncate()
	{

	}
}
