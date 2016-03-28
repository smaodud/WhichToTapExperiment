<?php
class AttemptManager {
	function getAttempts() {
		include_once 'conn.php';
		$result = mysql_query ( "SELECT attempt_id, start_time, end_time, is_correct, trial_id FROM attempts" );
		return $result;
	}
	
	function getAttemptsByTrial($trial_id) {
		include_once 'conn.php';
		$result = mysql_query ( "SELECT attempt_id, (end_time-start_time)  as attempt_time , is_correct, trial_id FROM attempts where trial_id = $trial_id" );
		return $result;
	}
	
	function insert($trial_id) {
		include_once 'conn.php';
		
		$microseconds = microtime ( true );
		$sql = "insert into attempts  (start_time, trial_id) values ($microseconds, $trial_id)";
		// "insert into attempts (end_time,is_correct, trial_id) values (NOW(),$is_correct, $trial_id)"
		// print "<br>$sql";
		$result = mysql_query ( $sql );
		if ($result) {
			return mysql_insert_id ();
		} else {
			return false;
		}
	}
	function update($is_correct, $attempt_id) {
		include_once 'conn.php';
		
		// print gettype($attempt_id);
		
		// print "<br>1 is_correct: $is_correct";
		
		$is_correct = $is_correct == 1 ? "true" : "false";
		
		// print "<br>2 is_correct: $is_correct";
		
		$microseconds = microtime ( true );
		
		$sql = "update  attempts set  end_time= $microseconds, is_correct =$is_correct where attempt_id = $attempt_id";
		// print "<br>$sql";
		$result = mysql_query ( $sql );
		if ($result) {
			return mysql_insert_id ();
		} else {
			return false;
		}
	}
	
	
}

?>

