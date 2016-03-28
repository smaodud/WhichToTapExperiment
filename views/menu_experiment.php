<?php
session_start ();
include_once '../blls/attempts_manager.php';

$attemptMgr = new AttemptManager ();

$target_icon_id = $_SESSION ["TARGET_ICON_ID"];

$img_array = $_SESSION ["IMG_ARRAY"];

$trial_id = $_SESSION ['trial_id'];

$attempt_id = $attemptMgr->insert ( $trial_id );

$_SESSION ['attempt_id'] = $attempt_id;

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

<script>
        var targetIconId = <?php echo json_encode($target_icon_id);?>;
        var trialId = <?php echo json_encode($trial_id);?>;
    </script>
</head>
<body>

	<div class="outer">
		<div class="middle">
			<div class="inner">

				<div align="center">

					<table style="margin-top: 10px" ; width="100%" height="100%"
						align="center">

                    														<?php
																				
																				$count = 0;
																				
																				for($i = 0; $i < sizeof ( $img_array ); $i ++) {
																					
																					$row = $img_array [$i];
																					$index = $row ['icon_id'];
																					$img = $row ['full_icon'];
																					$icon_name = $row ['icon_name'];
																					
																					if ($count == 0) {
																						print "<tr>";
																					}
																					
																					print "<td style = \"text-align: center; padding-left: 3px; padding-right: 5px; padding-top: 3px; padding-bottom: 3px;\">";
																					
																					$iconData = base64_encode ( $img );
																					print "<img src=\"data:image/jpeg;base64,$iconData\"  height=\"58\" width=\"60\" onclick=\"show($index)\" /><br>";
																					print $icon_name; // print filename without .png extension
																					print "</td>";
																					
																					$count ++;
																					
																					if ($count == 4) {
																						print "</tr>";
																						$count = 0;
																					}
																				}
																				?>



                </table>



				</div>

			</div>
		</div>
	</div>
	<script>

    function show(selectedIconId){
        if(targetIconId == selectedIconId){
           location.href = 'target_experiment.php?status=true&trial_id='+trialId;
        }
        else{
            location.href = 'feedback_experiment.php?status=false&trial_id='+trialId;
        }

     }

</script>

</body>
</html>
