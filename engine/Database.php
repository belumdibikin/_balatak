<?php

class Database {
	private static $_INSTANCE = null;
	private static $_HOST = "127.0.0.1";
	private static $_DBNAME = "balatak";
	private static $_USERNAME = "root";
	private static $_PASSWORD = "";

	public function __construct(){
	}

	public static function getInstance() {
		if(!isset(self::$_INSTANCE)) {
			$_OPTIONS[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			// $_OPTIONS[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
			try{
				self::$_INSTANCE = new PDO('mysql:host=' . self::$_HOST . ';dbname=' . self::$_DBNAME, self::$_USERNAME, self::$_PASSWORD, $_OPTIONS);
			}catch(PDOException $e){
				if($e->getCode() == 1049){
					
					$_CREATEDB = new PDO('mysql:host=' . self::$_HOST, self::$_USERNAME, self::$_PASSWORD, $_OPTIONS);
					$_STMT = $_CREATEDB->prepare("CREATE DATABASE ". self::$_DBNAME);
					$_STMT->execute();
					self::$_INSTANCE = new PDO('mysql:host=' . self::$_HOST . ';dbname=' . self::$_DBNAME, self::$_USERNAME, self::$_PASSWORD, $_OPTIONS);
				}
			}
		}
		return self::$_INSTANCE;
	}

}