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
        <title>Signup page</title>
        <link rel="stylesheet" href="css/signup.css">
        <body>
        <nav>
        <!-- <a href="../index.php">Home</a> -->
        </nav>
            <div class="container">
            <form id="signup" action="../Controller/Authenticate.php" method="POST">
            <input type="hidden" name="action" value="register">
            <label class="column1" for="username">Username :</label>
            <input class="column2" type="text" id="username" name="username">
            <span id="usernameError" class="error"></span>
            <br>
            <label class="column1" for="email">Email :</label> 
            <input class="column2" type="email" id="email" name="email">
            <span id="emailError" class="error"></span>
            <br>
            <label class="column1" for="password">Password :</label>
            <input class="column2" type="Password" id="password" name="password">
            <span id="passwordError" class="error"></span>
            <br>
            <label class="column1" for="confirmpassword">Confirm Password :</label>
            <input class="column2" type="Password" id="confirmpassword" name="confirmpassword">
            <span id="confirmpasswordError" class="error"></span>
            <br>
            <button id="register" type="submit">Register</button>
            <p>Do you want go to login page ? <a href="Signin_user.php"> Sign In</a></p>
            <script src="../javascript/signup.js"></script>
            </div>
         </form>
        </body>
    </head>
</html>

