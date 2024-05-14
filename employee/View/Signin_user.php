<?php
require_once '/var/www/html/employee/Controller/Authenticate.php';
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("Location: ../View/Dashboard.php"); 
    exit; 
}
if(isset($_SESSION['error'])) {
    echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']); 
}

?>
<html>
    <head>
        <title>Signin page</title>
        <link rel="stylesheet" href="css/signin.css"> 
    </head>
        <body class="login">
       
            <div class="container">
            <form id="formlogin" action="../Controller/Authenticate.php" method="post">
            <input type="hidden" name="action" value="login">
            <label class = "column1" for="email">Email :</label>
            <input class = "column2" type="email" id="loginemail" name="email">
            <span id="loginemailError" class="error"></span>
            <br>
            <label class = "column1" for="password">Password :</label>
            <input class = "column2" type="Password" id="loginpassword" name="password">
            <span id="loginpasswordError" class="error"></span>
            <br>
            <button type="submit" id="login">Sign In</button>
            <p>Dont have an account ? <a href="Signup_user.php">Sign Up</a></p>
            <script src="../javascript/signin.js"></script>
            </form>
            </div>
        </body>
        </html>
