<?php

function show_status() {
	
	global $mysqli;
	$sql = "select * from game where game_id=".$_SESSION['gameID'];
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}

?>
