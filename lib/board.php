<?php

function show_board($input) {
	
	global $mysqli;
	
	$sql = "select ID as activegameID from game where (playerx='".$input['token']."' or playery='".$input['token']."') and status='started'";
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	
	$sql= "SELECT posX,posY FROM moves WHERE game=".r[0]['activegameID'];
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);   
      
}

function play_pos($x,$y,$input) 
{
	global $mysqli;
	
	$sql = "select ID as activegameID from game where (playerx='".$input['token']."' or playery='".$input['token']."') and status='started'";
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	
	$sql="update moves set posX=$x,posY=$y piece='".findPlayerPiece($input['token'])."' WHERE game=".r[0]['activegameID'];
	$st = $mysqli->prepare($sql);
	$st->execute();

}

?>