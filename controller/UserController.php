<?php

/**
 * User Controller
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

require "BaseController.php";
require './model/UserModel.php';

class UserController extends BaseController
{
    /**
     * User Model
     *
     * @var UserModel
     */
    private $userModel;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Delete User
     *
     * @return void
     */
    public function userDelete(): void
    {
        $data = (int)$_GET['id'];
        $this->userModel->deleteUser($data);

        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Get User
     *
     * @return void
     */
    public function userProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }
        $data = $this->decodeRequest();
        $user = $this->userModel->getUser($_SESSION['user_id']);

        $details = [];
        $from = new DateTime($user->dob);
        $to   = new DateTime('today');
        $details = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'gender' => $user->gender,
            'age' => $from->diff($to)->y,
            'dob' => $user->dob,
            'phoneNumber' => $user->phonenumber,
        ];
        echo json_encode($details);
        exit;
    }

    /**
     * Update User
     *
     * @return void
     */
    public function userUpdate(): void
    {
        $data = $this->decodeRequest();
        $details = [
        'first_name' => $data['firstName'],
        'last_name' => $data['lastName'],
        'email' => $data['email'],
        'gender' => $data['gender'],
        'dob' => $data['dob'],
        'phonenumber' => $data['phoneNumber'],
        ];
        // Add the 'modified_at' key regardless
        $details['modified_at'] = date('Y-m-d H:i:s');
        if ($this->userModel->updateUser($_SESSION['user_id'], $details)) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }

    /**
     * User change password
     *
     * @return void
     */
    public function userChangePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }

        $data = $this->decodeRequest();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->userModel->updateUser($data['user_id'], $data['password']);

        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * User profile picture upload
     *
     * @return void
     */
    public function userProfilePictureUpload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }

        $data = $this->decodeRequest();

        // Updated code for handling profile picture upload with error handling, permission fix, and database update
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/home/asplap1937/github/be/assets/profile_pictures/';

            $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $uploadFile = $uploadDir . $_SESSION['user_id'] . '.' . $fileExtension;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
                $this->userModel->updateUser($_POST['user_id'], [
                    'profile_picture' => $uploadFile = $uploadDir . $_POST['email'] . '.' . $fileExtension
                ]);
                echo json_encode([
                    'success' => true,
                    'message' => 'Profile picture uploaded successfully.'
                ]);
                exit;
            } else {
                echo json_encode([
                    'error' => 'Failed to upload profile picture.',
                    'error_code' => $_FILES['profile_picture']['error'],
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'error' => 'Profile picture upload failed.',
                'error_code' => $_FILES['profile_picture']['error'],
            ]);
            exit;
        }
    }

    /**
     * Get countries
     *
     * @return void
     */
    public function getCountries(): void
    {
        $countries = $this->userModel->getCountries();
        echo json_encode($countries);
        exit;
    }

    /**
     * Get states
     *
     * @return void
     */
    public function getStates(): void
    {
        $states = $this->userModel->getStates($_GET['country_id']);
        echo json_encode($states);
        exit;
    }

    /**
     * Get cities
     *
     * @return void
     */
    public function getCities(): void
    {
        $cities = $this->userModel->getCities($_GET['state_id']);
        echo json_encode($cities);
        exit;
    }

    public function getAll()
    {
        $users = $this->userModel->getAllUsers();
        echo json_encode($users);
        exit;
    }

    public function usersCount()
    {
        $users = $this->userModel->getAllUsers();
        echo json_encode(count($users));
        exit;
    }
}
