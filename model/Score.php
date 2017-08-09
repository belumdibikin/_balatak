<?php

class Score {
	
	private static $_TABLENAME = "score";
	private $_CREATE = "CREATE TABLE `balatak`.`score` ( `score_id` INT NOT NULL AUTO_INCREMENT , `player` VARCHAR(255) NULL , `score` INT NULL , PRIMARY KEY (`score_id`), UNIQUE `PLAYERNAME` (`player`));";
	private $_CONNDB = NULL;

	public function __construct(){
		$this->_CONNDB = Database::getInstance();
		$this->initScore();
	}

	public function initScore(){
		try{
			$_STMT = $this->_CONNDB->prepare("SELECT * FROM " . self::$_TABLENAME . " LIMIT 1");
			$_STMT->execute();
		}catch(PDOException $e){
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
			}
		}
	}

	public function submitScore($_player, $_score){
		$_respond = "successSubmitScore";
		try{
			try {
				$_STMT = $this->_CONNDB->prepare("INSERT INTO " . self::$_TABLENAME . " VALUES('', :player, :score) ON DUPLICATE KEY UPDATE `score` = :score");
				$_STMT->bindParam(":player", $_player, PDO::PARAM_STR);
				$_STMT->bindParam(":score", $_score, PDO::PARAM_INT);
				$_STMT->execute();
			} catch (PDOException $e) {
				$_respond = $_STMT->queryString;
				if ($e->errorInfo[1] == 1062) {
					$_respond = "errorSubmitScore";
				}
			}
		}catch(PDOException $e){
			$_respond = "errorSubmitScore";
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
			}
		}
		return $_respond;
	}


	public function getScore(){
		$_result = array();
		try{
			$_STMT = $this->_CONNDB->prepare("SELECT * FROM " . self::$_TABLENAME . " ORDER BY `score` DESC LIMIT 10");
			$_STMT->execute();
			while($_row = $_STMT->fetch(PDO::FETCH_ASSOC)){
				array_push($_result, $_row);
			}
		}catch(PDOException $e){
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
			}
		}
		return $_result;
	}
}
