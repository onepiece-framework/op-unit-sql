<?php
/**
 * unit-sql:/select.class.php
 *
 * @created   2016-11-28
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/**
 * select
 *
 * @created   2016-11-29
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */
class select extends OnePiece
{
	static function Get($args)
	{
		$table = $args['table'];
		return "SELECT * FROM $table";
	}
}