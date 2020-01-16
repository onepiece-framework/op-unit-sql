<?php
/**
 * unit-sql:/Database.class.php
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2018-04-25
 */
namespace OP\UNIT;

/** Used class
 *
 * @created   2019-03-04
 */
use Exception;
use OP\IF_UNIT;
use OP\IF_SQL;
use OP\IF_DATABASE;

/** Database
 *
 * @creation  2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class SQL implements IF_SQL, IF_UNIT
{
	/** trait
	 *
	 */
	use \OP\OP_CORE, \OP\OP_UNIT;

	/** Database
	 *
	 * @var \OP\UNIT\Database
	 */
	private $_DB;

	/** DDL
	 *
	 * @var SQL\DDL
	 */
	private $_DDL;

	/** DML
	 *
	 * @var SQL\DML
	 */
	private $_DML;

	/** DCL
	 *
	 * @var SQL\DCL
	 */
	private $_DCL;

	/** Set Database Object.
	 *
	 * @creation  2019-04-09
	 * @param    &IF_DATABASE
	 */
	public function DB( IF_DATABASE & $DB )
	{
		//	...
		if( $this->_DB ){
			throw new Exception("Database object is already set.");
		}

		//	...
		$this->_DB = & $DB;
	}

	/** Data Definition Language.
	 *
	 * @creation  2019-01-08
	 * @return    SQL\DDL
	 */
	public function DDL()
	{
		//	...
		if(!$this->_DDL ){
			//	...
			include_once(__DIR__.'/DDL.class.php');

			//	...
			$this->_DDL = new SQL\DDL($this->_DB);
		};

		//	...
		return $this->_DDL;
	}

	/** Data Manipulation Language.
	 *
	 * @creation  2019-01-08
	 * @return    SQL\DML
	 */
	public function DML()
	{
		//	...
		if(!$this->_DML ){
			//	...
			include_once(__DIR__.'/DML.class.php');

			//	...
			$this->_DML = new SQL\DML($this->_DB);
		};

		//	...
		return $this->_DML;
	}

	/** Data Control Language
	 *
	 * @creation  2019-01-08
	 * @return    SQL\DCL
	 */
	public function DCL()
	{
		//	...
		if(!$this->_DCL ){
			//	...
			include_once(__DIR__.'/DCL.class.php');

			//	...
			$this->_DCL = new SQL\DCL($this->_DB);
		};

		//	...
		return $this->_DCL;
	}
}
