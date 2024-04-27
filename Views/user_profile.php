<?php
require_once "/var/www/html/Controllers/UpdateController.php";
session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Image Upload</title>
  <link rel="stylesheet" href="Css/updateProfile.css">
</head>
<body>
    <h2>Update Profile</h2>
    <form action="../Controllers/UpdateController.php" method="post" enctype="multipart/form-data">
    <label class="column" for="username">Username : </label>
        <input class="column1" type="text" name="newUsername" id="newUsername" value="<?php session_start(); echo $_SESSION['username'] ?>" ><br>
        <label class="column" for="email">Email : </label>
        <input class="column1" type="text" name="email" id="email" value="<?php session_start(); echo $_SESSION['email'] ?>" readonly ><br><br>
        <label class="column" for="image">Upload:</label>
        <input type="file" class="image" name="image"><br><br>
        <input type="submit" name="submit" value="Upload">
    </form>
    <form action="../Controllers/RemoveUserController.php" method="post">
    <input type="submit" class="profile" name="delete" value="DELETE MY ACCOUNT">
    </form>   
</body>
</html>


