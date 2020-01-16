<?php
/**
 * unit-sql:/DCL.class.php
 *
 * @created   2019-03-04
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @created   2019-03-04
 */
namespace OP\UNIT\SQL;

/** Used class
 *
 * @created   2019-03-04
 */
use OP\OP_CORE;
use OP\IF_DATABASE;
use OP\IF_SQL_DCL;

/** DCL
 *
 * @created   2018-04-20
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class DCL implements IF_SQL_DCL
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
	 * @created  2019-01-08
	 * @param    IF_DATABASE $_DB
	 */
	public function __construct(IF_DATABASE & $_DB)
	{
		$this->_DB = & $_DB;
	}

	/** Generate Grant SQL.
	 *
	 * @created  2019-01-08
	 * @param    array      $config
	 * @return   DCL\Grant
	 */
	public function Grant()
	{
		include_once(__DIR__.'/dcl/Grant.class.php');
		return new DCL\Grant($this->_DB);
	}

	/** Generate Revoke SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Revoke(array $config)
	{

	}

	/** Generate Begin SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Begin(array $config)
	{

	}

	/** Generate Commit SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Commit(array $config)
	{

	}

	/** Generate Rollback SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Rollback(array $config)
	{

	}

	/** Generate Lock SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Lock(array $config)
	{

	}

	/** Generate Savepoint SQL.
	 *
	 * @created  2019-01-08
	 * @param	 array		 $config
	 */
	public function Savepoint(array $config)
	{

	}
}
