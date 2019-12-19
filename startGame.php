<?php 

	session_start();
	include "dbconnect.php";

	$_SESSION["name"]=$_POST["name"];
	$name=$_SESSION["name"];
	$result = mysqli_query( $mysqli , "SELECT ID FROM game WHERE status='initialized'" );
	$line = mysqli_fetch_row($result);
	$info = array();
	if(!isset($line[0])){
		$sql="INSERT INTO game( playerx , status) VALUES ( '$name ', 'initialized')";
		if ($mysqli->query($sql) === TRUE) 
		{
			$id = $mysqli->insert_id;
			$_SESSION["gameID"]=$id;
			$_SESSION["XO"]="X";
			$info["gameID"]=$id;
			$info["XO"]="X";
			echo json_encode($info);
		}
		else 
		{
			echo "Error: " . $sql . "<br>" . $mysqli->error;
		}
	}
	else{
		$sql="UPDATE game SET status='started', playery='$name' where id=$line[0]";
		$id=$line[0];
		if ($mysqli->query($sql) === TRUE) 
		{
			$_SESSION["gameID"]=$id;
			$_SESSION["XO"]="O";
			$info["gameID"]=$id;
			$info["XO"]="O";
			echo json_encode($info);
		}
		else 
		{
			echo "Error: " . $sql . "<br>" . $mysqli->error;
		}
		
	}
	
		

?>