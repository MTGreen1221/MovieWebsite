<html>
<head><title>Edit</title></head>

<body>
<?php
require ('Header.php');
$website = "EditPicture.php?ID=".$_SESSION['ID'];
$website2 = "EditAboutMe.php?ID=".$_SESSION['ID'];
?>
<form action=<?php echo $website ?> enctype="multipart/form-data" method="POST">
Change Profile Picture?: <input type="file" name="upload" accept=".jpg, .png">
<input type="submit" value="Submit">
</form>
<form action=<?php echo $website2 ?> method="POST">
Change About Me?: <input type="text" name="AboutMe"><br>
<input type="submit" value="Submit">
</form>
</body>
</html>