<?php
include_once '../blls/attempts_manager.php';
include_once '../blls/participant_manager.php';
include_once '../blls/trial_manager.php';

if (! isset ( $_GET ['participant_id'] )) {
	print "invalid request";
	return;	
}

$img_size = 40;

$participantMgr = new ParticipantManager ();
$trialMgr = new TrialManager ();
$attemptMgr = new AttemptManager ();

?>
<!DOCTYPE html>
<html>


<head>


</head>

<body>


<?php

include_once 'admin_menu.php';


$participant_id = $_GET ['participant_id'];

$row = $participantMgr->get ( $participant_id );

print "<br><h3>Name: " . $row ["participant_name"] . "</h3>";

if (isset ( $_GET ['precue_set'] )) {
	$precue_set = $_GET ['precue_set'];
	$colour_distractor_ratio = $_GET ['colour_distractor_ratio'];
	$shape_distractor_ratio = $_GET ['shape_distractor_ratio'];
	
	//print "$precue_set<br>$colour_distractor_ratio<br>$shape_distractor_ratio";
	
	$result = $trialMgr->getTrialsByParticipantAndRatio ( $precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id );
} else {
	$result = $trialMgr->getTrialsByParticipant ( $participant_id );
}

?>

<table  border="1">
	<tr>
		<th>Trial Id</th>
		<th>Icon Id</th>
		<th>Precue</th>
		<th>Name</th>
		<th>Attempts</th>
		<th>Time</th>
		<th>Colour Dist.</th>
		<th>Shape Dist.</th>
	</tr>
<?php

while ( $row = mysql_fetch_assoc ( $result ) ) {
	print "<tr>";
	
	$trial_id = $row ["trial_id"];
	
	print "<td>$trial_id</td>";
	
	print "<td>" . $row ["icon_id"] . "</td>";
	
	$precue_set = $row ["precue_set"];
	
	switch ($precue_set) {
		
		case 1 :
			$icon = $row ['full_icon'];
			
			$iconData = base64_encode ( $icon );
			break;
		
		case 2 :
			$icon = $row ['colour'];
			$iconData = base64_encode ( $icon );
			break;
		
		case 3 :
			$icon = $row ['shape'];
			$iconData = base64_encode ( $icon );
			break;
		
		case 4 :
			$icon = null;
			
			$iconData = null;
			break;
	}
	
	if ($icon == "") {
		print "<td><img src=\"../images/blank.png\" height=\"65\" width=\"65\"></td>";
	} else {
		print "<td><img src=\"data:image/jpeg;base64,$iconData\" width=\"$img_size\" height=\"$img_size\"  /></td>"; // img processing
	}
	
	print "<td>" . $row ["icon_name"] . "</td>";
	print "<td  style='text-align:center'>" . $row ["total_attempts"] . "</td>";
	print "<td  style='text-align:right'>" . $row ["total_time"] . "</td>";
	print "<td style='text-align:center'>" . $row ["colour_distractor_ratio"] . "</td>";
	print "<td  style='text-align:center'>" . $row ["shape_distractor_ratio"] . "</td>";
	print "</tr>";
	
	if ($row ["total_attempts"] > 1) {
		print "<tr>";
		print "<td>&nbsp;</td>";
		print "<td colspan = '7'>";
		print "<table width='100%' style='background:#ffccdd'>";
		print "<tr>";
		print "<th>Attempt Id</th>";
		print "<th>Time</th>";
		print "<th>Is correct</th>";
		print "</tr>";
		
		$attemptResult = $attemptMgr->getAttemptsByTrial ( $trial_id );
		while ( $attemptRow = mysql_fetch_assoc ( $attemptResult ) ) {
			print "<tr>";
			
			print "<td>" . $attemptRow ['attempt_id'] . "</td>";
			print "<td>" . $attemptRow ['attempt_time'] . "</td>";
			print "<td>" . $attemptRow ['is_correct'] . "</td>";
			print "</tr>";
		}
		
		print "</table>";
		print "</td>";
		
		print "</tr>";
	}
}

?>

</table>

</body>