<?php 

	function postInChat($input)
{
	global $mysqli;
	
	$sql='INSERT INTO chat (username, msg) VALUES (?,?)';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ss',$input['username'],$input['msg']);
	$r = $st->execute();
}

function getChat()
{
	global $mysqli;
	
	$sql="SELECT  username,msg FROM chat;";
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
?> 