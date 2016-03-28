<?php

$conn = @mysql_connect('localhost','',''); //enter db username and password in empty fields
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('whichtotap', $conn);

?>