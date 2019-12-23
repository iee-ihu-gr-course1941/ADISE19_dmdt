<?php
function findPlayerPiece($token)
{
	$sql = "select count(*) as count from game where (playerx='".$input['token']."' and status='started'";
	$st = $mysqli->prepare($sql);
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	if (r[0]['count']>0)
		return "X";
	$sql = "select count(*) as count from game where (playery='".$input['token']."' and status='started'";
	$st = $mysqli->prepare($sql);
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	if (r[0]['count']>0)
		return "Y";
}
function wonGame($input)
{
	$sql="update game set status='ended',result='".findPlayerPiece($input['token'])."'";
	$st = $mysqli->prepare($sql);
	$r = $st->execute();
}
function gameupdate($input)
{
	global $mysqli;
	
	
	$sql = "select count(*) as count,id from game where (playerx='".$input['token']."' or playery='".$input['token']."') and status='started'";
	$st = $mysqli->prepare($sql);
	$r = $st->execute();
	
	check_abort(r[0]['id']);
	
	$sql = "select status,turn from game where playerx='".$input['token']."' or playery='".$input['token']."'";
	$st = $mysqli->prepare($sql);
	$res = $st->execute();
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function show_status($input) {
	
	global $mysqli;
	$sql = "select status from game where playerx='".$input['token']."' or playery='".$input['token']."'";
	
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}

function check_abort($id) {
	global $mysqli;
	
	$sql = "update game set status='aborded', result=if(p_turn='W','B','W'),turn=null where p_turn is not null and last_change<(now()-INTERVAL 5 MINUTE) and status='started' and ID=$id";
	$st = $mysqli->prepare($sql);
	$r = $st->execute();
}


function start_game()
{
	global $mysqli;
	
	
		
		$sql = "select count(*) as count FROM game WHERE status='initialized'";
		$st = $mysqli->prepare($sql);
		$res = $st->get_result();
		$r = $res->fetch_all(MYSQLI_ASSOC);
		
		if($r[0]['count']==0)
		{
			$sql="INSERT INTO game( playerx , status) VALUES ( '".$input['token']."', 'initialized')";
			$mysqli->query($sql);
			$id=$mysqli->insert_id;
			$piece="X";
		}
		else
		{
			$sql = "select ID as activegameID FROM game WHERE status='initialized'";
			$st = $mysqli->prepare($sql);
			$res = $st->get_result();
			$r = $res->fetch_all(MYSQLI_ASSOC);
			
			$id=$r[0]['activegameID'];
			
			$sql="UPDATE game SET status='started', playery='".$input['token']."' where id=$id";
			$st = $mysqli->prepare($sql);
			$r = $st->execute();
			$piece="O";
		}
		
		$info["gameid"]=$id;
		$info["piece"]=$piece;
		header('Content-type: application/json');
		print json_encode($info[], JSON_PRETTY_PRINT);
			
	}
?>
