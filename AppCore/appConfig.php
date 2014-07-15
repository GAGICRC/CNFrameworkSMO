<?php

class appConfig {
	
	private static $configFile = "/appConfig.xml";
	
	private static function loadXML() {
		return (object)(array)simplexml_load_file(__DIR__ . self::$configFile);
	}
	
	public static function getDBSettings() {
		$storedConfig = self::loadXML();
		return $storedConfig->dbsettings;
	}
	
	public static function setDBSettings($newSettings) {
		
	}
	
}

?>