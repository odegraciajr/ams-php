<?php

class Db
{
	private static $db;
	
	public static function init()
	{
		if (!self::$db)
		{
			try {
				$dsn = 'mysql:host='.self::getDbInfo('host').';dbname='.self::getDbInfo('name');
				$options = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				); 
				self::$db = new PDO($dsn, self::getDbInfo('user'), self::getDbInfo('pass'), $options);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				
			} catch (PDOException $e) {
				die('Connection error: ' . $e->getMessage());
			}
		}
		return self::$db;
	}
	
	private static function getDbInfo($info){
	
		$app_config = App::getConfig();
		return $app_config['db'][$info];
	}
}