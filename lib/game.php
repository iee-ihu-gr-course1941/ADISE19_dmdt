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

function GameWon($piece)
{
}

function start_game()
{
	global $mysqli;
	
	$playerid=$_SESSION["Player"];
	
		$result = mysqli_query( $mysqli , "SELECT ID FROM game WHERE status='initialized'" );
		$line = mysqli_fetch_row($result);
		$info = array();

		if(!isset($line[0]))
		{
			$sql="INSERT INTO game( playerx , status) VALUES ( '$playerid ', 'initialized')";
			$mysqli->query($sql);
			$id = $mysqli->insert_id;
			$info["gameID"]=$id;
			$info["XO"]="X";
		}
		else
		{
			$sql="UPDATE game SET status='started', playery='$playerid' where id=$line[0]";
			$id=$line[0];
			$mysqli->query($sql);
			$info["gameID"]=$id;
			$info["XO"]="O";
		}
			$_SESSION["gameID"]=$info["gameID"];
			$_SESSION["XO"]=$info["XO"];
			echo json_encode($info);
	}
?>
