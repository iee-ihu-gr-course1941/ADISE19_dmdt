<?php

function show_board() {
	
	global $mysqli;
	
	$sql =  "SELECT piece,posX,posY FROM moves where game_id=".$_SESSION['gameID'];
	$st = $mysqli->prepare($sql);

	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);   
    
    
    

}

function remove_board() {
	global $mysqli;
	
	$sql = "call clean_board(".$_SESSION['gameID'].")";
	$mysqli->query($sql);
	show_board();
}

function new_board() {
	global $mysqli;
	
	$sql = "call new_board(".$_SESSION['gameID'].")";
	$mysqli->query($sql);
	show_board();
}


?>