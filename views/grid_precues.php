<?php 

$img_size = 40;

?>

<html>
<head>
</head>
<body>
<?php 
include_once 'admin_menu.php';

?>
<table>
<tr>
<th>Id</th><th>name</th><th>icon</th><th>shape</th><th>colour</th>
</tr>
<?php 
include_once '../blls/precue_manager.php'; 

$precueMgr = new PrecueManager();

$result = $precueMgr -> getPrequeState1();


while($row = mysql_fetch_assoc($result)){
	print "<tr>";
	$id= $row["icon_id"];
	print "<td>".$row["icon_id"]."</td>";
	print "<td>".$row["icon_name"]."</td>";

	$icon= $row['full_icon'];
	 
	$iconData  = base64_encode( $icon );
	print "<td><img src=\"data:image/jpeg;base64,$iconData\" width=\"$img_size\" height=\"$img_size\"  /></td>"; //img processing
																	//fetches blob and converts to base64 img
	$shape= $row['shape'];
	$shapeData  = base64_encode( $shape );
	print "<td><img src=\"data:image/jpeg;base64,$shapeData\"  width=\"$img_size\" height=\"$img_size\"/></td>";
	$colour= $row['colour'];
	$colorData  = base64_encode( $colour );
	print "<td><img src=\"data:image/jpeg;base64,$colorData\"  width=\"$img_size\" height=\"$img_size\"/></td>";
	print "<td>".$row["icon_colour"]."</td>";
	print "<td>".$row["icon_shape"]."</td>";
	
	print "</tr>";
}

?>
</table>

</body>

</html>

<?php
