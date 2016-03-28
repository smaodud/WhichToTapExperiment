<!DOCTYPE html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


</head>

<body>

	<div align="center">


		<p style="font-size: 16pt; text-align: center">
			<br> <br> Please fill in ALL the fields below: <br>
		</p>

		<form action="./views/instruction.php" method="post">

			<table>
				<tr>
					<td><br> Participant No.</td>
				</tr>
				<tr>
					<td><input type="text" name='participant_name'></td>
				</tr>

				<tr>
					<td><br>Age</td>
				</tr>
				<tr>
					<td><input type="text" name='age' ></td>
				</tr>



				<tr>
					<td><br>Gender</td>
				
				
				<tr>
					<td><input type="radio" name='gender' value="m" checked> Male <input
						type="radio" name='gender' value="f"> Female</td>
				</tr>



				<tr>
					<td><br>
					<input type="Submit" class="btn btn-default btn-lg"></td>
				</tr>

				<tr>
					<td>&nbsp;</td>
				</tr>

			</table>

		</form>

	</div>
</body>
