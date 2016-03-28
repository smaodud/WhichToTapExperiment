<?php
class TrialManager {
	function getTrials() {
		include_once 'conn.php';
		$sql = <<<EOT
			SELECT trial_id, precue_set, target_icon_id, colour_distractor_ratio, shape_distractor_ratio,arranged_ids, participant_id
			FROM trials where trial_id=$trial_id
EOT;
		$result = mysql_query ( $sql );
		return $result;
	}
	function getTrial($trial_id) {
		include_once 'conn.php';
		$sql = <<<EOT
			SELECT trial_id, precue_set, target_icon_id, colour_distractor_ratio, shape_distractor_ratio,arranged_ids, participant_id 
			FROM trials where trial_id=$trial_id
EOT;
		
		$result = mysql_query ( $sql );
		
		return mysql_fetch_assoc ( $result );
	}
	function save($precue_set, $target_icon_id, $colour_distractor_ratio, $shape_distractor_ratio, $icon_array, $participant_id) {
		include_once 'conn.php';
		
		$indexes = array ();
		
		for($i = 0; $i < sizeof ( $icon_array ); $i ++) {
			
			$row = $icon_array [$i];
			
			$id = $row ["icon_id"];
			
			array_push ( $indexes, $id );
		}
		
		// convert index array to index string
		$indexes = implode ( "#", $indexes );
		
		// print "<br>Indexes: $indexes";
		
		$sql = <<<EOT
 insert into trials  (precue_set, target_icon_id,colour_distractor_ratio,shape_distractor_ratio,arranged_ids,participant_id) 
 values ($precue_set, $target_icon_id,$colour_distractor_ratio,$shape_distractor_ratio,'$indexes' ,$participant_id);
EOT;
		
		// print "<br>$sql<br>";
		
		$result = mysql_query ( $sql );
		
		if ($result) {
			return mysql_insert_id ();
		} else {
			return false;
		}
	}
	function deleteTrials($participant_id) {
		include_once 'conn.php';
		$sql = "DELETE FROM trials WHERE participant_id =$participant_id";
		$result = mysql_query ( $sql );
		
		if ($result) {
			return mysql_insert_id ();
		} else {
			return false;
		}
	}
	function getRatioCount($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id) {
		include_once 'conn.php';
		
		$sql = <<<EOT
 select count(*) as ratio_count 
 from trials 
 where colour_distractor_ratio = $colour_distractor_ratio 
 and shape_distractor_ratio = $shape_distractor_ratio 
 and precue_set = $precue_set
 and participant_id = $participant_id;

EOT;
		
		$result = mysql_query ( $sql );
		
		if ($result) {
			$row = mysql_fetch_assoc ( $result );
			
			return $row ["ratio_count"];
		} else {
			return false;
		}
	}
	function getFullIconCount($participant_id) {
		
		// 1 is precue set number, and the next 2 numbers are distractor ratio
		return $this->getRatioCount ( 1, 2, 2, $participant_id ) + $this->getRatioCount ( 1, 2, 6, $participant_id ) + $this->getRatioCount ( 1, 6, 2, $participant_id ) + $this->getRatioCount ( 1, 6, 6, $participant_id );
	}
	function getColourNNameCount($participant_id) {
		return $this->getRatioCount ( 2, 2, 2, $participant_id ) + $this->getRatioCount ( 2, 2, 6, $participant_id ) + $this->getRatioCount ( 2, 6, 2, $participant_id ) + $this->getRatioCount ( 2, 6, 6, $participant_id );
	}
	function getShapeNNameCount($participant_id) {
		return $this->getRatioCount ( 3, 2, 2, $participant_id ) + $this->getRatioCount ( 3, 2, 6, $participant_id ) + $this->getRatioCount ( 3, 6, 2, $participant_id ) + $this->getRatioCount ( 3, 6, 6, $participant_id );
	}
	function getNameOnlyCount($participant_id) {
		return $this->getRatioCount ( 4, 2, 2, $participant_id ) + $this->getRatioCount ( 4, 2, 6, $participant_id ) + $this->getRatioCount ( 4, 6, 2, $participant_id ) + $this->getRatioCount ( 4, 6, 6, $participant_id );
	}
	
	function totalNumberOfTrials( $participant_id){
		
		$full_icon_count = $this->getFullIconCount ( $participant_id );
		$colour_n_name_count = $this->getColourNNameCount ( $participant_id );
		$shape_n_name_count = $this->getShapeNNameCount ( $participant_id );
		$name_only_count = $this->getNameOnlyCount ( $participant_id );
		
		$total_no_of_trials = $full_icon_count + $colour_n_name_count + $shape_n_name_count + $name_only_count;
		
		return $total_no_of_trials;
	}
	
	
	//function isMaxLimit($no_of_trial_for_group, $participant_id) { // max limit = 160 trials
	
	function isMaxLimit($no_of_trial_for_group, $total_no_of_trials) {
		
// 		$full_icon_count = $this->getFullIconCount ( $participant_id );
// 		$colour_n_name_count = $this->getColourNNameCount ( $participant_id );
// 		$shape_n_name_count = $this->getShapeNNameCount ( $participant_id );
// 		$name_only_count = $this->getNameOnlyCount ( $participant_id );
		
// 		$total_no_of_trials = $full_icon_count + $colour_n_name_count + $shape_n_name_count + $name_only_count;
	//	$total_no_of_trials = $this -> totalNumberOfTrials($no_of_trial_for_group, $participant_id);
		
		
		if ($total_no_of_trials < $no_of_trial_for_group * 4) {
			return false;
		} else {
			return true;
		}
	}
	function getTrialsByParticipant($participant_id) {
		include_once 'conn.php';
		$sql = <<<EOT
          SELECT 
            t.trial_id,
			t.precue_set
			, t.target_icon_id as icon_id
			, i.icon_name
			, i.full_icon
			, i.shape
			, i.colour
			, i.icon_colour
			, i.icon_shape
			, t.colour_distractor_ratio
			, t.shape_distractor_ratio
			, (SELECT sum( end_time-start_time) as total_time FROM `attempts` as a WHERE a.trial_id = t.trial_id) as total_time
			, (SELECT count(attempt_id) as total_attempts FROM `attempts` as a WHERE a.trial_id = t.trial_id) as total_attempts
			
			FROM trials as t join icons as i on t.target_icon_id=i.icon_id
			WHERE participant_id = $participant_id ORDER BY t.trial_id ASC
	
EOT;
		
		//print $sql;
		$result = mysql_query ( $sql );
		return $result;
	}
	
function getTrialsByParticipantAndRatio($precue_set, $colour_distractor_ratio, $shape_distractor_ratio, $participant_id){
	include_once 'conn.php';
	$sql = <<<EOT
          SELECT
            t.trial_id,
			t.precue_set
			, t.target_icon_id as icon_id
			, i.icon_name
			, i.full_icon
			, i.shape
			, i.colour
			, i.icon_colour
			, i.icon_shape
			, t.colour_distractor_ratio
			, t.shape_distractor_ratio
			, (SELECT sum( end_time-start_time) as total_time FROM `attempts` as a WHERE a.trial_id = t.trial_id) as total_time
			, (SELECT count(attempt_id) as total_attempts FROM `attempts` as a WHERE a.trial_id = t.trial_id) as total_attempts
		
			FROM trials as t join icons as i on t.target_icon_id=i.icon_id
			WHERE t.precue_set = $precue_set 
			AND t.colour_distractor_ratio= $colour_distractor_ratio
			AND t.shape_distractor_ratio =  $shape_distractor_ratio 
			AND participant_id = $participant_id 
			ORDER BY t.trial_id ASC
	
EOT;
	
	//print $sql."<br>";
	$result = mysql_query ( $sql );
	return $result;
}
	
	
}

?>

