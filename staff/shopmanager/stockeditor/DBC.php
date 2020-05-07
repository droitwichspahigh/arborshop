<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2014 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
require "../../../bin/classes.php";

class DBC
{
	private static $instance = null;
	private static $iniFile = '../../dbc.ini';
	
	public static function get()
	{
		if(self::$instance == null)
		{
			try
			{
				self::$instance = new PDO(
				    'mysql:host=' . \ArborShop\Config::$db['host'] . ';dbname=' . \ArborShop\Config::$db['name'], 
				    \ArborShop\Config::$db['user'], 
				    \ArborShop\Config::$db['password'],
					array(
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					)
				);
			} 
			catch(PDOException $e)
			{
				echo "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
		return self::$instance;
	}
	
	public function getPrepareSets($arr)
	{
		$prepareSets = array();
		foreach($arr as $column => $value)
		{
			$prepareSets[] = "`$column` = :".$column;
			$value; /* Silence warning! */
		}
		return $prepareSets;
	}
	
}
?>
