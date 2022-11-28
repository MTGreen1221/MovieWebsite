<html>
<head><title>Home</title></head>

<body>
<?php
require ('Header.php');
?>
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
?>


</body>
</html>