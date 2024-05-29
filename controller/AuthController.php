<?php

/**
 * AuthController class
 *
 * @author Sridharan
 */

require './model/AuthModel.php';

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
    }

    /**
     * Login a user
     *
     * @return void
     */
    public function login(): void
    {
        // Handle login request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate login form entries
            $errors = $this->validateLoginFormEntries($_POST);
            if (!empty($errors)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => $errors
                    ]
                );
                exit();
            }
            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($this->model->check($email)) {
                $user = $this->model->getUserByEmail($email);
                if (password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    echo json_encode(([
                        'status' => 'success',
                        'message' => 'Login successful',
                        'user' => $user
                    ]));
                    exit();
                } else {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Invalid password'
                        ]
                    );
                    exit();
                }
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'User not found'
                    ]
                );
                exit();
            }
        }
    }

    /**
     * Validate login form entries
     *
     * @return array
     */
    public function validateLoginFormEntries(array $data): array
    {
        $errors = [];
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        }
        if (empty($data['password'])) {
            $errors[] = 'Password is required';
        }

        return $errors;
    }

    /**
     * Register a new user
     *
     * @return void
     */
    public function register(): void
    {
        // Handle registration request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = $this->validateRegisterFormEntries($_POST);
            if (!empty($errors)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => $errors
                    ]
                );
                exit();
            }
            if ($this->model->check($email)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Email already exists'
                    ]
                );
                exit();
            }
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password
            ];
            if ($this->model->create($data)) {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Registration successful'
                    ]
                );
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Registration failed'
                    ]
                );
                exit();
            }
        }
    }

    /**
     * Validate form entries
     *
     * @param array $data
     * @return array
     */
    public function validateRegisterFormEntries(array $data): array
    {
        $errors = [];

        $requiredFields = ['username' => 'Name', 'email' => 'Email', 'password' => 'Password', 'confirm_password' => 'Confirm Password'];

        foreach ($requiredFields as $field => $fieldName) {
            if (empty($data[$field])) {
                $errors[] = $fieldName . ' is required';
            }
        }

        if (!empty($data['password']) && !empty($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            $errors[] = 'Passwords do not match';
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        $lengthConstraints = ['username' => [3, 20], 'password' => [8, 20], 'confirm_password' => [0, 20]];

        foreach ($lengthConstraints as $field => $length) {
            if (!empty($data[$field]) && (strlen($data[$field]) < $length[0] || strlen($data[$field]) > $length[1])) {
                $errors[] = ucfirst($field) . ' must be between ' . $length[0] . ' and ' . $length[1] . ' characters';
            }
        }

        if (!empty($data['password']) && !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/', $data['password'])) {
            $errors[] = "Password must contain at least one upper case letter, one number, and one special character.";
        }

        return $errors;
    }

    /**
     * Logout a user
     *
     * @return void
     */
    public function logout(): void
    {
        echo "Hiii";
        exit;
        // Handle logout request
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            session_destroy();
            echo json_encode(
                [
                    'status' => 'success',
                    'message' => 'Logout successful'
                ]
            );
            exit();
        }
    }
}
