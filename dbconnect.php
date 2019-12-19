<?php
$user='root';
$host='127.0.0.1';
$db = 'myttt';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . 
    $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
	$_SESSION['mysqli']=$mysqli;
?>