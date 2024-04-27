<?php
session_start();
if(!isset($_SESSION['username']) && !isset($_SESSION['newimage']) && !isset($_SESSION['email'])) {
    header("location: ../index.php");
    exit;
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome page</title>
    <link rel="stylesheet" href="Css/update.css">
</head>
<body>
<nav>
    <a href="../Views/logout_user.php">Log out</a>
    <a href="user_profile.php">Update profile</a>
</nav>
<h2>Welcome <?php echo $_SESSION['username']; ?></h2>
<form>
    <?php
    if(isset($_SESSION['newimage'])) {
        $imagePath = $_SESSION['newimage'];
        echo "<img src='../upload_image/$imagePath' class='user-image' alt='User Image '>";
    } else {
        echo "<img src='../Image/no_profile.jpg' class='user-image' alt='User Image'>";
    }
    ?>
    <br><br>
    <label>Email: <?php echo $_SESSION['email']."<br>";?></label>
    <br>
    <label>Username :  <?php echo $_SESSION['username'] ?></label>
</form>

</body>
</html>
