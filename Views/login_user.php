<?php
require_once '/var/www/html/Controllers/LoginController.php';
?>

<html>
    <head>
        <title>Login page</title>
        <link rel="stylesheet" href="Css/login.css">
    </head>
        <body class="login">
        <nav>
        <a href="../index.php">Home</a>
        <a href="register_user.php">Register</a>
        </nav>
            <div class="container">
            <form id="formlogin" action="../Controllers/LoginController.php" method="post">
            <label class = "column" for="email">Email :</label>
            <input class = "column1" type="email" id="loginemail" name="email">
            <span id="loginemailError" class="error"></span>
            <br>
            <label class = "column" for="password">Password :</label>
            <input class = "column1" type="Password" id="loginpassword" name="password">
            <span id="loginpasswordError" class="error"></span>
            <br>
            <button type="submit" id="login">Log in</button>
            <script src="../Javascript/login_user.js"></script>
            </form>
            </div>
        </body>
        </html>
