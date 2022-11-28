<html>
<head><title>Log in</title></head>
<body>
<?php
require ('Header.php');
?>
<header>New here?</header>
<form action="SignIn.php" method="POST">
<input type="submit" value="Sign up">
</form>
<form action="LogProcess.php" method="POST">
<input type="hidden" name="hiddenvalue" value="foo">
Username: <input type="text" name="Username"><br>
Password: <input type="text" name="Password"><br>
<input type="submit" value="Submit">
</form>
</body>
<?php
if(isset($_GET["error"])){
	if($_GET["error"]==0){
		echo "all fields must be filled";
	}
	if($_GET["error"]==1){
		echo "Username does not exist";
	}
	if($_GET["error"]==2){
		echo "Incorrect password";
	}
}
?>

</html>