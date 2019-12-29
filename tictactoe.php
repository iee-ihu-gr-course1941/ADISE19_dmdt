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
	
	if(isset($_SERVER['HTTP_X_TOKEN'])) {
	$input['token']=$_SERVER['HTTP_X_TOKEN'];
	}
	
	
	switch ($r=array_shift($request)) {
		case 'game':	
							switch ($b=array_shift($request))
							{
								case "": 
								case null:	
									gameupdate($input);
									break;
								case "join":
									start_game($input);
									break;
								case "won":
									wonGame($input);
									break;
								default: header("HTTP/1.1 404 Not Found");
							}
						
		case 'board' : 
						 switch ($b=array_shift($request)) {
									case '':
									case null: show_board($input);;
													break;
									case 'piece': handle_piece($method, $request[0],$request[1],$input);
													break;

									default: header("HTTP/1.1 404 Not Found");
													break;
			 }
			 break;
		case 'status': 
				if(sizeof($request)==0) {show_status($input);}
				else {header("HTTP/1.1 404 Not Found");}
				break;
		case 'players': handle_Player($method, $request,$input);
				break;
		default:  header("HTTP/1.1 404 Not Found");
							exit;
}

function handle_piece($method, $x,$y,$input) {
        if ($method=='PUT')
			play_pos($x,$y,$input);
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