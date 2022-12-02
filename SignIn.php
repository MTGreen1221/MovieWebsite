<html>
<head><title>Sign in</title></head>
<body>
<?php
require ('Header.php');
?>
<form action="SignProcess.php" method="POST">
Email: <input type="text" name="Email"><br>
Username: <input type="text" name="Username"><br>
Password: <input type="text" name="Password"><br>
Confirm Password: <input type="text" name="Password2"><br>
Profile Picture: <input type="file" name="upload" accept=".jpg, .png"><br>
<input type="submit" value="Submit">
</form>
<?php
if(isset($_GET["error"])){
	if($_GET["error"]==0){
		echo "all fields must be filled";
	}
	if($_GET["error"]==1){
		echo "email is already in use";
	}
	if($_GET["error"]==2){
		echo "Username is already in use";
	}
	if($_GET["error"]==3){
		echo "Passwords do not match";
	}
	if($_GET["error"]==4){
		echo "Please provide a profilr picture";
	}
}
?>

</body>

</html>