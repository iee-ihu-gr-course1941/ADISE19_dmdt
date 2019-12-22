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

function startGame(nametxt)//ksekinaei to game 
{
    $.post("lib/startGame.php",
        {
        name: nametxt
        },
        function(data, status){
            obj = JSON.parse(data);
        });
				
}
			
function printTable() // emfanizei ton pinaka me oti move exei ginei kai ta apothikevei sto 2d pinaka 3x3 Ttable
{
    var temp;
    $.post("lib/startGame.php",
    {
        gameId: id
    },
    function(result)
    {
        temp = JSON.parse(result);	
        var t='<table id="table">';
                        
        for ( i=0; ttt.length; i++)
        {
            t += '<tr>';
            var x=temp[i].posX;
            var y=temp[i].posY;
            Ttable[x][y]=temp[i].piece;
						
        }
					       
        for(var i=1;i<4;i++) 
        {
            t += '<tr>';
            for(var j=1;j<4;j++) 
            {
                t += '<td class="chess_square" id="square_'+i+'_'+j+'">' + Ttable[i][j]+'</td>';
            }
                t+='</tr>';
                $('#board').html(t);
        }
    });
}
				
			
function playPos(x,y) // Paizei to position pou tou dothike kai enimerwnei ton pinaka 
{	
    $.post("lib/playPos.php",
    {
        posX: x,
        posY: y,
        gameId: id;
    },
    function(data, status)
    {});	
				
    printTable();
}
			
function getTurn() // pairnei to turn to game to game id dinete me session
{	
    var temp;
    $.ajax({url: "lib/getTurn.php", success: function(result)
        {
            temp = JSON.parse(result);	
            turn=temp.turn;

        }
			
    });
}
			
function updateGame() // h function poy xrisimopioume me interval kai enimeronei to game
{
    printTable();
    getTurn();
}
			
function dropGame() // kanei unset to gameID session kai thetei to game se ended
{
    $.ajax({url: "lib/dropGame.php"});
}



