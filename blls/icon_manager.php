<?php


class IconManager{

	
	
//fetch set of icons to display
function getIcons(){	
	include_once 'conn.php';	
$result = mysql_query("select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons");	
	return $result;
}


function getIconsByIds($icon_ids){
	include_once 'conn.php';
	//$icon_ids = implode($icon_ids, ",");
	$arr = array();
	
	for($i=0;$i<sizeof($icon_ids);$i++){
	$row =	$this->getIcon($icon_ids[$i]);
	
	$rowarray = $this-> rowToArray($row);
	
	array_push($arr, $rowarray);
	}
	
	//$sql = "select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons where icon_id in ($icon_ids) ";
	
	
	
	//$result = mysql_query($sql);
	
//	$arr = $this ->resultSetToArray($result);
	
	return $arr;
}

//fetch a record by icon_id (display target icon)
//for target page
function getIcon($icon_id){
	include_once 'conn.php';
	$result = mysql_query("select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons
	where icon_id=$icon_id");
	return mysql_fetch_assoc( $result);
}


function  rowToArray($row){
	$array =	 array("icon_id"=>$row['icon_id']
			,"icon_name"=>$row['icon_name']
			,"full_icon"=>$row['full_icon']
			,"shape"=>$row['shape']
			,"colour"=>$row['colour']
			,"icon_colour"=>$row['icon_colour']
			,"icon_shape"=>$row['icon_shape']
	);
	return $array;
}

function resultSetToArray($result){

	$arr = array();
	while($row = mysql_fetch_assoc($result)){
	
 $arrayItem =	$this->rowToArray($row);

		array_push($arr, $arrayItem);
	
	
	}
	

	
	return $arr;
}

//convert set of icons from getIcons() to array
//return array
//for menu page
function getIconArray(){
	$result = $this->getIcons();
	
	$arr = $this->resultSetToArray($result);
	
	return $arr;
	
}


function getIconsByColour($target_icon_id,$no_of_ratio_icons){
	include_once 'conn.php';
	
	$sql =<<<EOY
	select  icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons 
			where icon_colour = (select icon_colour from icons where icon_id = $target_icon_id) 
			and icon_id <> $target_icon_id
			limit $no_of_ratio_icons;
EOY;
	
	
	//print "<br>$sql";
	
	$result = mysql_query($sql);
	
	
$arr = $this->resultSetToArray($result);
	
	return $arr;
}

function getIconsByShape($target_icon_id,$no_of_ratio_icons){
	include_once 'conn.php';
	

	$sql =<<<EOY
	select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons
			where icon_shape = (select icon_shape from icons where icon_id = $target_icon_id) 
				and icon_id <> $target_icon_id
				limit $no_of_ratio_icons;
EOY;
	$result = mysql_query($sql);
	
	
	$arr = $this->resultSetToArray($result);
	
	return $arr;
}

function getIconsByOhters($target_icon_id, $tempArray ){	
		include_once 'conn.php';
	
	$excludedIconIds = array();
		for ($i=0;$i<sizeof($tempArray);$i++){
			
			$row = $tempArray[$i];
			$icon_id = $row["icon_id"];
			
			array_push($excludedIconIds, $icon_id);
			//print "<br>ic_id: $icon_id<br>";
			
		}
		
		$excludedIconIds = implode($excludedIconIds, ",");
		//print "<br>exc: $excludedIconIds<br>";
		$sql =<<<EOY
	select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons
			where icon_id not in ($excludedIconIds) 
			and icon_colour <> (select icon_colour from icons where icon_id = $target_icon_id)
			and icon_shape <> (select icon_shape from icons where icon_id = $target_icon_id);
		
EOY;

	
		
	//	print "<br>sql: $sql<br>";
		
		
		$result = mysql_query($sql);
	
	

	
		
		$arr = $this->resultSetToArray($result);
		
	return $arr;
}

//randomly select target icon
function getTargetIconId(){
  $result =	$this->getIcons(); 
  // select a random number from  1 to 64 
  return rand(1,  mysql_num_rows($result)); 

}

function getIconsForScreen($target_icon_id,$no_of_colors,$no_of_shapes,$no_of_icons_in_screen){
	
	$tempArray = array(); // creates an empty array
	
	$no_of_others = $no_of_icons_in_screen - $no_of_colors - $no_of_shapes-1;  // 20 - 2 - 6 - 1 = 11
	
	$targetIcon = $this->getIcon($target_icon_id);  // get all info of target icon by its icon id
	
	array_push($tempArray, $targetIcon); // push target icon info to $tempArray
	$colorArray = $this->getIconsByColour($target_icon_id, $no_of_colors); //distractor 1
	$tempArray = array_merge($tempArray,$colorArray);
	
	
	$shapeArray =  $this->getIconsByShape($target_icon_id, $no_of_shapes); //distractor 2
	$tempArray = array_merge($tempArray,$shapeArray);
	
	$restArray =  $this->getIconsByOhters($target_icon_id,$tempArray ); // 64 - 9 = 55
	$othersArray = getRandomArray($restArray, $no_of_others); // retun only 11 from (64-9)
	
	$tempArray = array_merge($tempArray,$othersArray); // 20
	
	shuffle($tempArray);
	
	return $tempArray;
}



}


?>

