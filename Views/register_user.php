<?php
require_once '/var/www/html/Controllers/RegisterController.php';
?>

<html>
    <head>
        <title>Register page</title>
        <link rel="stylesheet" href="Css/login.css">
        <body>
        <nav>
        <a href="../index.php">Home</a>
        </nav>
            <div class="container">
            <form id="registration" action="../Controllers/RegisterController.php" method="POST">
            <label class="column" for="username">Username :</label>
            <input class="column1" type="text" id="username" name="username">
            <span id="usernameError" class="error"></span>
            <br>
            <label class="column" for="email">Email :</label> 
            <input class="column1" type="email" id="email" name="email">
            <span id="emailError" class="error"></span>
            <br>
            <label class="column" for="password">Password :</label>
            <input class="column1" type="Password" id="password" name="password">
            <span id="passwordError" class="error"></span>
            <br>
            <label class="column" for="confirmpassword">Confirm Password :</label>
            <input class="column1" type="Password" id="confirmpassword" name="confirmpassword">
            <span id="confirmpasswordError" class="error"></span>
            
            <button id="register" type="submit">Register</button>
           
            <script src="../Javascript/register_user.js"></script>
            </div>
         </form>
        </body>
    </head>
</html>

