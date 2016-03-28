<?php
include_once "participant_manager.php";

$participantMgr = new ParticipantManager();


$result  = $participantMgr->getResultTable(34);

?>


<table>
  <tr>
    <th>Precure</th>
    <th>Shape</th>
 
  <th>Color</th>

 
  <th>Mean</th>
   
  <th>SD</th>
  
  </tr>
<?php

for ($i=0;$i<sizeof($result);$i++){
	
	$row = $result[$i];
	
	
	print "<tr>";
	
	print "<td>";
	print $row['precue_name'];
	print "</td>";

	print "<td>";
	print $row['colour_distractor_ratio'];
	print "</td>";

	print "<td>";
	print $row['shape_distractor_ratio'];
	print "</td>";

	print "<td>";
	print $row['mean'];
	print "</td>";

	print "<td>";
	print $row['sd'];
	print "</td>";
	
	print "</tr>";
	
	
}

?>


</table>