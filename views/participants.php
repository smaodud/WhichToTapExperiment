<?php
include_once '../blls/participant_manager.php';
include_once '../blls/trial_manager.php';

$participantMgr = new ParticipantManager ();
$trialMgr = new TrialManager ();
?>


<table>
	<tr>
		<th>Sl</th>
			<th>Action</th>
		<th>name</th>
		<th>full icon</th>
		<th>colour and name</th>
		<th>shape and name</th>
		<th>name only</th>
	</tr>
<?php

$result = $participantMgr->getAll ();

$serial_no = 0;
while ( $row = mysql_fetch_assoc ( $result ) ) {
	$serial_no ++;
	$participant_id = $row ['participant_id'];
	
	$participant_id = $row ['participant_id'];
	
	print "<tr>";
	print "<td> $serial_no</td>";

	print "<td>";
	print "<a href='results_by_participant.php?participant_id=$participant_id'> Result </a>";
	print "| <a href='targets_by_participant.php?participant_id=$participant_id'> Targets </a>";

	
	
	print "</td>";
	
	
	print "<td>" . $row ['participant_name'] . "</td>";
	print "<td style='text-align:center'>";
	
	print "(";
	print " 22-> " . $trialMgr->getRatioCount ( 1, 2, 2, $participant_id );
	print ", 26-> " . $trialMgr->getRatioCount ( 1, 2, 6, $participant_id );
	print ", 62-> " . $trialMgr->getRatioCount ( 1, 6, 2, $participant_id );
	print ", 66-> " . $trialMgr->getRatioCount ( 1, 6, 6, $participant_id );
	print ")<br>=";
	print $trialMgr->getFullIconCount ( $participant_id );
	
	print "</td>";
	print "<td style='text-align:center'>";
	
	print "(";
	print " 22-> " . $trialMgr->getRatioCount ( 2, 2, 2, $participant_id );
	print ", 26-> " . $trialMgr->getRatioCount ( 2, 2, 6, $participant_id );
	print ", 62-> " . $trialMgr->getRatioCount ( 2, 6, 2, $participant_id );
	print ", 66-> " . $trialMgr->getRatioCount ( 2, 6, 6, $participant_id );
	print ")<br>=";
	print $trialMgr->getColourNNameCount ( $participant_id );
	print "</td>";
	
	print "<td style='text-align:center'>";
	
	print "(";
	print " 22-> " . $trialMgr->getRatioCount ( 3, 2, 2, $participant_id );
	print ", 26-> " . $trialMgr->getRatioCount ( 3, 2, 6, $participant_id );
	print ", 62-> " . $trialMgr->getRatioCount ( 3, 6, 2, $participant_id );
	print ", 66-> " . $trialMgr->getRatioCount ( 3, 6, 6, $participant_id );
	print ")<br>=";
	print $trialMgr->getShapeNNameCount ( $participant_id );
	print "</td>";
	
	print "<td style='text-align:center'>";
	
	print "(";
	print " 22-> " . $trialMgr->getRatioCount ( 4, 2, 2, $participant_id );
	print ", 26-> " . $trialMgr->getRatioCount ( 4, 2, 6, $participant_id );
	print ", 62-> " . $trialMgr->getRatioCount ( 4, 6, 2, $participant_id );
	print ", 66-> " . $trialMgr->getRatioCount ( 4, 6, 6, $participant_id );
	print ")<br>=";
	print $trialMgr->getNameOnlyCount ( $participant_id );
	print "</td>";
}

?>



</table>