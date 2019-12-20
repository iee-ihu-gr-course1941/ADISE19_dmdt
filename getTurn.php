<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

	<?php 
	session_start();
	include "dbconnect.php";
	
	$result = mysqli_query( $mysqli , "SELECT turn FROM game WHERE id=".$_SESSION['gameID'] );
	$line = mysqli_fetch_row($result);
	echo json_encode($line[0]);
	
	?> 
	
<body>
</body>
</html>