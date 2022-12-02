<html>
<head>
<title>Search</title>
</head>
<body>
<?php
require ('Header.php');
try{
	$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
	echo $e->getMessage();
	exit();
}?>
<form action="Search.php" method="POST">
Search for a movie?:<input type="text" name="title">
<input type="submit" value="Search">
</form>
<?php
if(isset($_POST['title'])){
	$term = '%'.$_POST['title'].'%';
	$sql = "SELECT * FROM Movies WHERE Name LIKE :title";
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':title', $term);
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
}
?>
</body>
</html>