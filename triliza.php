<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<?php 
	session_start();
	//unset($_SESSION["gameID"]);
	//echo $_SESSION["name"];
	?>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script>
	
		$(document).ready(function()
		{	
			var Ttable=new Array(2);
			var id,xo;
			var obj;
			var turn;
			
			$("#btn").click(function()  //Otan patiete to koumpi kai ksekinaei to game ... prepei na valoume interval gia updateGame()
			{
				var nametxt=$("#namebox").val();
				
				$.post("startGame.php",
				{
					name: nametxt
				},
				  function(data, status){
					$("#form").hide();
					obj = JSON.parse(data);
					$("#gameID").text(id+" "+xo);
				});
				
			});
			
			function printTable() // emfanizei ton pinaka me oti move exei ginei kai ta apothikevei sto 2d pinaka 3x3 Ttable
			{
				var temp;
		
  				$.ajax({url: "printTable.php", success: function(result)
					{
						temp = JSON.parse(result);	
				
						for ( i=0; ttt.length; i++)
						{
								var x=ttt[i].posX;
								var y=ttt[i].posY;
								Ttable[x][y]=xo;
						
						}
					
						$("game").html("<th style='border-right:1px solid #ddd;border-bottom: 1px solid #ddd' >"+Ttable[0][0]"+</th><th style='border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd' >"+Ttable[0][1]+"</th><th style='border-left:1px solid #ddd;border-bottom: 1px solid #ddd' >"+Ttable[0][2]+"</th></tr><tr><th style='border-right:1px solid #ddd;border-bottom: 1px solid #ddd' >"+Ttable[1][0]+"</th><th style='border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd'  >"+Ttable[1][1]+"</th><th style='border-left:1px solid #ddd;border-bottom: 1px solid #ddd' >"+Ttable[1][2]+"</th></tr><tr><th style='border-right:1px solid #ddd' >"+Ttable[2][0]+"</th><th style='border-right:1px solid #ddd;border-left:1px solid #ddd' "+Ttable[2][1]+">-</th><th style='border-left:1px solid #ddd' "+Ttable[2][2]+" >-</th></tr>"); 
					
					}
				});
			}
			
			function playPos(x,y) // Paizei to position pou tou dothike
			{	
				$.post("playPos.php",
				{
					posX: x,
					posY: y
				},
				function(data, status){
						
				});	
				
				printTable();
				}
			
			function getTurn() // pairnei to turn to game to game id dinete me session
			{	
				var temp;
				$.ajax({url: "getTurn.php", success: function(result)
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
		
	
	</script>
</head>

<body>
	
	
	<table id="game">
		<tr>
			<th style="border-right:1px solid #ddd;border-bottom: 1px solid #ddd" id="[0][0]">-</th>
			<th style="border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd" id="[0][1]">-</th>
			<th style="border-left:1px solid #ddd;border-bottom: 1px solid #ddd" id="[0][2]">-</th>
  		</tr>
		<tr>
			<th style="border-right:1px solid #ddd;border-bottom: 1px solid #ddd" id="[1][0]">-</th>
			<th style="border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd" id="[1][1]">-</th>
			<th style="border-left:1px solid #ddd;border-bottom: 1px solid #ddd" id="[1][2]">-</th>
  		</tr>
		<tr>
			<th style="border-right:1px solid #ddd" id="[2][0]">-</th>
			<th style="border-right:1px solid #ddd;border-left:1px solid #ddd" id="[2][1]">-</th>
			<th style="border-left:1px solid #ddd" id="[2][2]">-</th>
  		</tr>
	</table>
	
	
	<?php 
	if (!isset($_SESSION["gameID"]))
	{
		echo "<form id='form'><input type='text' id='namebox' name='name'><input type='button' id='btn' value='PLAY!''></form>";
	}
	?>
	
	<p id="gameID"></p>
</body>
</html>

<style>
table {
	
    width:30%;
	height:400px;
	
}

</style>