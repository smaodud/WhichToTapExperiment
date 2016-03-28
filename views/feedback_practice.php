<?php
include_once '../blls/attempts_manager.php';
session_start();

$attemptMgr = new AttemptManager();

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

            <div align="center" style="margin-top:200px">

                <table >
                    <tr>
                        <td style="color: #d10000; font-size: 25px">
                            Incorrect!
                        </td>


                    <tr>

                        <td style="padding-top: 100px;text-align: center">

                            <!-- <button onlick="#"><a href="target.php">Try Again</a></button> -->
<?php 
$status=$_GET['status'];

$trial_id=$_GET['trial_id'];

if(isset($_SESSION['attempt_id'])){
	$attempt_id=$_SESSION['attempt_id'];
}


$attemptMgr-> update($status, $attempt_id);

?>

                            <button onclick=<?php echo "\"location.href = 'target_practice.php?status=$status&trial_id=$trial_id'\"" ?>  type="button"
                                    class="btn btn-danger btn-lg">Try Again</button>
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