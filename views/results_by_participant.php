<?php
include_once '../blls/participant_manager.php';
include_once '../blls/trial_manager.php';

if (! isset ( $_GET ['participant_id'] )) {
	print "Invalid request";
	return;
}

$participantMgr = new ParticipantManager ();
$trialMgr = new TrialManager ();

?>

<!DOCTYPE html>
<html>


<head>


</head>

<body>

</body>
<?php
include_once 'admin_menu.php';

$participant_id = $_GET ['participant_id'];

$row = $participantMgr->get ( $participant_id );

print "<br><h3>Name: " . $row ["participant_name"] . "</h3>";

$result = $participantMgr->getResultTable ( $participant_id );

?>


<table>
	<tr>
		<th>Precue</th>

		<th>Colour</th>

		<th>Shape</th>
		<th>Mean</th>

		<th>SD</th>

	</tr>
<?php

for($i = 0; $i < sizeof ( $result ); $i ++) { // result = 16 = no. of rows (precue and distractor ratio combinations)
	
	$row = $result [$i];
	$precue_set = $row ['precue_set'];
	$colour_distractor_ratio = $row ['colour_distractor_ratio'];
	$shape_distractor_ratio = $row ['shape_distractor_ratio'];
	print "<tr>";
	
	print "<td>";
	print "<a href='targets_by_participant.php?precue_set=$precue_set&colour_distractor_ratio=$colour_distractor_ratio&shape_distractor_ratio=$shape_distractor_ratio&participant_id=$participant_id'>" . $row ['precue_name'] . "</td>";
	print "</td>";
	
	print "<td>";
	print $colour_distractor_ratio;
	print "</td>";
	
	print "<td>";
	print $shape_distractor_ratio;
	print "</td>";
	
	print "<td>";
	print $row ['mean'];
	print "</td>";
	
	print "<td>";
	print $row ['sd'];
	print "</td>";
	
	print "</tr>";
}

?>


</table>