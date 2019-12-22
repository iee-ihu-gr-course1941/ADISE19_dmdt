<?php
session_start();
global $mysqli;

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
}

function new_board() {
	global $mysqli;
	
	$sql = "call new_board(".$_SESSION['gameID'].")";
	$mysqli->query($sql);
	show_board();
}

function play_pos($X,$Y){
	global $mysqli;

	$sql="UPDATE moves SET x=$X, y=$Y where id=".$_SESSION["gameID"];
	$mysqli->query($sql);
}

function get_turn()
{
	global $mysqli;
	$result = mysqli_query( $mysqli , "SELECT turn FROM game WHERE id=".$_SESSION['gameID'] );
	$line = mysqli_fetch_row($result);
	echo json_encode($line[0]);
}

function endGame()
{
	$sql="UPDATE game SET status='ended' where id=".$_SESSION["gameID"];
	$mysqli->query($sql);
	unset($_SESSION["gameID"]);
}

?>