<?php
session_start();
if(isset($_SESSION['ID'])){
	try{
		$pdo = new PDO('mysql:host=localhost;dbname=test','Bob','bob');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec('SET NAMES "utf8"');
	}
	catch(PDOException $e){
		echo $e->getMessage();
		exit();
	}
	$sql = 'SELECT * FROM Users WHERE UserID = :UserID';
	$stmt = $pdo->prepare ($sql);
	$stmt->bindParam (':UserID', $_SESSION['ID']);
	$stmt->execute();
	$test = $stmt->fetch(PDO::FETCH_ASSOC);
	if($test['IsAdmin']){
		if(strcmp($_POST['Name'],"") == 0 || strcmp($_POST['ReleaseY'],"") == 0 || strcmp($_POST['ReleaseM'],"") == 0 || strcmp($_POST['Description'],"") == 0 || strcmp($_POST['Crew1'],"") == 0 || strcmp($_POST['Role1'],"") == 0){
			header ('Location:Admin.php?error=0');
			exit ();
		}
		$Month = 0;
		if($_POST['ReleaseM'] == "Jan" || $_POST['ReleaseM'] == "January" || $_POST['ReleaseM'] == "jan" || $_POST['ReleaseM'] == "january" || $_POST['ReleaseM'] == "1" || $_POST['ReleaseM'] == "01"){
			$Month = 1;
		}
		if($_POST['ReleaseM'] == "Feb" || $_POST['ReleaseM'] == "Febuary" || $_POST['ReleaseM'] == "feb" || $_POST['ReleaseM'] == "febuary" || $_POST['ReleaseM'] == "2" || $_POST['ReleaseM'] == "02"){
			$Month = 2;
		}
		if($_POST['ReleaseM'] == "Mar" || $_POST['ReleaseM'] == "March" || $_POST['ReleaseM'] == "mar" || $_POST['ReleaseM'] == "march" || $_POST['ReleaseM'] == "3" || $_POST['ReleaseM'] == "03"){
			$Month = 3;
		}
		if($_POST['ReleaseM'] == "Apr" || $_POST['ReleaseM'] == "April" || $_POST['ReleaseM'] == "apr" || $_POST['ReleaseM'] == "april" || $_POST['ReleaseM'] == "4" || $_POST['ReleaseM'] == "04"){
			$Month = 4;
		}
		if($_POST['ReleaseM'] == "May" || $_POST['ReleaseM'] == "may" || $_POST['ReleaseM'] == "5" || $_POST['ReleaseM'] == "05"){
			$Month = 5;
		}
		if($_POST['ReleaseM'] == "June" || $_POST['ReleaseM'] == "june" || $_POST['ReleaseM'] == "6" || $_POST['ReleaseM'] == "06"){
			$Month = 6;
		}
		if($_POST['ReleaseM'] == "July" || $_POST['ReleaseM'] == "july" || $_POST['ReleaseM'] == "7" || $_POST['ReleaseM'] == "07"){
			$Month = 7;
		}
		if($_POST['ReleaseM'] == "Aug" || $_POST['ReleaseM'] == "August" || $_POST['ReleaseM'] == "aug" || $_POST['ReleaseM'] == "august" || $_POST['ReleaseM'] == "8" || $_POST['ReleaseM'] == "08"){
			$Month = 8;
		}
		if($_POST['ReleaseM'] == "Sept" || $_POST['ReleaseM'] == "September" || $_POST['ReleaseM'] == "sept" || $_POST['ReleaseM'] == "september" || $_POST['ReleaseM'] == "9" || $_POST['ReleaseM'] == "09"){
			$Month = 9;
		}
		if($_POST['ReleaseM'] == "Oct" || $_POST['ReleaseM'] == "October" || $_POST['ReleaseM'] == "oct" || $_POST['ReleaseM'] == "october" || $_POST['ReleaseM'] == "10"){
			$Month = 10;
		}
		if($_POST['ReleaseM'] == "Nov" || $_POST['ReleaseM'] == "November" || $_POST['ReleaseM'] == "nov" || $_POST['ReleaseM'] == "november" || $_POST['ReleaseM'] == "11"){
			$Month = 11;
		}
		if($_POST['ReleaseM'] == "Dec" || $_POST['ReleaseM'] == "Decebmer" || $_POST['ReleaseM'] == "dec" || $_POST['ReleaseM'] == "december" || $_POST['ReleaseM'] == "12"){
			$Month = 12;
		}
		if($Month == 0 || $Month > date("m")){
			header ('Location:Admin.php?error=2');
			exit ();
		}
		$Year = 0;
		if($_POST['ReleaseY'] > 1890 && $_POST['ReleaseY'] <= date("Y")){
			$Year = $_POST['ReleaseY'];
		}
		if($Year < 1890 || $Year > date("Y")){
			header ('Location:Admin.php?error=3');
			exit ();
		}
		$ReleaseDate = $Month.$Year;
		$sql = 'SELECT * FROM Movies WHERE Name = :Name';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':Name', $_POST['Name']);
		$stmt->execute();
		foreach ($stmt as $row){
			if($row['ReleaseDate'] == $ReleaseDate){
				header ('Location:Admin.php?error=1');
				exit ();
			}
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
			$newName = $_POST['Name'].$ReleaseDate.".jpeg";
		}
		if ($ftype == "image/jpg"){
			$newName = $_POST['Name'].$ReleaseDate.".jpg";
		}
		if ($ftype == "image/png"){
			$newName = $_POST['Name'].$ReleaseDate.".png";
		}
		$FILE_DIR = 'C:\UniServerZ\www\test\Images\ ';
		move_uploaded_file ($_FILES['upload']['tmp_name'], $FILE_DIR.$newName);
		
		$sql = 'INSERT INTO Movies (Name, ReleaseDate, Description, Poster) VALUES (:Name, :ReleaseDate, :Description, :Poster)';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':Name', $_POST['Name']);
		$stmt->bindParam (':ReleaseDate', $ReleaseDate);
		$stmt->bindParam (':Description', $_POST['Description']);
		$stmt->bindParam (':Poster', $newName);
		$stmt->execute();
		$sql = 'INSERT INTO Crew (Name, Role) VALUES (:Name, :Role)';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':Name', $_POST['Crew1']);
		$stmt->bindParam (':Role', $_POST['Role1']);
		$stmt->execute();
		if($_POST['Crew2'] != "" && $_POST['Role2'] != ""){
			$stmt->bindParam (':Name', $_POST['Crew2']);
			$stmt->bindParam (':Role', $_POST['Role2']);
			$stmt->execute();
			if($_POST['Crew3'] != "" && $_POST['Role3'] != ""){
				$stmt->bindParam (':Name', $_POST['Crew3']);
				$stmt->bindParam (':Role', $_POST['Role3']);
				$stmt->execute();
				if($_POST['Crew4'] != "" && $_POST['Role4'] != ""){
					$stmt->bindParam (':Name', $_POST['Crew4']);
					$stmt->bindParam (':Role', $_POST['Role4']);
					$stmt->execute();
					if($_POST['Crew5'] != "" && $_POST['Role5'] != ""){
						$stmt->bindParam (':Name', $_POST['Crew5']);
						$stmt->bindParam (':Role', $_POST['Role5']);
						$stmt->execute();
						if($_POST['Crew6'] != "" && $_POST['Role6'] != ""){
							$stmt->bindParam (':Name', $_POST['Crew6']);
							$stmt->bindParam (':Role', $_POST['Role6']);
							$stmt->execute();
						}
					}
				}
			}
		}
		$sql = 'SELECT * FROM Movies WHERE Name = :Name';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':Name', $_POST['Name']);
		$stmt->execute();
		foreach ($stmt as $row){
			if($row['ReleaseDate'] == $ReleaseDate){
				$MovieID = $row['MovieID'];
			}
		}
		$sql = 'SELECT * FROM Crew WHERE Name = :Name';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':Name', $_POST['Crew1']);
		$stmt->execute();
		foreach ($stmt as $row){
			if($row['Role'] == $_POST["Role1"]){
				$CrewID1 = $row['CrewID'];
			}
		}
		if($_POST['Crew2'] != "" && $_POST['Role2'] != ""){
			$stmt->bindParam (':Name', $_POST['Crew2']);
			$stmt->execute();
			foreach ($stmt as $row){
				if($row['Role'] == $_POST["Role2"]){
					$CrewID2 = $row['CrewID'];
				}
			}
			if($_POST['Crew3'] != "" && $_POST['Role3'] != ""){
				$stmt->bindParam (':Name', $_POST['Crew3']);
				$stmt->execute();
				foreach ($stmt as $row){
					if($row['Role'] == $_POST["Role3"]){
						$CrewID3 = $row['CrewID'];
					}
				}
				if($_POST['Crew4'] != "" && $_POST['Role4'] != ""){
					$stmt->bindParam (':Name', $_POST['Crew4']);
					$stmt->execute();
					foreach ($stmt as $row){
						if($row['Role'] == $_POST["Role4"]){
							$CrewID4 = $row['CrewID'];
						}
					}
					if($_POST['Crew5'] != "" && $_POST['Role5'] != ""){
						$stmt->bindParam (':Name', $_POST['Crew5']);
						$stmt->execute();
						foreach ($stmt as $row){
							if($row['Role'] == $_POST["Role5"]){
								$CrewID5 = $row['CrewID'];
							}
						}
						if($_POST['Crew6'] != "" && $_POST['Role6'] != ""){
							$stmt->bindParam (':Name', $_POST['Crew6']);
							$stmt->execute();
							foreach ($stmt as $row){
								if($row['Role'] == $_POST["Role6"]){
									$CrewID6 = $row['CrewID'];
								}
							}
						}
					}
				}
			}
		}
		$sql = 'INSERT INTO Credits (MovieID, CrewID) VALUES (:MovieID, :CrewID)';
		$stmt = $pdo->prepare ($sql);
		$stmt->bindParam (':MovieID', $MovieID);
		$stmt->bindParam (':CrewID', $CrewID1);
		$stmt->execute();
		if($_POST['Crew2'] != "" && $_POST['Role2'] != ""){
			$stmt->bindParam (':MovieID', $MovieID);
			$stmt->bindParam (':CrewID', $CrewID2);
			$stmt->execute();
			if($_POST['Crew3'] != "" && $_POST['Role3'] != ""){
				$stmt->bindParam (':MovieID', $MovieID);
				$stmt->bindParam (':CrewID', $CrewID3);
				$stmt->execute();
				if($_POST['Crew4'] != "" && $_POST['Role4'] != ""){
					$stmt->bindParam (':MovieID', $MovieID);
					$stmt->bindParam (':CrewID', $CrewID4);
					$stmt->execute();
					if($_POST['Crew5'] != "" && $_POST['Role5'] != ""){
						$stmt->bindParam (':MovieID', $MovieID);
						$stmt->bindParam (':CrewID', $CrewID5);
						$stmt->execute();
						if($_POST['Crew6'] != "" && $_POST['Role6'] != ""){
							$stmt->bindParam (':MovieID', $MovieID);
							$stmt->bindParam (':CrewID', $CrewID6);
							$stmt->execute();
						}
					}
				}
			}
		}
		header ('Location:Home.php');
		exit ();
	}
	else{
		echo "Nice try hacker";
	}
}
else{
		echo "Nice try hacker";
	}

?>