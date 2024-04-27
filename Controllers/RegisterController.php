<?php
require_once '/var/www/html/Models/UserModel.php';

class UserController 
{

    /** 
     * Handles the registration form submission.
     * Checks if the user already exists. If not, register the user.
     * Starts a session upon successful registration.
     * 
     * @return bool
     */
    public function register() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            if ($this->isUserExist($username)) 
            {
                $message= "User with this email already exists.";
                header("Location:../Views/register_user.php?error=".urlencode($message));  
                exit();
            } else {
                $this->saveUser($username, $password, $email);
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
        
                $message = "User registered successfully.";
                header("Location: ../Views/welcome_user.php?error=".urlencode($message));
                exit();
                } 
        }
    }

    /**
     * Validate is user already exist.
     *
     * @param string $email
     *
     * @return bool
     */
    private function isUserExist(string $email): bool
    {
        $userModel = new UserModel();
        $result = $userModel->getUserByEmail($email);

        return ($result !== null) ? true : false;
    }

    /**
     * Validate is no user exist it allow to adding a new user
     * 
     * @param string $username
     * @param string $password
     * @param string $email
     * 
     * @return void
    */
    private function saveUser(string $username, string $password, string $email): void
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel = new UserModel();
        $userModel->addUser($username, $hashedPassword, $email);
    }
}

$controller = new UserController();
$controller->register();





















































// require_once '/var/www/html/mysite/Models/UserModel.php';

// class UserController {
    
//     public function register() {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $username = $_POST['username'];
//             $password = $_POST['password'];
//             $email = $_POST['email'];
//             $userModel = new UserModel();

//             if ($userModel->userExists($username, $email)) {
//                 $message = "User with this username or email already exists.";
//             } else {
//                 $success = $userModel->addUser($username, $password, $email);

//                 if ($success) {
//                     $message = "User registered successfully.";
//                     header("Location: ../Views/welcome.php");
//                 exit();
//                 } else {
//                     $message = "Error occurred while registering user.";
//                 }
//             }
//         }
//         include '../Views/register_view.php'; 
//     }

//     public function login() {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $email = $_POST['email'];
//             $password = $_POST['password'];

//             $userModel = new UserModel();

//             $user = $userModel->getUserByEmail($email);
//             if ($user && password_verify($password, $user['password'])) {
//                 header("Location: ../Views/welcome.php");
//                 exit();
//             } else {
//                 $message = "Invalid email or password.";
//             }
//         }
//         include '../Views/login_view.php'; 
//     }
//}


// $controller = new UserController();
// $controller->register();

// $controller = new UserController();
// $controller->login();
?>








