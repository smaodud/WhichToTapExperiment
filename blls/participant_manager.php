
<?php
class ParticipantManager {
	function insert($name, $age, $gender) {
		include_once 'conn.php';
		
		$result = mysql_query ( "insert into participants  (participant_name,age,gender) values ('$name',$age,'$gender') " );
		
		if ($result) {
			return mysql_insert_id ();
		} else {
			$result = mysql_query ( "select participant_id from participants where participant_name = '$name'" );
			
			$row = mysql_fetch_assoc ( $result );
			
			return $row ['participant_id'];
		}
	}
	function getAll() {
		include_once 'conn.php'; // connect to db
		
		$sql = <<<EOT
				select participant_id
		, participant_name
		,age
	    ,gender
		from participants
		
EOT;
		
		$result = mysql_query ( $sql ); // execute sql and fetch result
		                                
		// process fetched result and return to where needed
		if ($result) {
			
			return $result;
		} else {
			
			return false;
		}
	}
	function get($participant_id) {
		include_once 'conn.php'; // connect to db
		
		$sql = <<<EOT
		
		select participant_id
		,participant_name
		,age
	    ,gender
		
		from participants 
		where participant_id = $participant_id
EOT;
		
		// print "<br>$sql<br>";
		
		$result = mysql_query ( $sql ); // execute sql and fetch result
		                                
		// process fetched result and return to where needed
		if ($result) {
			
			$row = mysql_fetch_assoc ( $result );
			
			return $row;
		} else {
			
			return false;
		}
	}
	
	
	function getMean($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id) {
		include_once 'conn.php';
		
		$sql = <<<DOM
    		SELECT sum(end_time - start_time)/count(t.trial_id) as mean 
				FROM attempts as a Join trials as t on a.trial_id = t.trial_id 
				WHERE t.precue_set = $precue_set and t.colour_distractor_ratio = $colour_distractor_ratio 
				and t.shape_distractor_ratio = $shape_distractor_ratio and t.participant_id = $participant_id 
				and t.trial_id not in 
					(SELECT tt.trial_id FROM attempts as aa Join trials as tt on aa.trial_id = tt.trial_id 
						WHERE tt.precue_set = $precue_set and tt.colour_distractor_ratio = $colour_distractor_ratio 
						and tt.shape_distractor_ratio = $shape_distractor_ratio and tt.participant_id = $participant_id 
						and aa.is_correct = false 
					)
DOM;
		
		// print $sql."<br><br>";
		
		$result = mysql_query ( $sql );
		
		$row = mysql_fetch_assoc ( $result );
		
		return $row ['mean'];
	}
	
	
	function getSD($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id) {
		include_once 'conn.php';
		
		$mean = $this->getMean ( $precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id );
		
		$mean = isset ( $mean ) ? $mean : 0.00;
		
		switch ($precue_set) {
			case 1 :
				$precue_name = "Full Icon";
				break;
			case 2 :
				$precue_name = "Colour & Name";
				break;
			
			case 3 :
				$precue_name = "Shape & Name";
				break;
			
			case 4 :
				$precue_name = "Name Only";
				break;
		}
		
		$sql = <<<DOM
			SELECT $precue_set as precue_set
			        , '$precue_name' as precue_name
					,  $colour_distractor_ratio as colour_distractor_ratio 
							,$shape_distractor_ratio as shape_distractor_ratio
								, $mean as mean 	
							,sum(pow(end_time - start_time-$mean,2))/count(t.trial_id) as sd
									FROM attempts as a Join trials as t on a.trial_id = t.trial_id 
										WHERE t.precue_set = $precue_set 
										and t.colour_distractor_ratio = $colour_distractor_ratio 
										and t.shape_distractor_ratio = $shape_distractor_ratio 
										and t.participant_id = $participant_id 
										and t.trial_id not in (SELECT tt.trial_id 
										FROM attempts as aa Join trials as tt on aa.trial_id = tt.trial_id 
										WHERE tt.precue_set = $precue_set 
										and tt.colour_distractor_ratio = $colour_distractor_ratio 
										and tt.shape_distractor_ratio = $shape_distractor_ratio 
										and tt.participant_id = $participant_id and aa.is_correct = false )
DOM;
		
		// print $sql."<br>";
		$result = mysql_query ( $sql );
		
		$row = mysql_fetch_assoc ( $result );
		
		$arr = array (
				"precue_set" => $row ["precue_set"],
				"precue_name" => $row ["precue_name"],
				"colour_distractor_ratio" => $row ["colour_distractor_ratio"],
				"shape_distractor_ratio" => $row ["shape_distractor_ratio"],
				"mean" => $row ["mean"],
				"sd" => sqrt( $row ["sd"]) 
		);
		
		return $arr;
	}
	function getResultTable($participant_id) {
		$result = array ();
		// precue set = 1, i.e. full icon and distractors
		$row = $this->getSD ( 1, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 1, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 1, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 1, 6, 6, $participant_id );
		array_push ( $result, $row );
		
		// precue set = 2, i.e. full icon and distractors
		$row = $this->getSD ( 2, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 2, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 2, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 2, 6, 6, $participant_id );
		array_push ( $result, $row );
		
		$row = $this->getSD ( 3, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 3, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 3, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 3, 6, 6, $participant_id );
		array_push ( $result, $row );
		
		$row = $this->getSD ( 4, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 4, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 4, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getSD ( 4, 6, 6, $participant_id );
		array_push ( $result, $row );
		
		return $result;
	}
	
	
	
	function getTotalErrors($participant_id) {
		$result = array ();
		// precue set = 1, i.e. full icon and distractors
		$row = $this->getErrors ( 1, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 1, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 1, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 1, 6, 6, $participant_id );
		array_push ( $result, $row );
	
		// precue set = 2, i.e. full icon and distractors
		$row = $this->getErrors ( 2, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 2, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 2, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 2, 6, 6, $participant_id );
		array_push ( $result, $row );
	
		$row = $this->getErrors ( 3, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 3, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 3, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 3, 6, 6, $participant_id );
		array_push ( $result, $row );
	
		$row = $this->getErrors ( 4, 2, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 4, 2, 6, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 4, 6, 2, $participant_id );
		array_push ( $result, $row );
		$row = $this->getErrors ( 4, 6, 6, $participant_id );
		array_push ( $result, $row );
	
		return $result;
	}
	
	
	
	function getErrors($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id) {
		include_once 'conn.php';
	
		
		

		$errCnt = $this->getErrorCount ( $precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id );
		
		$errCnt = isset ( $errCnt ) ? $errCnt : 0;
		
		
	
		switch ($precue_set) {
			case 1 :
				$precue_name = "Full Icon";
				break;
			case 2 :
				$precue_name = "Colour & Name";
				break;
					
			case 3 :
				$precue_name = "Shape & Name";
				break;
					
			case 4 :
				$precue_name = "Name Only";
				break;
		}
	
		$sql = <<<DOM
			SELECT $precue_set as precue_set
			        , '$precue_name' as precue_name
					,  $colour_distractor_ratio as colour_distractor_ratio
							,$shape_distractor_ratio as shape_distractor_ratio
								, $errCnt as error_count
							
									FROM attempts as a Join trials as t on a.trial_id = t.trial_id
										WHERE t.precue_set = $precue_set
										and t.colour_distractor_ratio = $colour_distractor_ratio
										and t.shape_distractor_ratio = $shape_distractor_ratio
										and t.participant_id = $participant_id
										and t.trial_id not in (SELECT tt.trial_id
										FROM attempts as aa Join trials as tt on aa.trial_id = tt.trial_id
										WHERE tt.precue_set = $precue_set
										and tt.colour_distractor_ratio = $colour_distractor_ratio
										and tt.shape_distractor_ratio = $shape_distractor_ratio
										and tt.participant_id = $participant_id and aa.is_correct = false )
DOM;
	
		// print $sql."<br>";
		$result = mysql_query ( $sql );
	
		$row = mysql_fetch_assoc ( $result );
	
		$arr = array (
				"precue_set" => $row ["precue_set"],
				"precue_name" => $row ["precue_name"],
				"colour_distractor_ratio" => $row ["colour_distractor_ratio"],
				"shape_distractor_ratio" => $row ["shape_distractor_ratio"],
				"error_count" => $row ["error_count"]
		);
	
		return $arr;
	}
	
	function  getErrorCount($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id){
		$sql = <<<DOM
			select count(* ) as cnt from 
					(SELECT (SELECT count(attempt_id) as total_attempts 
						FROM `attempts` as a WHERE a.trial_id = t.trial_id) as total_attempts 
								FROM trials as t join icons as i on t.target_icon_id=i.icon_id 
									WHERE t.precue_set = $precue_set 
									AND t.colour_distractor_ratio= $colour_distractor_ratio 
									AND t.shape_distractor_ratio = $shape_distractor_ratio 
									AND participant_id = $participant_id 
									ORDER BY t.trial_id ASC) as t where t.total_attempts>1
DOM;
		
		$result = mysql_query ( $sql );
		
		$row = mysql_fetch_assoc ( $result );
		
		return $row['cnt'];
		
	}
	
	
	
	// function incrementForFullIconCount($participant_id) {
	// include_once 'conn.php'; //connect to db
	// $result = mysql_query ( "update participants set full_icon_count = full_icon_count + 1 where participant_id= $participant_id" );
	
	// return $result;
	// }
	
	// function incrementForColourNNameCount($participant_id) {
	// include_once 'conn.php'; //connect to db
	// $result = mysql_query ( "update participants set colour_n_name_count = colour_n_name_count + 1 where participant_id= $participant_id" );
	// return $result;
	// }
	
	// function incrementForShapeNNameCount($participant_id) {
	// include_once 'conn.php'; //connect to db
	// $result = mysql_query ( "update participants set shape_n_name_count = shape_n_name_count + 1 where participant_id= $participant_id" );
	// return $result;
	// }
	
	// function incrementForNameOnlyCount($participant_id) {
	// include_once 'conn.php'; //connect to db
	// $result = mysql_query ( "update participants set name_only_count = name_only_count + 1 where participant_id= $participant_id" );
	// return $result;
	// }
	
	// function isMaxLimit($no_of_trial_for_group, $participant_id){ //max limit = 160 trials
	
	// // print "<br>Participant id set <br>";
	// $row = $this->get ( $participant_id ); // fetch data from db via id
	
	// // store count data into vars
	// // row var stores records
	// // data from db being fetched (how many full_icons displayed, how many name_only, etc.)
	// $full_icon_count = $row ['full_icon_count'];
	
	// $colour_n_name_count = $row ['colour_n_name_count'];
	
	// $shape_n_name_count = $row ['shape_n_name_count'];
	
	// $name_only_count = $row ['name_only_count'];
	
	// $total_no_of_trials =
	// $full_icon_count
	
	// + $colour_n_name_count
	
	// + $shape_n_name_count
	
	// + $name_only_count;
	
	// return ($total_no_of_trials == $no_of_trial_for_group * 4);
	// }
}

?>