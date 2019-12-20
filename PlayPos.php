<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
<?php 
	session_start();
	include "dbconnect.php";
	
	$X=$_POST["posX"];
	$Y=$_POST["posY"];
	$sql="INSERT INTO moves(game,player,posX,posY) VALUES (".$_SESSION['gameID'].",".$_SESSION['name'].",$X,$Y)";
	
	if ($mysqli->query($sql) === TRUE) 
	{
		echo "done";
	}
	else 
	{
		echo "Error: " . $sql . "<br>" . $mysqli->error;
	}
	
	?> 
<body>
</body>
</html>