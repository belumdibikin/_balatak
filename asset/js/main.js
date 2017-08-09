$(function() {
	var get_url = window.location;
	var base_url = get_url.protocol + "//" + get_url.host + get_url.pathname +"?";
	var _ajax = "";

	var _offset = 0;
	var _source = "database";
	var _url = "";

	var _arrTrue = ["Congratulation !", "You're a master !", "Nice One !", "You're on fire !", "Good Job !"];
	var _arrFalse = ["Oops...", "Sorry...", "Seriously?", "Let's try again !", "No !"];
	var _feedbackTO = "";
	var _superTO = "";
	var _holder = true;
	var _holderA = true;

	var _arrQuestions = [];
	var _curQuestion = 0;
	var _score = 0;
	var _highscore = 0;

	var _token = "";
	var _tokentime = "";

	$("#game_answer").focus();
	$("#game_feedback").html("&nbsp;");

	var disableAnswer = function(_opt){
		$("#game_submit").prop("disabled", _opt);
		$("#game_answer").prop("disabled", _opt);
		$("#game_answer").focus();
	};

	var disableAdd = function(_opt){
		$("#super_word").prop("disabled", _opt);
		$("#super_add").prop("disabled", _opt);
		$("#super_word").focus();
	};

	var getWord = function(){
		$("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS");
		_url = base_url + "Game/doGetWords/" + _offset + "/" + _source;
		console.log(_url);
		_ajax = $.ajax({
			url: _url,
			async: true,
			success: function(data){
				data["words"].forEach(function(_temp){
					_arrQuestions.push(_temp);
				});
				_offset = data["offset"];
				_source = data["source"];
				if($("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS")){
					createQuestion();
				}
			}
		});
	}

	var createQuestion = function(){
		$("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS");
		if(_arrQuestions.length == 2){
			getWord();
		}else if(_arrQuestions.length > 0){
			$("#game_question").html(_arrQuestions[0]["word"]);
			_curQuestion = _arrQuestions[0]["dictionary_id"];
			_arrQuestions.shift();
			disableAnswer(false);
			_holderA = true;
		}else{
			$("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS");
		}
	}

	var shareTwitter = function(){
		window.open("https://twitter.com/intent/tweet?url="+base_url+"&amp;text=My Score is "+_highscore+", how about you\?;hashtags=balatak", "_blank", "width=auto,height=auto");
	}

	var shareFacebook = function(){
		window.open("https://www.facebook.com/sharer/sharer.php?url="+encodeURI(base_url), "_blank", "width=auto,height=auto");
	}

	$("#game_twitter").click(function(){
		shareTwitter();
	})

	$("#game_super").click(function(){
		$("#super_two").hide();
		$("#super_first").show();
		$("#super_modal").modal({
			backdrop: "static"
		})
	})

	$('#super_modal').on('shown.bs.modal show.bs.modal', function (e) {
		$("#super_pass").focus();

		$("#super_word").bind('keyup blur',function(e){
			if(e.keyCode == 13){
				$("#super_add").click();
			}else{
				var node = $(this);
				node.val(node.val().replace(/[^a-z-A-Z]/g,''));
			}
		});

		$("#super_pass").bind('keyup blur',function(e){
			if(e.keyCode == 13){
				$("#super_login").click();
			}
		});
	})

	$("#super_login").click(function(){
		_url = base_url + "Game/getSuperToken";
		console.log($("#super_pass").val());
		$.ajax({
			url: _url,
			async: false,
			type: "POST",
			data: {
				"password": $("#super_pass").val()
			},
			success: function(data){
				console.log(data);
				if(data["status"] != "failed"){
					_tokentime = data["tokentime"];
					_token = data["token"];
					$("#super_two").show();
					$("#super_first").hide();
					$("#super_feedback").html("Success !");
					$("#super_feedback").css("color", "#00FF00");
					$("#super_word").focus();
				}else{
					$("#super_feedback").html("Wrong Password !");
					$("#super_feedback").css("color", "#FF0000");
				}
				clearTimeout(_superTO);
				_superTO = setTimeout(function(){
					$("#super_feedback").css("color", "#000000");				
					$("#super_feedback").html("&nbsp;");
					$("#super_pass").val("");
				}, 2000);
			}
		});
	})

	$("#super_add").click(function(){
		_url = base_url + "Game/checkTokenThenAdd";
		disableAdd(true);
		if($("#super_word").prop("disabled") == true && _holder == true){
			_holder = false;
			$.ajax({
				url: _url,
				async: false,
				type: "POST",
				data: {
					"token": _token,
					"tokentime": _tokentime,
					"newword": $("#super_word").val()
				},
				success: function(data){
					console.log(data);
					if(parseInt(data["status"]) > 0){
						_tokentime = data["tokentime"];
						_token = data["token"];
						$("#super_two").show();
						$("#super_first").hide();
						$("#super_feedback").html("Kata terdaftar dalam Kamus Oxford, dan berhasil ditambahkan kedalam database.");
						$("#super_feedback").css("color", "#00FF00");
					}else{
						if(data["status"] == "errorAddDictionary"){
							data["status"] = "Kata sudah ada didalam database.";
						}else if(data["status"] == "Token Invalid"){
							$("#super_first").show();
							$("#super_two").hide();
						}
						$("#super_feedback").html(data["status"]);
						$("#super_feedback").css("color", "#FF0000");
					}
					clearTimeout(_superTO);
					_superTO = setTimeout(function(){
						$("#super_feedback").css("color", "#000000");				
						$("#super_feedback").html("&nbsp;");
						$("#super_word").val("");
						disableAdd(false);
						_holder = true;
					}, 2000);
				}
			});
		}
	})

	$("#game_facebook").click(function(){
		// shareFacebook();
		// shareFacebook();
	})
	$("#game_facebook").prop("disabled", true);

	var checkAnswer = function(){
		disableAnswer(true);
		if($("#game_answer").prop("disabled") == true && _holderA == true){
			_holderA = false;
			_answer = $("#game_answer").val();
			_answer = _answer.toUpperCase();
			_url = base_url + "Game/doCheckAnswer/" + _curQuestion + "/" + _answer;
			$.ajax({
				url: _url,
				async: false,
				success: function(data){
					_feedback = "";
					console.log(_curQuestion + " " + _answer + " " + data["word"]);
					if(data["status"] == "Benar"){
						_feedback += "+1 " + _arrTrue[Math.floor(Math.random() * _arrTrue.length)];
						$("#game_feedback").css("color", "#00FF00");
						$("#game_score").css("color", "#00FF00");
						$("#game_score").html(_score + " +1");
						_score += 1;
					}else{
						_feedback += "-1 " + _arrFalse[Math.floor(Math.random() * _arrFalse.length)];
						$("#game_feedback").css("color", "#FF0000");				
						$("#game_score").css("color", "#FF0000");				
						$("#game_score").html(_score + " -1");
						_score -= 1;	
					}

					_feedback += " The answer is : <b>" + data["word"] + "</b>";

					$("#game_feedback").html(_feedback);
					$("#game_answer").val("");
					createQuestion();

					clearTimeout(_feedbackTO);
					_feedbackTO = setTimeout(function(){
						if(_highscore < _score){
							_highscore = _score;
							$("#game_highscore").html(_highscore);
						}
						$("#game_score").css("color", "#000000");				
						$("#game_score").html(_score);
						$("#game_feedback").html("&nbsp;");
					}, 2000);
				}
			});
		}
	}

	$("#game_answer").bind('keyup blur',function(e){ 
		if(e.keyCode == 13){
			$("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS");
			checkAnswer();
		}else{
			var node = $(this);
			node.val(node.val().replace(/[^a-z-A-Z]/g,''));
		}
	});

	$("#game_submit").click(function(){
		$("#game_question").html("<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS");
		checkAnswer();
	});

	disableAnswer(true);
	_url = base_url + "Game/doGetWords/" + _offset + "/" + _source;
	$.ajax({
		url: _url,
		async: true,
		success: function(data){
			data["words"].forEach(function(_temp){
				_arrQuestions.push(_temp);
			});
			_offset = data["offset"];
			_source = data["source"];

			createQuestion();
		}
	});
});
