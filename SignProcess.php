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
$encryptedPass = password_hash($_POST['Password'],PASSWORD_BCRYPT);
$sql = 'INSERT INTO Users (Email, Username, Password) VALUES (:Email, :Username, :Password)';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Email', $_POST['Email']);
$stmt->bindParam (':Username', $_POST['Username']);
$stmt->bindParam (':Password', $encryptedPass);
$stmt->execute();
header ('Location:LogIn.php');
exit ();
?>