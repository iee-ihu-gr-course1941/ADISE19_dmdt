var me={token:null,piece:null};
var game_status={};


$(function () {
	draw_empty_board();
    $('#login').click( login_to_game);
    $('#reset').click( reset_board);
    $('#do_move').click( do_move);
		game_status_update();

});



function draw_empty_board() {
	var t='<table id="table">';
	for(var i=1;i<4;i++) {
		t += '<tr>';
		for(var j=1;j<4;j++) {
			t += '<td class="square" id="square_'+j+'_'+i+'">' + j +','+i+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#board').html(t);
}


function reset_board() {
	$.ajax({url: "triliza.php/board/", method: 'POST'});
	$('#move_div').hide();
	$('#game_initializer').show(2000);
}

function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	var piece = $('#piece').val();
    
	$.ajax({url: "triliza.php/players/"+piece, 
			method: 'PUT',
			dataType: "json",
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val(), piece: piece}),
			success: login_result,
			error: login_error});
}


function login_result(data) {
	me = data[0];
	$('#game_initializer').hide();
	update_info();
	game_status_update();
}

function login_error(data,y,z,c) {
	var x = data.responseJSON;
	alert(x.errormesg);
}



function game_status_update() {
	$.ajax({url: "triliza.php/status/", success: update_status });
}


function update_status(data) {
	game_status=data[0];
	update_info();
	if(game_status.p_turn==me.piece &&  me.piece!=null) {
		x=0;
		// do play
		$('#move_div').show(1000);
		setTimeout(function() { game_status_update();}, 15000);
	} else {
		// must wait for something
		$('#move_div').hide(1000);
		setTimeout(function() { game_status_update();}, 4000);
	}
 	
}

function update_info(){
	$('#game_info').html("I am Player: "+me.piece+", my name is "+me.username +'<br>Token='+me.token+'<br>Game state: '+game_status.status+', '+ game_status.p_turn+' must play now.');
	
}

function do_move() {
	var s = $('#the_move').val();
	
	var a = s.trim().split(/[ ]+/);
	if(a.length!=2) {
		alert('Must give 2 numbers');
		return;
	}
	$.ajax({url: "triliza.php/board/piece/"+a[0]+'/'+a[1], 
			method: 'PUT',
			dataType: "json",
			contentType: 'application/json',
			data: JSON.stringify( {x: a[0], y: a[1],b: me.piece}),
			headers: {"X-Token": me.token},
			success: move_result,
			error: login_error});
	
}

function move_result(data){
	
	$('#move_div').hide(1000);
}