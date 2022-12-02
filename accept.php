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
$sql = 'UPDATE Friends SET Status = 1 WHERE UserID = :ID AND UserID2 = :ID2';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':ID', $_POST['ID']);
$stmt->bindParam (':ID2', $_SESSION['ID']);
$stmt->execute();

header ('Location:Home.php');
exit ();
?>