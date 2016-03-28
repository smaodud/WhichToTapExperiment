<?php 
include_once '../blls/functions.php';

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
include_once '../blls/icon_manager.php'; 

$iconMgr = new IconManager();

// $tempArray = array();

$target_icon_id =$iconMgr->getTargetIconId();

print "<br>Targer id$target_icon_id<br>";

$no_of_colors = 2;

$no_of_shapes = 6;

$no_of_icons_in_screen =20;

// $no_of_others = $no_of_icons_in_screen - $no_of_colors - $no_of_shapes-1;

// $targetIcon = $iconMgr->getIcon($target_icon_id);

// array_push($tempArray, $targetIcon);
// $colorArray = $iconMgr -> getIconsByColour($target_icon_id, $no_of_colors); //distractor 1
// $tempArray = array_merge($tempArray,$colorArray);


// $shapeArray = $iconMgr -> getIconsByShape($target_icon_id, $no_of_shapes); //distractor 2
// $tempArray = array_merge($tempArray,$shapeArray);

// $restArray = $iconMgr -> getIconsByOhters($target_icon_id,$tempArray ,$no_of_others);




// $othersArray = getRandomArray($restArray, $no_of_others);



// $tempArray = array_merge($tempArray,$othersArray);
// shuffle($tempArray);

$tempArray = $iconMgr->getIconsForScreen($target_icon_id, $no_of_colors, $no_of_shapes, $no_of_icons_in_screen);

//$tempArray = $colorArray;
for ($i=0;$i<sizeof($tempArray);$i++){
	
	$row = $tempArray[$i];
	
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
