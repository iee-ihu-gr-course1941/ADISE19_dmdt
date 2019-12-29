<?php 

function handleLogin($input)
{
	if(!isset($input['username'])) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"No username given."]);
		exit;
	}
	$username=$input['username'];
	
	global $mysqli;
	$sql = "select count(*) as count from players where username='".$username."'";
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);

	if($res[0]['count']>0) {
			$sql = "update players set token=md5(CONCAT( ?, NOW())) where username='".$username."'";
	}
	else
		$sql = "INSERT INTO players( name, token) VALUES ( '$username', md5(CONCAT( ?, NOW())))";
	
	$st2 = $mysqli->prepare($sql);
	$st2->execute();
	
	$sql = "select token from players where name='".$username."'";
	
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}

function handleUser($method,$request,$input)
{
	switch ($method)
	{
		case 'GET':show_user($input);
					break;
		case 'PUT':
					switch (array_shift($request))
					{
						case 'login':	handleLogin($input);
										break;
						default: 		header("HTTP/1.1 404 Not Found");		
					}
					break;
		default: 	header("HTTP/1.1 400 Bad Request"); 
					print json_encode(['errormesg'=>"Method $method not allowed here."]);
	}
	
}

function show_users() {
	
	global $mysqli;
	$sql = "select * from players";
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();
	

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}

function show_user($input)
{
	global $mysqli;
	
	$token=$input['token'];
	
	$sql = "select * from players where token='$token'";
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
?>