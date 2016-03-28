<?php
include_once '../blls/participant_manager.php';


$participantMgr = new ParticipantManager ();

include_once 'admin_menu.php';


?>



<table>
	<tr>

		<th>ID</th>
		<th>Age</th>
		<th>Gender</th>
		<th>FI(2,2)</th>
		<th>FI(2,6)</th>
		<th>FI(6,2)</th>
		<th>FI(6,6)</th>
		<th>CN(2,2)</th>
		<th>CN(2,6)</th>
		<th>CN(6,2)</th>
		<th>CN(6,6)</th>
		<th>SN(2,2)</th>
		<th>SN(2,6)</th>
		<th>SN(6,2)</th>
		<th>SN(6,6)</th>
		
		<th>NO (2,2)</th>
		<th>NO (2,6)</th>
		<th>NO (6,2)</th>
		<th>NO (6,6)</th>
	</tr>
<?php

$result = $participantMgr->getAll ();

$serial_no = 0;
while ( $row = mysql_fetch_assoc ( $result ) ) {
	$serial_no ++;
	$participant_id = $row ['participant_id'];
	
	
	print "<tr>";
	
//	print "<td>" . $row ['participant_id'] . "</td>";
	print "<td>" . $row ['participant_name'] . "</td>";
	
	print "<td>" . $row ['age'] . "</td>";
	
	print "<td>" . $row ['gender'] . "</td>";
	

	$analysisResult = $participantMgr->getResultTable ( $participant_id );
	
	//print "size". sizeof ( $analysisResult );
	
	for($i = 0; $i < sizeof ( $analysisResult ); $i ++) { // result = 16 = no. of rows (precue and distractor ratio combinations)
	
		
	
		$analysisRow = $analysisResult [$i];
		$precue_set = $analysisRow ['precue_set'];
		$colour_distractor_ratio = $analysisRow ['colour_distractor_ratio'];
		$shape_distractor_ratio = $analysisRow ['shape_distractor_ratio'];
	
	
// 		print "<td>";
// 		print  $analysisRow ['precue_name'];
// 		print "</td>";
	
// 		print "<td>";
// 		print $colour_distractor_ratio;
// 		print "</td>";
	
// 		print "<td>";
// 		print $shape_distractor_ratio;
// 		print "</td>";
	
		print "<td>";
		print $analysisRow ['mean'];
		print "</td>";
	
		print "<td>";
		print $analysisRow ['sd'];
		print "</td>";
	
	
	}
	
	print "</tr>";
}

?>



</table>