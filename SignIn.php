<html>
<head><title>Sign in</title></head>
<body>
<?php
require ('Header.php');
?>
<form action="SignProcess.php" method="POST">
<input type="hidden" name="hiddenvalue" value="foo">
Email: <input type="text" name="Email">
Username: <input type="text" name="Username">
Password: <input type="text" name="Password">
Confirm Password: <input type="text" name="Password2">
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
}
?>

</body>

</html>