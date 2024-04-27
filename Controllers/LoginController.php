<?php
require_once '/var/www/html/Models/UserModel.php';

class LoginController 
{

   /**
   * Checks if the login form is submitted.
   * Validates email and password fields.
   * Retrieves user data based on provided email and password.
   * If a user is found, starts a session and redirects to the welcome page.
   * If no user is found, redirects back to the login page with an error message.
   * 
   * @return bool
   */
    public function login() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (empty($_POST['email']) || empty($_POST['password'])) 
            {
                $message = "Email and password are required.";
                header("Location: ../Views/login_user.php");
                exit();
            } else {
                  $email = $_POST['email'];
                  $password = $_POST['password'];
        
                  $userModel = new UserModel();
                  $user = $userModel->getUserByEmailPassword($email,$password);
                
                    if(!is_null($user))
                    {
                        session_start();
                        $username = $user['username'];
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;
                    
                        $userDetails = $userModel->userProfile($email);
                        $_SESSION['newimage'] = $userDetails;
                        $message = "Logged in";
                        header("Location: ../Views/welcome_user.php?error=".urlencode($message));
                        exit();
                    } else {
                          $message = "Invalid email or password.";
                          header("Location: ../Views/login_user.php?error=".urlencode($message));
                          exit();
                        }
                }
        }
    }    
}

$controller = new LoginController();
$controller->login();

?>