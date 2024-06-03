<?php

/**
 * User Controller
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last Modified : 03-06-2024
 */

require "BaseController.php";
require './model/UserModel.php';
require './helper/SessionHelper.php';

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
        $data = $this->decodeRequest();
        $this->userModel->deleteUser($data['user_id']);

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
        $data = $this->decodeRequest();
        $user = $this->userModel->getUser($data['user_id']);

        echo json_encode($user);
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
        $details = [];

        // Iterate through the desired keys
        $keys = ['name', 'age', 'gender', 'dob', 'phone', 'address'];

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $details[$key] = $data[$key];
            }
        }

        // Add the 'modified_at' key regardless
        $details['modified_at'] = date('Y-m-d H:i:s');
        if ($this->userModel->updateUser($data['user_id'], $details)) {
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
            echo json_encode(['error' => 'Method Not Allowed']);
            exit;
        }

        $data = $this->decodeRequest();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->userModel->updateUser($data['user_id'], $data['password']);

        echo json_encode(['success' => true]);
        exit;
    }
}
