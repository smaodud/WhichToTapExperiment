<?php


class PrecueManager{
	

function getPrequeState1(){	
	include_once 'conn.php';	
$result = mysql_query("select icon_id, icon_name, full_icon, shape, colour, icon_colour, icon_shape from icons");	
	return $result;
}



function getPrequeState1Array(){
	$result = $this->getPrequeState1();
	
	$arr = array();
	while($row = mysql_fetch_assoc($result)){
	
		$arr[] = array("precue_id"=>$row['precue_id']
				,"icon_name"=>$row['icon_name']
				,"full_icon"=>$row['full_icon']
				,"shape"=>$row['shape']
				,"colour"=>$row['colour']
				,"icon_colour"=>$row['icon_colour']
				,"icon_shape"=>$row['icon_shape']
		);
		
		
	}
	
	return $arr;
	
}

}


?>

