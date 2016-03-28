<?php
session_start ();

define ( "SCREEN_ICON_SIZE", 20 );

$no_of_same_distractor_per_group = 10; // eg., x 2,2 distractors

$no_of_trial_for_group = 40; // 4 x no_of_trials_for_group = total trials

include_once '../blls/participant_manager.php';
include_once '../blls/icon_manager.php';
include_once '../blls/trial_manager.php';
include_once '../blls/attempts_manager.php';
include_once '../blls/functions.php';
// include_once 'async_operation.php';

$trialMgr = new TrialManager ();
$iconMgr = new IconManager ();
$attemptsMgr = new AttemptManager ();

// to fetch participant records from db
// inserts participant record
// increments count during trials
$participantMgr = new ParticipantManager ();

$request_method = $_SERVER ['REQUEST_METHOD'];

// when user inputs name, POST method passes data to following page
// then initial var = true
if ($request_method == 'POST' && isset ( $_POST ['participant_id'] )) {
	$participant_id = $_POST ['participant_id']; // name to keep track of participant
	                                             // post data from client side to server
	                                             // user data saved to db
	                                             // id generated
	                                             // $participant_id = $participantMgr->insert ( $participant_name ); // to db
	                                             // save id in current session
	$_SESSION ['participant_id'] = $participant_id;
	$initial = true;
} else {
	$initial = false;
}

// request from break_time.php
// if ($request_method == 'GET' && isset ( $_GET ['participant_id'] )) {
// 	$participant_id = $_GET ['participant_id'] ;	
// 	$_SESSION ['participant_id'] = $participant_id;
// 	$initial = true;
// }


if (isset ( $_SESSION ['participant_id'] )) {
	$participant_id = $_SESSION ['participant_id']; // if id found in session, take it
}

if (isset ( $participant_id )) {
	$row = $participantMgr->get ( $participant_id ); // fetch data from db via id
	                                                 // from trial_manager.php
	$full_icon_count = $trialMgr->getFullIconCount ( $participant_id );
	$colour_n_name_count = $trialMgr->getColourNNameCount ( $participant_id );
	$shape_n_name_count = $trialMgr->getShapeNNameCount ( $participant_id );
	$name_only_count = $trialMgr->getNameOnlyCount ( $participant_id );
	
	// print "full: $full_icon_count<br> colour: $colour_n_name_count<br> shape: $shape_n_name_count<br>name: $name_only_count";
} 

else {
	// nothing happens
}

// if user selects incorrect icon
if ($request_method == 'GET' && isset ( $_GET ['status'] ) && isset ( $_GET ['trial_id'] )) {
	// when incorrect, data is obtained from feedback page using GET method
	$attempt_status = stringToBool ( $_GET ['status'] );
	$trial_id = $_GET ['trial_id'];
	
	if ($attempt_status == true) { // if attempt is correct, update time using attempts_manager.php
		$attempt_id = $_SESSION ['attempt_id'];
		$attemptsMgr->update ( $attempt_status, $attempt_id );
	} else 
	
	// if attempt false, fetch last state of trails
	if ($attempt_status == false) {
		// following processes fetch old icon arrangements to display when attempt is incorrect
		$rowTrial = $trialMgr->getTrial ( $trial_id );
		$precue_set = $rowTrial ["precue_set"];
		$target_icon_id = $rowTrial ["target_icon_id"];
		$rowIcon = $iconMgr->getIcon ( $target_icon_id );
		$target_icon_name = $rowIcon ["icon_name"];
		$arranged_ids = $rowTrial ["arranged_ids"];
		$arranged_ids = explode ( "#", $arranged_ids );
		$icon_array = $iconMgr->getIconsByIds ( $arranged_ids ); // method in icon_manager
	
		switch ($precue_set) {
			case 1 :
				$target_img = $rowIcon ['full_icon'];
				break;
			case 2 :
				$target_img = $rowIcon ['colour'];
				break;
			case 3 :
				$target_img = $rowIcon ['shape'];
				break;
			case 4 :
				$target_img = "";
				break;
		}		
		$_SESSION ['trial_id'] = $rowTrial ["trial_id"];
	}
}

// incrementing count
// receive array from db
// select random icon as target, place into display array

$total_no_of_trials = $trialMgr->totalNumberOfTrials($participant_id);

$isMaxLimit = $trialMgr->isMaxLimit ($no_of_trial_for_group, $total_no_of_trials);

// if($total_no_of_trials!=0 && $total_no_of_trials % $no_of_trial_for_group == 0 && !$isMaxLimit){	
// 	print "<script type=\"text/javascript\">location.href = 'break_time.php?participant_id=$participant_id';</script>";
// }


// print "ismax: $isMaxLimit";
// until max no. of trials is reached (160), keep entering loop

if ($isMaxLimit == false && ($initial == true || isset ( $attempt_status ) && $attempt_status == true)) {
	// if initial = true, new arrangement of icons	
	
	$target_icon_id = $iconMgr->getTargetIconId ();
	$target_icon_item = $iconMgr->getIcon ( $target_icon_id );
	$target_icon_name = $target_icon_item ['icon_name'];
	
	$flag = false;
	while ( $flag == false ) { //if flag false, enter condition
	                         
		$precueSet = rand ( 1, 4 ); // choose precue set
		
		switch ($precueSet) {
			case 1 :
				
				$flag = $full_icon_count < $no_of_trial_for_group;
				// if full_icon_count is less than no of trials for group, then true
				
				if ($flag) {
					$full_icon_2_2_count = $trialMgr->getRatioCount ( 1, 2, 2, $participant_id );
					$full_icon_2_6_count = $trialMgr->getRatioCount ( 1, 2, 6, $participant_id );
					$full_icon_6_2_count = $trialMgr->getRatioCount ( 1, 6, 2, $participant_id );
					$full_icon_6_6_count = $trialMgr->getRatioCount ( 1, 6, 6, $participant_id );
					
					if ($full_icon_2_2_count < $no_of_same_distractor_per_group) {
						$ratioSet = 22;
					} 
					else if ($full_icon_2_6_count < $no_of_same_distractor_per_group) {
						$ratioSet = 26;
					} 
					else if ($full_icon_6_2_count < $no_of_same_distractor_per_group) {
						$ratioSet = 62;
					} 
					else if ($full_icon_6_6_count < $no_of_same_distractor_per_group) {
						$ratioSet = 66;
					}
				}				
				break;
				
			case 2 :
				$flag = ($colour_n_name_count < $no_of_trial_for_group) ? true : false;
				
				if ($flag) {
					$colour_n_name_2_2_count = $trialMgr->getRatioCount ( 2, 2, 2, $participant_id );
					$colour_n_name_2_6_count = $trialMgr->getRatioCount ( 2, 2, 6, $participant_id );
					$colour_n_name_6_2_count = $trialMgr->getRatioCount ( 2, 6, 2, $participant_id );
					$colour_n_name_6_6_count = $trialMgr->getRatioCount ( 2, 6, 6, $participant_id );
					
					if ($colour_n_name_2_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 22;
					} 

					else if ($colour_n_name_2_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 26;
					} 

					else if ($colour_n_name_6_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 62;
					} 

					else if ($colour_n_name_6_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 66;
					}
				}
				
				break;
			
			case 3 :
				$flag = ($shape_n_name_count < $no_of_trial_for_group) ? true : false;
				if ($flag) {
					
					$shape_n_name_2_2_count = $trialMgr->getRatioCount ( 3, 2, 2, $participant_id );
					$shape_n_name_2_6_count = $trialMgr->getRatioCount ( 3, 2, 6, $participant_id );
					$shape_n_name_6_2_count = $trialMgr->getRatioCount ( 3, 6, 2, $participant_id );
					$shape_n_name_6_6_count = $trialMgr->getRatioCount ( 3, 6, 6, $participant_id );
					
					if ($shape_n_name_2_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 22;
					} 

					else if ($shape_n_name_2_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 26;
					} 

					else if ($shape_n_name_6_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 62;
					} 

					else if ($shape_n_name_6_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 66;
					}
				}
				
				break;
			case 4 :
				$flag = ($name_only_count < $no_of_trial_for_group) ? true : false;
				
				if ($flag) {
					$name_only_2_2_count = $trialMgr->getRatioCount ( 4, 2, 2, $participant_id );
					$name_only_2_6_count = $trialMgr->getRatioCount ( 4, 2, 6, $participant_id );
					$name_only_6_2_count = $trialMgr->getRatioCount ( 4, 6, 2, $participant_id );
					$name_only_6_6_count = $trialMgr->getRatioCount ( 4, 6, 6, $participant_id );
					
					if ($name_only_2_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 22;
					} 

					else if ($name_only_2_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 26;
					} 

					else if ($name_only_6_2_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 62;
					} 

					else if ($name_only_6_6_count < $no_of_same_distractor_per_group) {
						
						$ratioSet = 66;
					}
				}
				
				break;
		}
	} // while ends
	
	switch ($precueSet) {
		case 1 :
			if ($full_icon_count <= $no_of_trial_for_group) {
				
				$target_img = $target_icon_item ['full_icon'];
				
				switch ($ratioSet) {
					case 22 :
							
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 1, $target_icon_id, 2, 2, $icon_array, $participant_id );
						break;
					case 26 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 1, $target_icon_id, 2, 6, $icon_array, $participant_id );
						break;
					case 62 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 1, $target_icon_id, 6, 2, $icon_array, $participant_id );
						break;
					case 66 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 1, $target_icon_id, 6, 6, $icon_array, $participant_id );
						break;
				}
				
				$_SESSION ['trial_id'] = $trial_id;
			}
			break;
		
		case 2 :
			
			if ($colour_n_name_count <= $no_of_trial_for_group) {
				
				$target_img = $target_icon_item ['colour'];
				
				switch ($ratioSet) {
					case 22 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 2, $target_icon_id, 2, 2, $icon_array, $participant_id );
						break;
					case 26 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 2, $target_icon_id, 2, 6, $icon_array, $participant_id );
						break;
					case 62 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 2, $target_icon_id, 6, 2, $icon_array, $participant_id );
						break;
					case 66 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 2, $target_icon_id, 6, 6, $icon_array, $participant_id );
						break;
				}
				
				$_SESSION ['trial_id'] = $trial_id;
			}
			break;
		
		case 3 :
			
			if ($shape_n_name_count <= $no_of_trial_for_group) {
				// $participantMgr->incrementForShapeNNameCount ( $participant_id );
				
				$target_img = $target_icon_item ['shape'];
				
				switch ($ratioSet) {
					case 22 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 3, $target_icon_id, 2, 2, $icon_array, $participant_id );
						break;
					case 26 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 3, $target_icon_id, 2, 6, $icon_array, $participant_id );
						break;
					case 62 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 3, $target_icon_id, 6, 2, $icon_array, $participant_id );
						break;
					case 66 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 3, $target_icon_id, 6, 6, $icon_array, $participant_id );
						break;
				}
				
				$_SESSION ['trial_id'] = $trial_id;
			}
			break;
		
		case 4 :
			
			if ($name_only_count <= $no_of_trial_for_group) {
				
				// $participantMgr->incrementForNameOnlyCount ( $participant_id );
				
				$target_img = '';
				
				switch ($ratioSet) {
					case 22 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 4, $target_icon_id, 2, 2, $icon_array, $participant_id );
						break;
					case 26 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 2, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 4, $target_icon_id, 2, 6, $icon_array, $participant_id );
						break;
					case 62 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 2, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 4, $target_icon_id, 6, 2, $icon_array, $participant_id );
						break;
					case 66 :
						$icon_array = $iconMgr->getIconsForScreen ( $target_icon_id, 6, 6, SCREEN_ICON_SIZE );
						$trial_id = $trialMgr->save ( 4, $target_icon_id, 6, 6, $icon_array, $participant_id );
						break;
				}
				
				$_SESSION ['trial_id'] = $trial_id;
			}
			break;
	}
}

?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/style.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


</head>
<body>

	<div class="outer">
		<div class="middle">
			<div class="inner">

				<div align="center" style="margin-top: 200px">


					<table>
						<tr>
							<td style="padding-right: 5px; font-size: 18px;">FIND:</td>

							<td style="padding-left: 5px; text-align: center">
                        <?php
																								
																								if ($isMaxLimit == false || ($isMaxLimit == true && $attempt_status == false)) {
																									// $_SESSION ["INDEXES"] = $indexes; // save specific img indexes to be printed into session
																									$_SESSION ["TARGET_ICON_ID"] = $target_icon_id; // save target img index into session
																									$_SESSION ["IMG_ARRAY"] = $icon_array; // save array of imgs into session
																									
																									if ($target_img == "") {
																										print "<img src=\"../images\blank.png\" height=\"65\" width=\"65\">";
																									} else {
																										$iconData = base64_encode ( $target_img );
																										print "<img src=\"data:image/jpeg;base64,$iconData\" height=\"65\" width=\"65\">";
																									}
																									print "<br>";
																									
																									print $target_icon_name;
																								} else if ($attempt_status == true) {
																									//
																									print "<script type=\"text/javascript\">location.href = 'thanks.php';</script>";
																								}
																								
																								?>
                        </td>
						
						
						<tr>
							<td></td>

							<td>
								<!-- <button onlick="#"><a href="menu1.php">GO!</a></button> -->

								<button onclick="location.href = 'menu_experiment.php'" type="button"
									style="position: absolute; left: 48.5%; right: 50%; bottom: 35%;"
									class="btn btn-default btn-lg">GO!</button>
							</td>

						</tr>

						</tr>
					</table>


				</div>

			</div>
		</div>
	</div>


</body>
</html>