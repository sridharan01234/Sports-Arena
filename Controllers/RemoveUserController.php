<?php
session_start();
require_once '/var/www/html/Models/UserModel.php';

class UserRemoveController 
{

    /**
     * UserRemoveController class for handling user removal.
     * Check if the request method is POST.
     * Check if the user is logged in.
     * Attempt to remove the user from the database from current session user.
     * If user removal is successful destroy the session then redirect to the index page.
     * If user is not logged in, redirect to the index page.
     */
    public function removeUser() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) 
            {
                $userModel = new UserModel();
                $email = $_SESSION['email']; 
                $success = $userModel->removeUser($email);

                if ($success) 
                {
                    session_destroy();
                    header("Location:../index.php");
                    exit;
                } else {
                    $errorMessage = "Error occurred while removing user.";
                    header("Location:../Views/error.php?message=" . urlencode($errorMessage));
                    exit;
                  }
            } else {
                  header("Location:../index.php");
                  exit;
              }
        }
    }
}

$controller = new UserRemoveController();
$controller->removeUser();
?>
