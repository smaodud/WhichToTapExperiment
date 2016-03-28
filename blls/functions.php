<?php


function getRandomIndexes($img_array){
	$no_of_img = sizeof($img_array);

	$indexes = array(); //empty array


	while (sizeof($indexes) != SCREEN_ICON_SIZE) {

		$number = rand(0, $no_of_img - 1); //randomly chooses icon/img from 0 to 41

		if (!in_array($number, $indexes)) { //new img added only when not previously present,
			// number corresponds to img index
			$indexes[] = $number;          //pushing img to array
		}

	}

	return $indexes;
}


function getRandomIndexesBySize($icon_array, $size){
	$no_of_img = sizeof($icon_array);

	$indexes = array(); //empty array


	while (sizeof($indexes) != $size) {

		$number = rand(0, $no_of_img - 1); //randomly chooses icon/img from 0 to 41

		if (!in_array($number, $indexes)) { //new img added only when not previously present,
			// number corresponds to img index
			$indexes[] = $number;          //pushing img to array
		}

	}

	return $indexes;
}


function stringToBool($variable){
	return 'true' === $variable;
}

function getRandomArray($allArray, $no_of_others_icons){

	$newArray = array();
	
	$size_of_all_array = sizeof($allArray);
	
	//print "<br> $size_of_rest<br>";
	
	$indexes = array(); // create an indexes array
	
	while (sizeof($indexes) != $no_of_others_icons){
		$index = rand(0, $size_of_all_array-1); // 55 - 1 = 53  
	
		//print "<br>$index <br>";
		//if selected index is not already in array indexes
		if(!array_search($index, $indexes)){
			array_push($indexes, $index);
			array_push($newArray, $allArray[$index]);
			//print "<br>index not found <br>";
		}
	
	}
	
	return $newArray;
}


?>