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
if(strcmp($_POST['Rating'],"") == 0){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
if($_POST['Rating'] < 1 || $_POST['Rating'] > 5){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
if(1 < $_POST['Rating'] && $_POST['Rating'] < 2){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
if(2 < $_POST['Rating'] && $_POST['Rating'] < 3){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
if(3 < $_POST['Rating'] && $_POST['Rating'] < 4){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
if(4 < $_POST['Rating'] && $_POST['Rating'] < 5){
	header ('Location:Movie.php?ID='.$_SESSION['MovieVisited'].'&error=0');
	exit ();
}
$sql = 'INSERT INTO Ratings (Rating, UserID, MovieID, AddTime) VALUES (:Rating, :UserID, :MovieID, NOW())';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Rating', $_POST['Rating']);
$stmt->bindParam (':UserID', $_SESSION['ID']);
$stmt->bindParam (':MovieID', $_SESSION['MovieVisited']);
$stmt->execute();
header ('Location:Movie.php?ID='.$_SESSION['MovieVisited']);
exit ();
?>