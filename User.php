<html>
<head><title>User</title></head>

<body>
<?php
require ('Header.php');
if(isset($_GET['ID'])){
	$sql = 'SELECT * FROM Users WHERE UserID = :ID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $_GET['ID']);
	$stmt->execute();
	$test = $stmt->fetch(PDO::FETCH_ASSOC);
	$Name = $test['Username']; 
	$description = $test['AboutMe'];
	$Poster = "http://localhost/test/pictures/" . $test['ProfilePicture'];
	?><img src="<?php echo $Poster?>" alt="profile picture">
	<?php if($_GET['ID'] == $_SESSION['ID']){?>
		<a href="EditProfile.php">Edit profile?</a>
	<?php }
	?><h1><?php echo $Name ?></h1>
	<h3>About Me</h3>
	<p><?php echo $description ?></p>
	<h3>Recent reviews</h3>
	<?php
	$sql = 'SELECT * FROM Ratings WHERE UserID = :ID ORDER BY AddTime DESC LIMIT 10';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $_GET['ID']);
	$stmt->execute();
	$films = array();
	while($test2 = $stmt->fetch()){
		array_push($films, array($test2['MovieID'], $test2['Rating']));
	}
	for($i = 0; $i <= count($films)-1;$i++){
		$value = $films[$i];
		$sql = 'SELECT * FROM Movies WHERE MovieID = :ID';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $value[0]);
		$stmt->execute();
		$title = $stmt->fetch(PDO::FETCH_ASSOC);
		echo $title['Name'];
		echo ": ".$value[1];
		?><br><?php
	}
	?>
	<h3>Friends</h3>
	<?php
	$sql = 'SELECT * FROM Friends WHERE UserID = :ID or UserID2 = :ID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $_SESSION['ID']);
	$stmt->execute();
	$friends = array();
	while($test = $stmt->fetch()){
		if($test['Status'] == 1){
			if($test['UserID'] == $_GET['ID']){
				array_push($friends, $test['UserID2']);
			}
			if($test['UserID2'] == $_GET['ID']){
				array_push($friends, $test['UserID']);
			}
		}
	}
	for($i = 0; $i <= sizeof($friends)-1; $i++){
		$sql = 'SELECT * FROM Users WHERE UserID = :ID';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $friends[$i]);
		$stmt->execute();
		$name = $stmt->fetch(PDO::FETCH_ASSOC);
		?><a href="User.php?ID=<?php echo $name['Username'] ?>"><?php echo $name['Username'] ?></a><?php
	}
}
?>
</body>
</html>