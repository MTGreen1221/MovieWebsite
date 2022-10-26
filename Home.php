<html>
<head><title>Home</title></head>

<body>
<?php
require ('Header.php');
?>
<h1>Home page</h1>
<?php
if(isset($_SESSION['Name'])){
	echo "Welcome ".$_SESSION['Name'];
}
?>
</body>
</html>