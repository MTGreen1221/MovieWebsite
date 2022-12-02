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
$sql = 'SELECT * FROM Users WHERE UserID = :ID';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':ID', $_GET['ID']);
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);
$sql = 'UPDATE Users SET AboutMe = :Pic WHERE UserID = :ID';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Pic', $_POST['AboutMe']);
$stmt->bindParam (':ID', $_GET['ID']);
$stmt->execute();
header ('Location:User.php?ID='.$_SESSION['ID']);
exit ();
?>