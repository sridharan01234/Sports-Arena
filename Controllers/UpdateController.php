<?php
session_start();
require_once '/var/www/html/Models/UserModel.php';

class UpdateProfileController 
{
    public function updateUserProfile() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (!empty($_POST['newUsername'])) {
                $newUsername = $_POST['newUsername'];
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    $userModel = new UserModel();
                    $email = $_SESSION['email'];

                    $success = $userModel->updateUsername($newUsername, $email);

                    if (!$success) {
                        $errorMessage = "Error occurred while updating username.";
                        header("Location:../Views/error.php?message=" . urlencode($errorMessage));
                        exit;
                    } else {
                        $_SESSION['username'] = $newUsername;
                    }
                } else {
                    header("Location:../index.php");
                    exit;
                }
            } else {
                $errorMessage = "New username cannot be empty.";
                header("Location:../Views/error.php?message=" . urlencode($errorMessage));
                exit;
            }

            if (isset($_FILES['image'])) {
                //print_r($_FILES);
                $imageTmpName = $_FILES['image']['tmp_name']; 
                $fileName = $_FILES['image']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!empty($fileName) && in_array($fileExtension, ["jpg", "jpeg", "png"])) {
                    $location = '/var/www/html/mysite/upload_image/'; 
                    $destinationPath = $location . $fileName;

                    if (move_uploaded_file($imageTmpName, $destinationPath)) {
                        $userModel = new UserModel();
                        $email = $_SESSION['email'];
                        $username = $_SESSION['username'];
                        $success = $userModel->updateUserProfile($fileName, $email, $username);

                        if (!$success) {
                            echo 'uploaded this file'.$fileName;
                            $errorMessage = "Error: Failed to insert profile image path into the database.";
                            header("Location:../Views/error.php?message=" . urlencode($errorMessage));
                            exit;
                        } else {
                            $_SESSION['newimage'] = $fileName;
                            header("Location: ../Views/welcome_user.php");
                            exit;
                        }
                    } else {
                        $errorMessage = "Error: Failed to move the uploaded file.";
                    }
                } else {
                    $errorMessage = "Error: Please upload a JPG, JPEG, or PNG image file.";
                }
            }
        }

        if (isset($errorMessage)) {
            echo $errorMessage;
        }
    }
}

$controller = new UpdateProfileController();
$controller->updateUserProfile();
?>
