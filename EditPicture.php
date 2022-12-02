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
$Name = $test['Username'];
$Email = $test['Email'];
$website = 'User.php?ID='.$_SESSION['ID'];
$ewebsite = $website."&error=1";
if ($_FILES['upload']['error'] > 0){
	header ('Location:'.$ewebsite);
	exit ();
}
$FileName = $_FILES['upload']['name'];
$finfo = new finfo (FILEINFO_MIME_TYPE);
$ftype = $finfo->file ($_FILES['upload']['tmp_name']);
if (strcmp($ftype, "image/jpg") != 0 && strcmp($ftype, "image/jpeg") != 0 && strcmp($ftype, "image/png") != 0){
	header ('Location:'.$ewebsite);
	exit ();
}
if ($ftype == "image/jpeg"){
	$newName = $test['Email'].$test['Username'].".jpeg";
}
if ($ftype == "image/jpg"){
	$newName = $test['Email'].$test['Username'].".jpg";
}
if ($ftype == "image/png"){
	$newName = $test['Email'].$test['Username'].".png";
}
$FILE_DIR = "C:\UniServerZ\www\\test\pictures\\";
move_uploaded_file ($_FILES['upload']['tmp_name'], $FILE_DIR.$newName);
$sql = 'UPDATE Users SET ProfilePicture = :Pic WHERE UserID = :ID';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Pic', $newName);
$stmt->bindParam (':ID', $_GET['ID']);
$stmt->execute();
header ('Location:User.php?ID='.$_SESSION['ID']);
exit ();
?>