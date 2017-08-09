<?php

class Dictionary {
	
	private static $_TABLENAME = "dictionary";
	private $_CREATE = "CREATE TABLE `balatak`.`dictionary` ( `dictionary_id` INT NOT NULL AUTO_INCREMENT , `word` VARCHAR(100) NOT NULL , PRIMARY KEY (`dictionary_id`), UNIQUE `KEYWORD` (`word`));";
	private $_CONNDB = NULL;

	public function __construct(){
		$this->_CONNDB = Database::getInstance();
		$this->initDictionary();
	}

	public function initDictionary(){
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

	public function getWord($dictionary_id){
		$_result = array();
		try{
			$_STMT = $this->_CONNDB->prepare("SELECT * FROM " . self::$_TABLENAME . " WHERE `dictionary_id` = :dictionary_id LIMIT 1");
			$_STMT->bindParam(":dictionary_id", $dictionary_id, PDO::PARAM_INT);
			$_STMT->execute();
			while($_row = $_STMT->fetch(PDO::FETCH_ASSOC)){
				$_result = $_row;
			}
		}catch(PDOException $e){
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
			}
		}
		return $_result;
	}

	public function countWords(){
		$_result = array();
		try{
			$_STMT = $this->_CONNDB->prepare("SELECT count(*) as total FROM " . self::$_TABLENAME . "");
			$_STMT->execute();
			while($_row = $_STMT->fetch(PDO::FETCH_ASSOC)){
				$_result = $_row["total"];
			}
		}catch(PDOException $e){
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
			}
		}
		return $_result;
	}

	public function getDictionary($_offset = 0){
		$results = array();
			try{
				$_order = "dictionary_id";
				$_query = "
				SELECT * FROM (
					SELECT * FROM " . self::$_TABLENAME . " 
					ORDER BY `" . $_order . "` DESC 
					LIMIT 10 
					OFFSET " . $_offset . "
				) as X
				ORDER BY RAND()
				";
				$_STMT = $this->_CONNDB->prepare($_query);
				$_STMT->execute();
				// echo $_STMT->queryString;

				while($_row = $_STMT->fetch(PDO::FETCH_ASSOC)){
					$_row["word"] = str_shuffle($_row["word"]);
					array_push($results, $_row);
				}
			}catch(PDOException $e){
				if($e->getCode() == "42S02"){
					$_STMT = $this->_CONNDB->prepare($this->_CREATE);
					$_STMT->execute();
				}
			}
		return $results;
	}

	public function doAddDictionary($_word){
		$_respond = "successAddDictionary";
		$_word = strtoupper($_word);
		try{
			try {
				$_STMT = $this->_CONNDB->prepare("INSERT INTO " . self::$_TABLENAME . " VALUES('', :word)");
				$_STMT->bindParam(":word", $_word, PDO::PARAM_STR);
				$_STMT->execute();
				$_respond = $this->_CONNDB->lastInsertId();
			} catch (PDOException $e) {
				if ($e->errorInfo[1] == 1062) {
					$_respond = "errorAddDictionary";
				}
			}
		}catch(PDOException $e){
			if($e->getCode() == "42S02"){
				$_STMT = $this->_CONNDB->prepare($this->_CREATE);
				$_STMT->execute();
				$this->doAddNewWord($_word);
			}
		}
		return $_respond;
	}
}
