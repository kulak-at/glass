<?php

namespace Glass;

/**
 * Wrapper providing database instance. 
 * @author Kacper Kula <kulak@kulak.at>
 */
class Db {
	protected static $dbHandler = null;
	protected static $settings = array();
	protected $dbInstances = array();
	
	
	private function __construct() {}
	
	public static function setConfig($settings) {
		self::$settings = $settings;
	}
	
	public static function getConfig($el) {
		return self::$settings[$el];
	}
	
	/**
	 * Returns database handler.
	 * @return \PDO PDO Instance
	 */
	public static function getDb() {
		if(self::$dbHandler)
			return self::$dbHandler;
		
		// creating new PDO instance.
		
		$dsn = 'pgsql:dbname='.self::$settings['dbname'] . ';host=' . self::$settings['host'];
		
		self::$dbHandler = new \PDO($dsn,self::$settings['username'],self::$settings['password']);
		return self::$dbHandler;
	}
	
	
}
