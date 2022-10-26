<body>
<a href="Home.php">Home</a>
<?php
session_start();
if(isset($_SESSION['ID'])){
	try{
		$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec('SET NAMES "utf8"');
	}
	catch(PDOException $e){
		echo $e->getMessage();
		exit();
	}
	$sql = 'SELECT * FROM Users WHERE UserID = :UserID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':UserID', $_SESSION['ID']);
	$stmt->execute();
	$test = $stmt->fetch(PDO::FETCH_ASSOC);
	if($test['IsAdmin']){
		?><a href="Admin.php">Add movie</a><?php
	}
	?><a href="LogOut.php">Log out</a><?php
}
else{
	?><a href="LogIn.php">Log in</a><?php
}
?>
<br>
</body>