<html>
<head><title>Recomend</title></head>

<body>
<?php
require ('Header.php');
if (isset($_SESSION['ID'])){
	$sql = 'SELECT MovieID, UserID, COUNT(*) AS count FROM ratings WHERE Rating = 5 GROUP By MovieID, UserID';
	$stmt = $pdo->prepare ($sql);
	$stmt->execute();
	$recomend = array();
	while($test = $stmt->fetch()){
		$sql = 'SELECT * FROM friends WHERE UserID = :ID AND Status = 1 OR UserID2 = :ID AND Status = 1';
		$stmt2 = $pdo->prepare ($sql);
		$stmt2->bindParam (':ID', $_SESSION['ID']);
		$stmt2->execute();
		while($test2 = $stmt2->fetch()){
			if($test2['UserID'] == $_SESSION['ID']){
				$ID = $test2['UserID2'];
			}
			else{
				$ID = $test2['UserID'];
			}
			if($test['UserID'] == $ID){
				array_push($recomend, $test['MovieID']);
			}
		}
	}
	if(count($recomend) > 0){
		for($i = 0; $i < count($recomend); $i++){
			$sql = 'SELECT MovieID, COUNT(*) AS count2 FROM ratings WHERE MovieID = :ID';
			$stmt = $pdo->prepare ($sql);
			$stmt->bindParam (':ID', $recomend[$i]);
			$stmt->execute();
			$movie = $stmt->fetch(PDO::FETCH_ASSOC);
			$recomend[$i] = array($movie['MovieID'], $movie['count2']);
		}
		$sorted = false;
		while ($sorted == false){
			$check = 0;
			for ($i = 0; $i < count($recomend); $i++){
				$value = $recomend[$i];
				if($i+1 < count($recomend)){
					$value2 = $recomend[$i+1];
					if($value[1] < $value2[1]){
						$recomend[$i] = $value2;
						$recomend[$i+1]=$value;
						$check++;
					}
				}
				if($check == 0){
					$sorted = true;
				}
			}
		}
		if(count($recomend) > 10){
			for($i = 0; $i < 10; $i++){
				$value = $recomend[$i];
				$sql = 'SELECT * FROM movies WHERE MovieID = :ID';
				$stmt = $pdo->prepare ($sql);
				$stmt->bindParam (':ID', $value[0]);
				$stmt->execute();
				$return = $stmt->fetch(PDO::FETCH_ASSOC);
				$name = $return['Name'];
				$web = 'Movie.php?ID='.$value[0];
				?><a href= <?php echo $web ?>><?php echo $name ?></a><br><?php
			}
		}
		else{
			for($i = 0; $i < count($recomend); $i++){
				$value = $recomend[$i];
				$sql = 'SELECT * FROM movies WHERE MovieID = :ID';
				$stmt = $pdo->prepare ($sql);
				$stmt->bindParam (':ID', $value[0]);
				$stmt->execute();
				$return = $stmt->fetch(PDO::FETCH_ASSOC);
				$name = $return['Name'];
				$web = 'Movie.php?ID='.$value[0];
				?><a href= <?php echo $web ?>><?php echo $name ?></a><br><?php
			}
		}
	}
}
else{
	echo "What are you doing here? How did you get here? Go log in first";
}
?>

</body>
</html>