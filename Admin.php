<html>
<head><title>Add movie</title></head>
<body>
<?php
require ('Header.php');
?>
<form action="AdminProcess.php" enctype="multipart/form-data" method="POST">
<input type="hidden" name="hiddenvalue" value="foo">
Name: <input type="text" name="Name"><br>
Release year: <input type="text" name="ReleaseY"><br>
Release month: <input type="text" name="ReleaseM"><br>
Description: <input type="text" name="Description"><br>
Poster: <input type="file" name="upload" accept=".jpg, .png"><br>
Main cast:<input type="text" name="Crew1"><br>
Role:<input type="text" name="Role1"><br>
(optional) Secondary cast:<input type="text" name="Crew2"><br>
(optional) Role:<input type="text" name="Role2"><br>
(optional) Secondary cast:<input type="text" name="Crew3"><br>
(optional) Role:<input type="text" name="Role3"><br>
(optional) Secondary cast:<input type="text" name="Crew4"><br>
(optional) Role:<input type="text" name="Role4"><br>
(optional) Secondary cast:<input type="text" name="Crew5"><br>
(optional) Role:<input type="text" name="Role5"><br>
(optional) Secondary cast:<input type="text" name="Crew6"><br>
(optional) Role:<input type="text" name="Role6"><br>
<input type="submit" value="Submit">
</form>
<h1>Note: please only upload .jpg or .pdf files for posters</h1>
<?php
if(isset($_GET["error"])){
	if($_GET["error"]==0){
		echo "all fields must be filled";
	}
	if($_GET["error"]==1){
		echo "Movie already in database";
	}
	if($_GET["error"]==2){
		echo "not a valid release month";
	}
	if($_GET["error"]==3){
		echo "not a valid release year";
	}
	if($_GET["error"]==4){
		echo "Invalid file upload";
	}
	if($_GET["error"]==5){
		echo "Invalid file type";
	}
}
?>

</body>

</html>