<?php
try{
	$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
	echo $e->getMessage();
	exit();
}
if(strcmp($_POST['Username'],"") == 0 || strcmp($_POST['Password'],"") == 0){
	header ('Location:LogIn.php?error=0');
	exit ();
}
$sql = 'SELECT * FROM Users WHERE Username = :Username';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':Username', $_POST['Username']);
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$test){
	header ('Location:LogIn.php?error=1');
	exit ();	
}
if(password_verify($_POST['Password'], $test['Password'])){
	session_start();
	session_regenerate_id(true);
	$_SESSION['ID'] = $test['UserID'];
	$_SESSION['Name'] = $test['Username'];
	header ('Location:Home.php');
	exit ();
}
else{
	header ('Location:LogIn.php?error=2');
	exit ();
}
?>