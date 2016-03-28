<?php
session_start();
include_once '../blls/trial_manager.php';

// validation to avoid removing actual trial data, incase user comes to this page accidentally
// makes sure only practice trials are removed
// if participant_id is set and practice trails were done, remove practice trial



if (isset ( $_GET ['participant_id'] ) && isset ( $_SESSION ["practice"] )) {
	$participant_id = $_GET ['participant_id'];
	
	$practice = $_SESSION ["practice"];
	
	if ($practice == true) {
		$trialManager = new TrialManager ();
		$result = $trialManager->deleteTrials ( $participant_id );
		if ($result) {
			$_SESSION ['practice'] = false;
		}
	}
} else {
	die ( "Invalid access" );
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/style.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<div align="center">

		<h4>You have completed the practice trails.</h4>
		<h3>Now please click the button below to start the experiment.</h3>



		<form action="target_experiment.php" method="post">
		<?php //echo $participant_id?>
		<input type="hidden" name="participant_id"
				value="<?php echo $participant_id?>"> <input type="submit"
				value="Start" class="btn btn-default btn-lg"
				style="width: 120px; height: 60px">


		</form>
	</div>


	<!--  	<button onclick="location.href = 'target2.php'" type="button"
									style="position: absolute; left: 48.5%; right: 50%; bottom: 35%;"
									class="btn btn-default btn-lg">Start</button>
									
									-->


</body>

</html>