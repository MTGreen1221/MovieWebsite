<a href="Home.php">Home</a>
<?php
session_start();
try{
		$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec('SET NAMES "utf8"');
	}
	catch(PDOException $e){
		echo $e->getMessage();
		exit();
	}
if(isset($_SESSION['ID'])){
	$sql = 'SELECT * FROM Users WHERE UserID = :UserID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':UserID', $_SESSION['ID']);
	$stmt->execute();
	$test = $stmt->fetch(PDO::FETCH_ASSOC);
	$Userpage = "User.php?ID=".$test['UserID'];
	?><a href=<?php echo $Userpage ?>> <?php echo $test['Username'] ?> </a><?php
	if($test['IsAdmin']){
		?><a href="Admin.php"> Add movie </a><?php
	}
	?>
	<a href="Recomend.php"> Recomended </a>
	<a href="LogOut.php">Log out</a><?php
}
else{
	?><a href="LogIn.php">Log in</a><?php
}
?>
<br>