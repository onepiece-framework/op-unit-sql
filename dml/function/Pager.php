<?php
/** op-unit-sql:/dml/function/Pager.php
 *
 * @creation  2020-06-06
 * @version   1.0
 * @package   op-unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

/** namespace
 *
 */
namespace OP\UNIT\SQL\DML;

/** Pager
 *
 * @created   2020-06-06
 * @param     array        $config
 */
function Pager(&$config){
	//	...
	$pager  = $config['pager'];
	$limit  = $config['limit'];

	//	...
	$config['offset'] = ($limit * $pager) - $limit;
}
