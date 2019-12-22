<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php 
	
	require_once "lib/dbconnect.php";
	require_once "lib/board.php";
	require_once "lib/game.php";
	require_once "lib/users.php";
	
	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	$input = json_decode(file_get_contents('php://input'),true);
	
	switch ($r=array_shift($request)) {
		case 'game':	
						if(isset($_SESSION["Player"]))
						{
							switch ($b=array_shift($request))
							{
								case "join":
									start_game($request);
								case "end":
									endGame();
								case "result":
									if ($request=="x" or $request=="o")
									wonGame($request);
									else
									header("HTTP/1.1 404 Not Found");
								default: header("HTTP/1.1 404 Not Found");
							}
						}
						else
						{
							header("HTTP/1.1 400 Bad Request"); 
							print json_encode(['errormesg'=>"Player is not set"]);	
						}
		case 'board' : 
						 switch ($b=array_shift($request)) {
									case '':
									case null: handle_board($method);
													break;
									case 'piece': handle_piece($method, $request[0],$request[1],$input);
													break;

									default: header("HTTP/1.1 404 Not Found");
													break;
			 }
			 break;
		case 'status': 
				if(sizeof($request)==0) {show_status();}
				else {header("HTTP/1.1 404 Not Found");}
				break;
		case 'players': handle_Player($method, $request,$input);
				break;
		default:  header("HTTP/1.1 404 Not Found");
							exit;
}
function handle_board($method) {
 
       if($method=='GET') {
		   show_board();
       } else if ($method=='POST') {
		   reset_board();
		   show_board();
       }
		
}
function handle_piece($method, $x,$y) {
        if ($method=='POST')
			play_pos($x,$y);
		else
		{
			header("HTTP/1.1 400 Bad Request"); 
			print json_encode(['errormesg'=>"Method $method not allowed here."]);
		}
}
 
function handle_player($method, $request,$input) {
	
	handleUser($method,$request,$input);
	
}
	?>
</body>
</html>