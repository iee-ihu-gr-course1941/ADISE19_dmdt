<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
	<?php session_start();
	unset($_SESSION["gameID"]);
	echo $_SESSION["name"];
	?>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script>
	
		$(document).ready(function()
		{	
			var id,xo;
			var obj;
			$("#btn").click(function()
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
		});
				
		
	
	</script>
</head>

<body>
	
	
	<table >
		<tr>
    <th style="border-right:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
    <th style="border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
    <th style="border-left:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
  </tr>
		<tr>
    <th style="border-right:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
    <th style="border-right:1px solid #ddd;border-left:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
    <th style="border-left:1px solid #ddd;border-bottom: 1px solid #ddd">-</th>
  </tr>
		<tr>
    <th style="border-right:1px solid #ddd">-</th>
    <th style="border-right:1px solid #ddd;border-left:1px solid #ddd">-</th>
    <th style="border-left:1px solid #ddd">-</th>
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