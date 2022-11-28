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
$sql = 'INSERT INTO Friends (UserID, UserID2, Status) VALUES (:UserID, :UserID2, 0)';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':UserID', $_SESSION['ID']);
$stmt->bindParam (':UserID2', $_POST["ID"]);
$stmt->execute();
header ('Location:Movie.php?ID='.$_SESSION['MovieVisited']);
exit ();
?>