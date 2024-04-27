<?php
session_start();
session_unset();
session_destroy();
header("Location: ../View/Signin_user.php");
exit;
?>