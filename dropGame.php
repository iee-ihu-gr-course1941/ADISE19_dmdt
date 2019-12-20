<?php 
session_start();
include "dbconnect.php";

$sql="UPDATE game SET status='ended' where id=".$_SESSION["gameID"];
if ($mysqli->query($sql) === TRUE) 
{
	echo "Updated";
}
else 
{
	echo "Error: " . $sql . "<br>" . $mysqli->error;
}
unset($_SESSION["gameID"]);


?>