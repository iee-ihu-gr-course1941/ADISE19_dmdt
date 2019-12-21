$(function () {
	draw_empty_board();
});


function draw_empty_board() {
	var t='<table id="table">';
	for(var i=1;i<4;i++) {
		t += '<tr>';
		for(var j=1;j<4;j++) {
			t += '<td class="chess_square" id="square_'+i+'_'+j+'">' + i +','+j+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#board').html(t);
}



