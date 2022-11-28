<html>
<head>
<title>Movie</title>
</head>
<body>
<?php
require ('Header.php');
if(isset($_GET['ID'])){
	$sql = 'SELECT * FROM Movies WHERE MovieID = :ID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $_GET['ID']);
	$stmt->execute();
	$test = $stmt->fetch(PDO::FETCH_ASSOC);
	$Title = $test['Name']; 
	$Poster = "http://localhost/test/pictures/" . $test['Poster'];
	?>
	<h1> <?php echo $Title; ?> </h1>
	<?php
	
	?>
	<img src ="<?php echo $Poster ?>" alt = "movie poster"><br>
	<?php
	$sql = 'SELECT AVG(Rating) FROM Ratings WHERE MovieID = :ID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':ID', $_GET['ID']);
	$stmt->execute();
	$test2 = $stmt->fetch(PDO::FETCH_ASSOC);
	$avgRate = $test2['AVG(Rating)'];
	if($avgRate == NULL){
		?>
		<p>You can be the first to rate this film<br></p>
		<?php 
	}else{
		?>
		Average Rating:<?php echo $avgRate ?><br>
		<?php 
	}
	if(isset($_SESSION['ID'])){
		$_SESSION['MovieVisited'] = $_GET['ID'];
		$sql = 'SELECT * FROM Ratings WHERE UserID = :ID AND MovieID = :MID';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $_SESSION['ID']);
		$stmt->bindParam (':MID', $_GET['ID']);
		$stmt->execute();
		$test3 = $stmt->fetch(PDO::FETCH_ASSOC);
		if($test3){
			$rate = $test3['Rating'];
			?>
			Your Rating:<?php echo $rate ?>
			<?php
			if(isset($_GET["error"])){
				if($_GET["error"]==0){
				?>
					<h1>please enter a whole number between 1 and 5</h1>
				<?php
				}
			}
		}else{
			?>
			<form action="RateProcess.php" method="POST">
			<input type="hidden" name="hiddenvalue" value="foo">
			Rating 1-5: <input type="text" name="Rating">
			<input type="submit" value="Submit">
			</form>
			<?php
		}
	}else{
		?>
		<p>please log in to add your rating <br></p>
		<?php
	}
	$Description = $test['Description']; ?>
	<p> <?php echo $Description; ?> </p>
	<?php
	if(isset($_SESSION['ID'])){
		?>
		<form action="CommentProcess.php" method="POST">
		<input type="hidden" name="hiddenvalue" value="foo">
		Add comment: <input type="text" name="Comment"><br>
		<input type="submit" value="Submit">
		</form>
		<?php
		if(isset($_GET["error"])){
			if($_GET["error"]==1){
				echo "please do not try to leave an empty comment";
			}
		}
	}else{
		?>
		<p>Log in to leave a comment</p>
		<?php
	}
	$sql = 'SELECT * FROM Comments WHERE MovieID = :MID ORDER BY AddTime DESC LIMIT 10';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':MID', $_GET['ID']);
	$stmt->execute();
	$a = array();
	while($test4 = $stmt->fetch()){
		array_push($a, $test4['UserID'], $test4['Comment']);
	}
	$index = 0;
	for($i = 0; $i <= sizeof($a)/2-1; $i++){
		$UserID = $a[$index];
		$sql = 'SELECT * FROM Users WHERE UserID = :ID';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $UserID);
		$stmt->execute();
		$test5 = $stmt->fetch(PDO::FETCH_ASSOC);
		$Username = $test5['Username'];
		$index++;
		$Comment = $a[$index];
		$sql = 'SELECT * FROM Friends WHERE UserID = :ID AND UserID2 = :ID2';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':ID', $UserID);
		$stmt->bindParam (':ID2', $_SESSION['ID']);
		$stmt->execute();
		$test6 = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->bindParam (':ID2', $UserID);
		$stmt->bindParam (':ID', $_SESSION['ID']);
		$stmt->execute();
		$test7 = $stmt->fetch(PDO::FETCH_ASSOC);
		?><p><?php echo $Username.':'.$Comment?></p><br><?php
		if(isset($_SESSION['ID']) && $UserID != $_SESSION['ID']){
			if($test6 == NULL && $test7 == NULL){
				?>
				<form action="FriendProcess.php" method="POST">
				<input type="hidden" name="ID" value= <?php echo $UserID?>>
				<input type="submit" value="Send friend request?">
				</form>
			<?php
			}
		}
		$index++;
	}
}else{ ?>
	<h1>Movie Title</h1>
	<p>poster here<br>
	rating<br>
	description<br>
	comments<p>
	<?php
}
?>
</body>

</html>
