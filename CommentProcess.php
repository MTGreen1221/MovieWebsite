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
if(strcmp($_POST['Comment'],"") == 0){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=1');
	exit ();
}
$sql = 'INSERT INTO Comments (Comment, UserID, MovieID, AddTime) VALUES (:Comment, :UserID, :MovieID, NOW())';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Comment', $_POST['Comment']);
$stmt->bindParam (':UserID', $_SESSION['ID']);
$stmt->bindParam (':MovieID', $_SESSION['MovieVisited']);
$stmt->execute();
header ('Location:Movie.php?ID='.$_SESSION['MovieVisited']);
exit ();
?>