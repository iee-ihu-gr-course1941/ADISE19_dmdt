<?php

function show_board() {
	
	global $mysqli;
	
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();

	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);

}
//gia na doume an yparxei hdh piece sto kouti
function read_piece($x,$y) {
	global $mysqli;
	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();
	if($row=$res->fetch_assoc()) {
		return($row['piece']);
	}
	return(null);
}



function reset_board() {
	global $mysqli;
	
	$sql = 'call clean_board()';
	$mysqli->query($sql);
}

function rematch() {
	global $mysqli;
	
	$sql = 'call rematch()';
	$mysqli->query($sql);
}



function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}




function show_piece($x,$y) {
	global $mysqli;
	
	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}



function add_piece($x,$y,$token) {
	
	if($token==null || $token=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
	
	$piece = current_piece($token);
	if($piece==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
    //an to game exei arxisei
	$status = read_status();
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
    //an einai to turn mas
	if($status['p_turn']!=$piece) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
	}
    //an to kouti einai keno
    $filled = read_piece($x,$y);
    if($filled != null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Already Filled."]);
		exit;
	}
	
	
	
			do_move($x,$y,$token);
			exit;
		
	header("HTTP/1.1 400 Bad Request");
	print json_encode(['errormesg'=>"This move is illegal."]);
	exit;
}



function do_move($x,$y,$token) {
	global $mysqli;
    $piece=current_piece($token);
	$sql = 'call `add_piece`(?,?,?)';
	$st = $mysqli->prepare($sql);
	$st->bind_param('iis',$x,$y,$piece );
	$st->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}


















?>