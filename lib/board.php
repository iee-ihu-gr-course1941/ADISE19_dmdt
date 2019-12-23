<?php
session_start();

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

function play_pos(){
	global $mysqli;
	
	$X=$_POST["posX"];
	$Y=$_POST["posY"];
	//$sql="INSERT INTO moves(game,player,posX,posY) VALUES (".$_SESSION['gameID'].",".$_SESSION['name'].",$X,$Y)";
    $sql="UPDATE  moves set piece VALUES (".$_SESSION['gameID'].",".$_SESSION['XO'].",$X,$Y)";
	
	$mysqli->query($sql);
}

function start_game()
{
	global $mysqli;
	
	$_SESSION["name"]=$_POST["name"];
	$name=$_SESSION["name"];
	
	$result = mysqli_query( $mysqli , "SELECT ID FROM game WHERE status='initialized'" );
	$line = mysqli_fetch_row($result);
	$info = array();

	if(!isset($line[0]))
	{
		$sql="INSERT INTO game( playerx , status) VALUES ( '$name ', 'initialized')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		$info["gameID"]=$id;
		$info["XO"]="X";
        $_SESSION["XO"]="X";
		echo json_encode($info);
	}
	else
	{
		$sql="UPDATE game SET status='started', playery='$name' where id=$line[0]";
		$id=$line[0];
		$mysqli->query($sql);
		$info["gameID"]=$id;
		$info["XO"]="O";
        $_SESSION["XO"]="O";
		echo json_encode($info);

	
	}
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