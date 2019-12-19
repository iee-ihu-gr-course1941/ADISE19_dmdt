<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php 
	session_start();
	include "dbconnect.php";
	
	$result = mysqli_query( $mysqli , "SELECT posX,posY FROM moves game=$_SESSION[gameID]");
	
	$jsonArr = array();
	while($row = mysqli_fetch_assoc($result))
	{
		$jsonArr[]= $row;
	}
		echo json_encode($jsonArr);

	?> 
</body>
</html>