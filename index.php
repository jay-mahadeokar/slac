<?php
	include_once 'config.php';
	session_start();
	if(isset($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1){
		include 'client.php';
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>SLAC Messenger - Powered by Yahoo!</title>
		<link rel="stylesheet" type="text/css" href="css/style_index.css" />
		<script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript" charset="utf-8"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/libs/geo.js" type="text/javascript" charset="utf-8"></script>
		<script>
			$(document).ready(function(){
				if(geo_position_js.init()){
					geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true,options:5000});
				}
				else{
					alert("GEO Functionality not available. No Pizzas for you :P");
				}
	
				function success_callback(p)
				{
					$('<input>').attr({
						type: 'hidden',
						id: 'lat',
						name: 'lat',
						value: p.coords.latitude.toFixed(4)
					}).appendTo('form');
					$('<input>').attr({
						type: 'hidden',
						id: 'lon',
						name: 'lon',
						value: p.coords.longitude.toFixed(4)
					}).appendTo('form');
				}
				
				function error_callback(p)
				{
					alert('error='+p.message);
				}		
			});
		</script>
	</head>
	<body>
		<form id="login-form" action="do.php?action=login" method="post">
			<fieldset>
				<legend>Log in</legend>
				<label for="login">Username:</label><input type="text" id="login" name="user" /><div class="clear"></div>
				<label for="password">Password:</label><input type="password" id="password" name="password" /><div class="clear"></div>
				<br />
				<input type="submit" style="margin: -20px 0 0 287px;" class="button" name="Login" value="Turn me ON!" />
			</fieldset>
		</form>
	</body>
	
</html>
