<?php 
session_start();
global $mysqli;

function handleLogin($name)
{
	$sql="SELECT $id FROM players WHERE name=$name";
	$result = mysqli_query( $mysqli , $sql );
	$player = mysqli_fetch_row($result);
	if (!isset($player[0]))
	{
		$sql="INSERT INTO players( name ) VALUES ('$name')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		$_SESSION["Player"]=$id;
	}
	else
		$_SESSION["Player"]=$player[0];
}
function handleUser($method,$request,$input)
{
	switch ($method)
	{
		case 'GET':	
					if ($request=='')
						show_users();
					else show_user($request);
					break;
		case 'POST':
					switch (array_shift($request))
					{
						case 'login':	handleLogin();
										break;
						case 'update':
										updateUser(array_shift($request),$request);
										break;
						default: 		header("HTTP/1.1 404 Not Found");		
					}
					break;
		default: 	header("HTTP/1.1 400 Bad Request"); 
					print json_encode(['errormesg'=>"Method $method not allowed here."]);
	}
	
}
function updateUser($var,$request)
{
	$sql="SELECT won,lost FROM players WHERE id=".$_SESSION["Player"];
	$result = mysqli_query( $mysqli , $sql );
	$info = mysqli_fetch_row($result);
	
	switch($var)
	{
		case 'won': 	$sql="UPDATE game SET won=".info[0]+1;
						$mysqli->query($sql);
						break;
		case 'lost': 	$sql="UPDATE game SET won=".info[1]+1;
						$mysqli->query($sql);
						break;
		default:		header("HTTP/1.1 400 Bad Request");
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
function show_user($request)
{
	global $mysqli;
	$sql = "select * from players where id=$request";
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
?>