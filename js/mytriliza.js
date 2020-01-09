var me={token:null,piece:null};
var game_status={};
var board={};
var last_update=new Date().getTime();
var timer=null;
var chatinterval=null;

$(function () {
	draw_empty_board();
    fill_board();
    
    $('#login').click( login_to_game);
    $('#reset').click( reset_board);
	$('#rematch').click( rematch);
    $('#do_move').click( do_move);
	$('#move_div').hide();
	$('#chat').hide();
	game_status_update();
	$("#msgbtn").click( sendChat);
});

function draw_empty_board() {
	var t='<table id="table">';
	for(var i=1;i<4;i++) {
		t += '<tr>';
		for(var j=1;j<4;j++) {
			t += '<td class="square" id="square_'+i+'_'+j+'">' + i +','+j+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#board').html(t);
    $('.square').click(click_on_piece);
}

function fill_board() {
	$.ajax({url: "triliza.php/board/", 
		headers: {"X-Token": me.token},
		success: fill_board_by_data });
}

function reset_board() {
	$.ajax({url: "triliza.php/board/", method: 'POST',  success: fill_board_by_data});
	$('#move_div').hide();
	$('#game_initializer').show(2000);
}

function rematch() {
	$.ajax({url: "triliza.php/board/rematch", method: 'POST',  success: fill_board_by_data});	
}

function fill_board_by_data(data) {
	board=data;
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.x +'_' + o.y;
		var c = (o.piece!=null)?o.piece:'';
		var im = (o.piece!=null)?'<img class="piece '+c+'" src="images/'+c+'.png">':'';
		$(id).addClass(o.b_color+'_square').html(im);
	}
}

function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	var piece = $('#piece').val();
    fill_board();
    
	$.ajax({url: "triliza.php/players/"+piece, 
			method: 'PUT',
			dataType: "json",
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val(), piece: piece}),
			success: login_result,
			error: login_error});
	startchat();
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
	last_update=new Date().getTime();
	var game_stat_old = game_status;
	game_status=data[0];
	update_info();
	clearTimeout(timer);
	if(game_status.p_turn==me.piece &&  me.piece!=null) {
		x=0;
		// do play
		if(game_stat_old.p_turn!=game_status.p_turn) {
			fill_board();
		}
		$('#move_div').show(1000);
		timer=setTimeout(function() { game_status_update();}, 15000);
	} else {
		// must wait for something
		$('#move_div').hide(1000);
		timer=setTimeout(function() { game_status_update();}, 4000);
	}
 	
}

function update_info(){
	$('#game_info').html("I am Player: "+me.piece+", my name is "+me.username +'<br>Token='+me.token+'<br>Game state: '+game_status.status+', '+ game_status.p_turn+' must play now , the result is ' + game_status.result);
	
}

function do_move() {
	var s = $('#the_move').val();
	
	var a = s.trim().split(/[ ]+/);
	if(a.length!=2) {
		alert('Must give 2 numbers');
		return;
	}
    if(a[0]>3 || a[0]<1 ){
        alert("out of bounds");
        return;
    }
    if(a[1]>3 || a[1]<1 ){
        alert("out of bounds")
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
	game_status_update();
	fill_board_by_data(data);
}

function getChat(){
		$.ajax({url: "triliza.php/chat", 
			method: 'GET',
			dataType: "json",
			contentType: 'application/json',
			headers: {"X-Token": me.token},
			success: loadchat,
			error: chat_error});
}

function sendChat()
{
	$.ajax({url: "triliza.php/chat", 
			method: 'PUT',
			dataType: "json",
			contentType: 'application/json',
			data: JSON.stringify( {username: me.piece, msg:$("#msg").val()}),
			headers: {"X-Token": me.token},
			success: loadchat,
			error: chat_error});
}

function loadchat(data){
	var inchat="";
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var msg=o.username+": "+o.msg+"<br>";
		inchat+=msg;
	}
	$("#outputchat").html(inchat);
}
function chat_error(data) {
	var x = data.responseJSON;
	$("#outputchat").html(x.errormesg);
}
function startchat(){
	$("#chat").show( 2000 );
	chatinterval = setInterval(function() { getChat();}, 4000);
}




function click_on_piece(e) {
	var o=e.target;
	if(o.tagName!='TD') {o=o.parentNode;}
	if(o.tagName!='TD') {return;}
	
	var id=o.id;
	var a=id.split(/_/);
	$('#the_move').val(a[1]+' ' +a[2]);
	do_move();
}