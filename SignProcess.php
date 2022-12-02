<?php
if(strcmp($_POST['Email'],"") == 0 || strcmp($_POST['Username'],"") == 0 || strcmp($_POST['Password'],"") == 0 || strcmp($_POST['Password2'],"") == 0){
	header ('Location:SignIn.php?error=0');
	exit ();
}
if(strcmp($_POST['Password'],$_POST['Password2'])!=0){
	header ('Location:SignIn.php?error=3');
	exit ();
}
try{
	$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
	echo $e->getMessage();
	exit();
}
$sql = 'SELECT * FROM Users WHERE Email = :Email';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Email', $_POST['Email']);
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);
if($test){
	header ('Location:SignIn.php?error=1');
	exit ();
}
$sql = 'SELECT * FROM Users WHERE Username = :Username';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Username', $_POST['Username']);
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);
if($test){
	header ('Location:SignIn.php?error=2');
	exit ();
}
if ($_FILES['upload']['error'] > 0){
	header ('Location:Admin.php?error=4');
	exit ();
}
$FileName = $_FILES['upload']['name'];
$finfo = new finfo (FILEINFO_MIME_TYPE);
$ftype = $finfo->file ($_FILES['upload']['tmp_name']);
if (strcmp($ftype, "image/jpg") != 0 && strcmp($ftype, "image/jpeg") != 0 && strcmp($ftype, "image/png") != 0){
	header ('Location:Admin.php?error=5');
	exit ();
}
if ($ftype == "image/jpeg"){
	$newName = $_POST['Email'].$_POST['Username'].".jpeg";
}
if ($ftype == "image/jpg"){
	$newName = $_POST['Email'].$_POST['Username'].".jpg";
}
if ($ftype == "image/png"){
	$newName = $_POST['Email'].$_POST['Username'].".png";
}
$FILE_DIR = "C:\UniServerZ\www\\test\pictures\\";
move_uploaded_file ($_FILES['upload']['tmp_name'], $FILE_DIR.$newName);
$encryptedPass = password_hash($_POST['Password'],PASSWORD_BCRYPT);
$sql = 'INSERT INTO Users (Email, Username, Password, ProfilePicture) VALUES (:Email, :Username, :Password, :ProfilePicture)';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Email', $_POST['Email']);
$stmt->bindParam (':Username', $_POST['Username']);
$stmt->bindParam (':Password', $encryptedPass);
$stmt->bindParam (':ProfilePicture', $newName);
$stmt->execute();
header ('Location:LogIn.php');
exit ();
?>