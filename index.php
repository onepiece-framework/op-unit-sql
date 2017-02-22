<?php
/**
 * unit-sql:/index.php
 *
 * @created   2016-11-29
 * @version   1.0
 * @package   unit-sql
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Tomoaki Nagahara All right reserved.
 */

//	...
include(__DIR__.'/SQL.class.php');

//	Set auto loader.
spl_autoload_register( function ($path) {
	//	...
	if( strpos($path, 'SQL\\') === false ){
		return;
	}

	//	...
	$name = substr($path, 4);

	//	...
	$path = __DIR__."/{$name}.class.php";

	//	...
	if( file_exists($path) ){
		include($path);
	}else{
		Notice::Set("Does not exists this file. ($path)");
	}
});