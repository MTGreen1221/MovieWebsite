<html>
<head><title>Home</title></head>

<body>
<?php
require ('Header.php');
?>
<form action="Search.php" method="POST">
Search for a movie?:<input type="text" name="title">
<input type="submit" value="Search">
</form>
<h1>Home page</h1>
<?php
if(isset($_SESSION['Name'])){
	echo "Welcome ".$_SESSION['Name'];
}
if(isset($_GET["error"])){
	if($_GET["error"]==1){
		echo "only admins can do that action";
	}
}
?>
<br>
<h1>Recent additions</h1>
<?php
$sql = 'SELECT * FROM Movies ORDER BY AddDate DESC LIMIT 10';
$stmt = $pdo->prepare ($sql);
$stmt->execute();
while($test = $stmt->fetch()){
	if($test){
		$title = $test['Name'];
		$webtitle = "Movie.php?ID=".$test['MovieID'];
		?>
		<a href=<?php echo $webtitle ?>><?php echo $title ?><br></a>
		<?php
	}
}
$sql = 'SELECT * FROM Friends WHERE UserID = :ID or UserID2 = :ID';
$stmt = $pdo->prepare ($sql);
$stmt->bindParam (':ID', $_SESSION['ID']);
$stmt->execute();
$pending = array();
$friends = array();
while($test = $stmt->fetch()){
	if($test['Status'] == 0 && $test['UserID2'] == $_SESSION['ID']){
		array_push($pending, $test['UserID']);
	}
	else{
		if($test['UserID'] == $_SESSION['ID']){
			array_push($friends, $test['UserID2']);
		}
		if($test['UserID2'] == $_SESSION['ID']){
			array_push($friends, $test['UserID']);
		}
	}
}
?>
<h1>Recent comments from friends</h1> 
<?php
if(count($friends) > 0){
	$sort = array();
	for($i = 0; $i <= sizeof($friends)-1; $i++){
		$sql = 'SELECT * FROM Comments WHERE UserID = :ID ORDER BY AddTime LIMIT 10';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $friends[$i]);
		$stmt->execute();
		while($test2 = $stmt->fetch()){
			if($test2){
				array_push($sort, array($test2['UserID'], $test2['Comment'], $test2['AddTime'], $test2['MovieID']));
			}
		}
	}
	$sorted = false;
	while ($sorted == false){
		$check = 0;
		for ($i = 0; $i <= count($sort)-1; $i++){
			$value = $sort[$i];
			if($i+1 <= count($sort)-1){
				$value2 = $sort[$i+1];
				if($value[2] < $value2[2]){
					$sort[$i] = $value2;
					$sort[$i+1]=$value;
					$check++;
				}
			}
			if($check == 0){
				$sorted = true;
			}
		}
	}
	if(count($sort) > 10){
		for ($i = 0; $i <= 9; $i++){
			$value = $sort[$i];
			$sql = 'SELECT * FROM Movies WHERE MovieID = :ID';
			$stmt = $pdo->prepare ($sql);
			$stmt->bindParam (':ID', $value[3]);
			$stmt->execute();
			$title = $stmt->fetch(PDO::FETCH_ASSOC);
			$webtitle = "Movie.php?ID=".$title['MovieID'];
			?><a href=<?php echo $webtitle?>><?php echo $title['Name']?></a><?php
			?><br><?php
			$sql = 'SELECT * FROM Users WHERE UserID = :ID';
			$stmt = $pdo->prepare ($sql);
			$stmt->bindParam (':ID', $value[0]);
			$stmt->execute();
			$name = $stmt->fetch(PDO::FETCH_ASSOC);
			$n = $name['Username'];
			$website = "User.php?ID=".$name['UserID'];
			?><a href=<?php echo $website ?>><?php echo $n ?></a><?php
			echo ": ".$value[1];
			?><br><?php
			echo " comment added: ".$value[2];
			?><br><br><?php  
		}
	}
	else{
		for ($i = 0; $i <= count($sort)-1; $i++){
			$value = $sort[$i];
			$sql = 'SELECT * FROM Movies WHERE MovieID = :ID';
			$stmt = $pdo->prepare ($sql);
			$stmt->bindParam (':ID', $value[3]);
			$stmt->execute();
			$title = $stmt->fetch(PDO::FETCH_ASSOC);
			$webtitle = "Movie.php?ID=".$title['MovieID'];
			?><a href=<?php echo $webtitle?>><?php echo $title['Name']?></a><?php
			?><br><?php
			$sql = 'SELECT * FROM Users WHERE UserID = :ID';
			$stmt = $pdo->prepare ($sql);
			$stmt->bindParam (':ID', $value[0]);
			$stmt->execute();
			$name = $stmt->fetch(PDO::FETCH_ASSOC);
			$n = $name['Username'];
			$website = "User.php?ID=".$name['UserID'];
			?><a href=<?php echo $website ?>><?php echo $n ?></a><?php
			echo ": ".$value[1];
			?><br><?php
			echo " comment added: ".$value[2];
			?><br><br><?php 
		}
	}
}
?>
<h1>Pending friend requests</h1>
<?php 
for($i = 0; $i <= sizeof($pending)-1; $i++){
	$sql = 'SELECT * FROM Users WHERE UserID = :ID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $pending[$i]);
	$stmt->execute();
	$name = $stmt->fetch(PDO::FETCH_ASSOC);
	$n = $name['Username'];
	$website = "User.php?ID=".$n;
	?><a href=<?php echo $website ?>><?php echo $n ?>: </a>
	<form action="accept.php" method="POST">
	<input type="hidden" name="ID" value="<?php echo $name['UserID']?>">
	<input type="submit" value="accept">
	</form>
	<form action="deny.php" method="POST">
	<input type="hidden" name="ID" value="<?php echo $name['UserID']?>">
	<input type="submit" value="deny">
	</form>
	<?php
}

?>
<script type="text/javascript" src="jquery-3.6.1.min"></script>
<script type="text/javascript" src="script.js"></script>
</body>
</html>