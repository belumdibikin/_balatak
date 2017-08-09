<?php
session_start();
class Game extends Controller{
	
	public function __construct(){
	}

	public function init(){
		$dataSend["dictionary_id"] = array("Peter"=>"35","Ben"=>"37","Joe"=>"43");
		$_view = $this->loadView("home", $dataSend);
		echo $_view;
	}

	public function doSubmitScore($_player, $_score){
		$model_score = $this->loadModel("Score");
		echo $model_score->submitScore($_player, $_score);
	}

	public function doGetScore(){
		$model_score = $this->loadModel("Score");

		$dataSend = array();
		$dataSend["status"] = "failed";
		$dataSend["score"] = array();

		$dataSend["score"] = $model_score->getScore();
		if(count($dataSend["score"]) > 0) $dataSend["status"] = "success";
		
		header('Content-Type: application/json');
		echo json_encode($dataSend);
	}

	public function getSuperToken(){
		$_return = array();
		$_return["status"] = "failed";
		if($_POST["password"] == "kosonginaja"){
			$_return["status"] = "success";
			$_return["token"] = bin2hex(random_bytes(10));
			$_return["tokentime"] = time();
			$_SESSION["token"] = $_return["token"];
			$_SESSION["tokentime"] = $_return["tokentime"];
		}

		header('Content-Type: application/json');
		echo json_encode($_return);
	}

	public function checkTokenThenAdd(){
		$_return = array();
		if($_SESSION["token"] == $_POST["token"] && (time() - $_SESSION["tokentime"]) < 300){
			$_return["status"] = $this->doAddNewWord($_POST["newword"]);
			$_return["token"] = $_SESSION["token"];
			$_return["tokentime"] = $_SESSION["tokentime"];
		}else{
			$_return["status"] = "Token Invalid";
			$_return["token"] = "";
			$_return["tokentime"] = "";
			$_SESSION["token"] = $_return["token"];
			$_SESSION["tokentime"] = $_return["tokentime"];
			session_unset();
		}

		header('Content-Type: application/json');
		echo json_encode($_return);
	}

	public function doCheckAnswer($_dictionary_id, $_answer){
		$model_dictionary = $this->loadModel("Dictionary");
		$_result = array();
		$_result = $model_dictionary->getWord($_dictionary_id);

		if($_result["word"] == $_answer){
			$_result["status"] = "Benar";
		}else{
			$_result["status"] = "False";
		}

		header('Content-Type: application/json');
		echo json_encode($_result);
	}

	public function doGetWords($_offset = 0, $_source = "database"){
		$model_dictionary = $this->loadModel("Dictionary");

		$dataSend = array();
		$dataSend["words"] = array();
		$dataSend["source"] = $_source;
		$dataSend["offset"] = $_offset;

		if($dataSend["source"] == "database"){
			//Jika dalam database masih ada kata
			$dataSend["words"] = $model_dictionary->getDictionary($dataSend["offset"]);
			if($model_dictionary->countWords() <= $_offset + 10){
				$dataSend["source"] = "api";
			}else{
				$dataSend["source"] = "database";
				$dataSend["offset"] += 10;
			}
		}else{
			//Jika kata dalam database sudah habis
			for($count = 0; $count < 5; $count++){
				$_word = $this->doCallAPIWord();
				$_respond = $this->doAddNewWord($_word);

				while($_respond == "errorAddDictionary"){
					$_word = $this->doCallAPIWord();
					$_respond = $this->doAddNewWord($_word, "api");
				}
				$_temp = array();
				$_temp["dictionary_id"] = $_respond;
				$_temp["word"] = str_shuffle($_word);
				array_push($dataSend["words"], $_temp);
			}
		}

		if(count($dataSend["words"]) != 0){
			// print_r($dataSend);
			header('Content-Type: application/json');
			echo json_encode($dataSend);
		}else{
			$this->doGetWords($dataSend["offset"], $dataSend["source"]);
		}
	}

	//Memvalidasi kata lalu memasukkannya kedalam database
	public function doAddNewWord($_word, $_source = "admin"){

		$model_dictionary = $this->loadModel("Dictionary");
		$_status = "Kata terdaftar di Kamus Oxford";
		if($_source == "admin"){
			$_status = $this->doWordValidation($_word);
		}

		if($_status == "Kata terdaftar di Kamus Oxford"){
			$_status = $model_dictionary->doAddDictionary($_word);
		}

		return $_status;
	}
	
	//Mengambil kata random dari watchout4snakes.com
	public function doCallAPIWord(){
		$_post = [];

		$_ch = curl_init('http://watchout4snakes.com/wo4snakes/Random/RandomWord');
		curl_setopt($_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($_ch, CURLOPT_POST, 1);
		curl_setopt($_ch, CURLOPT_POSTFIELDS, $_post);

		$_word = curl_exec($_ch);
		$_word = strtoupper($_word);

		curl_close($_ch);

		return $_word;

		// if($this->doWordValidation($_word) == "Kata terdaftar di Kamus Oxford"){
		// 	return $_word;
		// }else{
		// 	$this->doCallAPIWord();
		// }

	}

	//Crosscheck kata dengan API Oxford Dictionaries
	public function doWordValidation($_word){
		$_headers = [
		'app_id: 501d588e',
		'app_key: 0cf6f4297554fa8328b4c46068b5ffa9',
		'Accept: application/json'
		];
		$_url = "https://od-api.oxforddictionaries.com:443/api/v1/inflections/en/$_word";
		$_post = [];

		$_ch = curl_init($_url);
		curl_setopt($_ch, CURLOPT_HTTPHEADER, $_headers);
		curl_setopt($_ch, CURLOPT_HTTPGET, 1);
		curl_setopt($_ch, CURLOPT_RETURNTRANSFER, true);

		$_word = curl_exec($_ch);
		curl_close($_ch);

		if($_word[0] == "{"){
			return "Kata terdaftar di Kamus Oxford";
		}else{
			return "Kata tidak terdaftar di Kamus Oxford";
		}
	}
}