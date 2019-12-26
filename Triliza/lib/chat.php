<?php 

function postInChat($input)
{
	global $mysqli;
	
	$sql="INSERT INTO chat (username, msg) VALUES (".$input["username"].",".$input["msg"].")";
	$st = $mysqli->prepare($sql);
	$r = $st->execute();
}

function getChat()
{
	global $mysqli;
	
	$sql="SELECT  username,msg FROM chat LIMIT 6;";
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
?> 