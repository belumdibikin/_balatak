<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>balatak</title>

	<!-- Bootstrap -->
	<link href="asset/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="asset/font-awesome/css/font-awesome.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!--  style="height: 100vh;" -->
	<style>
		#game_highscore, #game_score, #game_feedback, #game_info, #super_feedback{
			font-size: 3vh;
			font-weight: bold;
		}

		#game_question, #game_answer{
			font-size: 5vh;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div class="container container-fluid">
		<header class="header">
			<p>&nbsp;</p>
		</header>
		<button class="btn btn-xs btn-danger pull-right" name="game_super" id="game_super" href="javascript:void(0);">SUPER</button>
		<div class="jumbotron" align="center">
			<div class="panel panel-default">
				<div class="panel-heading clearfix" id="game_info" name="game_info">
					<div class="pull-right">
						Highscore: <span id="game_highscore" name="game_highscore">0</span>
					</div>
					<div class="pull-left">
						Score: <span  id="game_score" name="game_score">0</span>
					</div>
				</div>
				<div class="panel-body" id="game_question" name="game_question">
					<i class='fa fa-spinner fa-pulse fa-fw'></i>GENERATING WORDS
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body clearfix" id="game_feedback" name="game_feedback">
					&nbsp;
				</div>
			</div>
			<div class="lead">
				<input type="text" class="form-control input-lg" name="game_answer" id="game_answer" style="text-transform:uppercase; text-align: center"/>
			</div>
			<p>
				<button class="btn btn-lg btn-success" name="game_submit" id="game_submit" href="javascript:void(0);">SUBMIT</button>
			</p>
			<p>
				<button class="btn btn-info" name="game_twitter" id="game_twitter" href="javascript:void(0);"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i>&nbsp;Tweet</button>
				<button class="btn btn-primary" name="game_facebook" id="game_facebook" href="javascript:void(0);"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i>&nbsp;Share</button>
			</p>
		</div>
		<footer class="footer pull-right clearfix">
			<p>© 2017 belumdibikin</p>
		</footer>

	</div>

	<div id="super_modal" name="super_modal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Super Modal</h4>
				</div>
				<div class="modal-body">
					<div class="input-group clearfix" id="super_first" name="super_first">
						<input type="text" name="super_pass" id="super_pass" class="form-control" placeholder="">
						<span class="input-group-btn">
							<button class="btn btn-warning" name="super_login" id="super_login" href="javascript:void(0);">LOGIN</button>
						</span>
					</div>
					<div class="input-group clearfix" id="super_two" name="super_two">
						<input type="text" name="super_word" style="text-transform:uppercase" id="super_word" class="form-control" placeholder="">
						<span class="input-group-btn">
						<button class="btn btn-warning" name="super_add" id="super_add" href="javascript:void(0);">ADD</button>
						</span>
					</div>
					&nbsp;
					<div class="panel panel-default">
						<div class="panel-body clearfix" id="super_feedback" name="super_feedback">
							&nbsp;
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script src="asset/js/jquery-3.2.1.min.js"></script>
	<script src="asset/js/main.js"></script>
	<script src="asset/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>