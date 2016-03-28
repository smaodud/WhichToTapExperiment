<?php
session_start();

// when user inputs name, POST method passes data to following page
// then initial var = true
if (isset ( $_POST ['participant_name'] )) {
	$participant_name = $_POST ['participant_name']; // name to keep track of participant
	                                                 // post data from client side to server
	                                                 // user data saved to db
	                                                 // id generated
	                                                 
	$age = $_POST ['age'];
	
	$gender = $_POST ['gender'];
	                                                 
	$_SESSION["practice"] = true;
}else {
	die("Invalid access");
}

?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

	<div align="center">
		<br>
		<h3>Please read the instructions carefully before starting the
			experiment:</h3>
		<br>

		<p style="font-size: 14pt;">
			You will be asked to search for a target icon amongst 20 icons.<br>
			<br> Before each search trial, you will be given a hint. <br>
			<br> The hint may be the full target icon or parts of the target icon.<br>
			<br> For example:<br>
			<br> To find <img src="../images/orange_star.png" height="58" width="60">,
			you may be shown 1 of the following 4 as a hint: <br> <br>
		
		
		<table>
			<tr>
				<td style="padding-left: 5px; padding-right: 5px; text-align: center"><img
					src="../images/orange_star.png" height="58" width="60"><br> flamingo</td>
				<td style="padding-left: 5px; padding-right: 5px; text-align: center"><img
					src="../images/colour_orange.png" height="58" width="60"><br> flamingo</td>
				<td style="padding-left: 5px; padding-right: 5px; text-align: center"><img
					src="../images/shape_star.png" height="58" width="60"><br> flamingo</td>
				<td style="padding-left: 5px; padding-right: 5px; text-align: center"><img
					src="../images/blank.png" height="58" width="60"><br> flamingo</td>
			</tr>
		</table>

		<br>
		<p style="font-size: 14pt;">
			Please take the practice trials before starting the experiment. Click
			button below:<br>
			<br>
		<form action="target_practice.php" method="post">
		
		<input type="hidden" name = "participant_name" value ="<?php echo $participant_name?>" >
		
		
		<input type="hidden" name = "age" value ="<?php echo $age?>" >
		
		
		
		<input type="hidden" name = "gender" value ="<?php echo $gender?>" >
		
		
				<input type="submit" value ="Practice" class="btn btn-default btn-lg" style="width: 120px; height: 60px">
				<br>
				<br>
		
		</form>

	
	</div>
</body>
</html>
