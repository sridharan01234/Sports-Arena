<?php
session_start();
require_once '/var/www/html/employee/Model/UserModel.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class AuthController 
{
    public function login() 
    {   
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (empty($_POST['email']) || empty($_POST['password'])) 
            {
                $message = "Email and password are required !";
                $_SESSION['error'] = $message; 
                header("Location: ../View/Signin_user.php");
                exit();
            } else {
                $email = $_POST['email'];
                $password = $_POST['password'];
        
                $userModel = new User();
                $user = $userModel->getUserByEmailPassword($email, $password);
                
                if($user)
                {
                    $_SESSION['loggedin'] = true;
                    echo "Logged in";
                    header("Location: ../View/Dashboard.php");
                    exit();
                } else {
                    $message = "Invalid email or password !";
                    $_SESSION['error'] = $message; 
                    header("Location: ../View/Signin_user.php");
                    exit();
                }
            }
        }
    }    

    public function register() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) 
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            if ($this->isUserExist($email)) 
            {
                $message= "User with this email already exists !";
                $_SESSION['error'] = $message;
                header("Location:../View/Signup_user.php");  
                exit();
            } else {
                $this->saveUser($username, $password, $email);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $email;
                
                $message = "User registered successfully...";
                $_SESSION['error'] = $message;
                
                header("Location: ../View/Dashboard.php");
                exit();
            } 
        }
    }

    private function isUserExist($email)
    {
        $userModel = new User();
        $result = $userModel->getUserByEmail($email);

        return ($result !== null) ? true : false;
    }

    private function saveUser($username, $password, $email)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel = new User();
        $userModel->addUser($username, $hashedPassword, $email);
    } 
}

$controller = new AuthController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    switch ($_POST['action']) {
        case 'login':
            $controller->login();
            break;
        case 'register':
            $controller->register();
            break;
        default:
            $message = "Invalid action!";
    }
}