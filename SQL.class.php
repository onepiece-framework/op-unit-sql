<?php
/**
 * unit-sql:/SQL.class.php
 *
 * @created   2016-11-28
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 * @creation  2017-12-05
 */
namespace OP\UNIT\SQL;

/** SQL
 *
 * @created   2016-11-29
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class SQL
{
	/** trait
	 *
	 */
	use OP_CORE;

	/** Database unit.
	 *
	 * @var db
	 */
	private $_db;

	/** Count
	 *
	 * @param  array  $args
	 * @return string
	 */
	function Count($args)
	{
		$args['column']	 = ['COUNT'=>'*'];
		$args['limit']	 = 1;
		return SQL\Select::Get($args, $this->_db);
	}

	/** Generate delete sql statement.
	 *
	 * @param  array $args
	 * @return string
	 */
	function Delete($args)
	{
		return SQL\Delete::Get($args, $this->_db);
	}

	/** Generate insert sql statement.
	 *
	 * @param  array $args
	 * @return string
	 */
	function Insert($args)
	{
		return SQL\Insert::Get($args, $this->_db);
	}

	/** Generate select sql statement.
	 *
	 * @param  array $args
	 * @return string
	 */
	function Select($args)
	{
		return SQL\Select::Get($args, $this->_db);
	}

	/** Set database unit object.
	 *
	 * @param db $db
	 */
	function SetDatabase($db)
	{
		$this->_db = $db;
	}

	/** Generate show sql statement.
	 *
	 * @param  array $args
	 * @return string
	 */
	function Show($args=null)
	{
		return SQL\Show::Get($args, $this->_db);
	}

	/** Generate update sql statement.
	 *
	 * @param  array $args
	 * @return string
	 */
	function Update($args)
	{
		return SQL\Update::Get($args, $this->_db);
	}
}