<?php


/**
 * AuthController class
 *
 * @author Sridharan
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './model/AuthModel.php';
require './vendor/autoload.php';

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
    }

    /**
     * Validate form entries
     *
     * @param array $data
     * @return array
     */
    private function validateRegisterFormEntries(array $data): array
    {
        $errors = [];

        $requiredFields = ['firstName' => 'Name', 'email' => 'Email', 'password' => 'Password', 'confirmPassword' => 'Confirm Password'];

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
     * Send a email
     *
     * @return bool|string
     */
    private function sendEmail(string $email, string $subject, string $message): bool|string
    {
        // Sending OTP via email
        $mail = new PHPMailer(true); // Creating PHPMailer instance
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->Username = "sridharan01234@gmail.com"; // Sender email
        $mail->Password = "quqyymmbzmqqntrh"; // Sender password
        $mail->SetFrom("sridharan01234@gmail.com", "Sridharan"); // Sender details
        $mail->Subject = $subject; // Email subject
        $mail->Body = $message; // Email body
        $mail->AddAddress($email, "HR"); // Recipient email

        // Sending email
        if (!$mail->Send()) {
            echo json_encode(
                [
                    "status"=> "error",
                    "message"=> "Mail failed to send"
                ]
            );
            exit();
        } else {
            echo json_encode(
                [
                    "status"=> "success",
                    "message"=> "Otp sent success"
                ]
            );
            exit();
        }
    }

    /**
     * Validate login form entries
     *
     * @return array
     */
    private function validateLoginFormEntries(array $data): array
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
     * Login a user
     *
     * @return void
     */
    public function login(): void
    {
        // Handle login request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Access the raw POST data
            $raw_data = file_get_contents('php://input');

            // Parse the JSON data
            $data = json_decode($raw_data, true);

            // Validate login form entries
            $errors = $this->validateLoginFormEntries($data);

            if (!empty($errors)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => $errors
                    ]
                );
                exit();
            }
            $email = $data['email'];
            $password = $data['password'];
            if ($this->model->checkEmail($email)) {
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
     * Register a new user
     *
     * @return void
     */
    public function register(): void
    {
        // Handle registration request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Access the raw POST data
            $raw_data = file_get_contents('php://input');

            // Parse the JSON data
            $data = json_decode($raw_data, true);

            $username = $data['firstName'];
            $email = $data['email'];
            $password = $data['password'];
            $errors = $this->validateRegisterFormEntries($data);
            if (!empty($errors)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => $errors
                    ]
                );
                exit();
            }
            if ($this->model->checkEmail($email)) {
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
                'firstName'=> $data['firstName'],
                'lastName'=> $data['lastName'],
                'username'=> $data['firstName'].$data['lastName'],
                'email'=> $email,
                'password'=> $hashed_password,
                'age' => $data['age'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
            ];
            if ($this->model->create($data)) {
                $subject = 'Registration Successful';
                $message = 'Thank you for registering!';
                $this->sendEmail($data['email'], $subject, $message);
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
     * Logout a user
     *
     * @return void
     */
    public function logout(): void
    {
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

    /**
     * Verify email address
     *
     * @return void
     */
    public function verifyEmail(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Access the raw POST data
            $raw_data = file_get_contents('php://input');
            // Parse the JSON data
            $data = json_decode($raw_data, true);
            if ($this->model->getUserByEmail($data['email'])) {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Email verified'
                    ]
                );
                exit();
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Invalid email'
                    ]
                );
                exit();
            }
        }
    }

    /**
     * Reset password
     *
     * @return void
     */
    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Access the raw POST data
            $raw_data = file_get_contents('php://input');
            // Parse the JSON data
            $data = json_decode($raw_data, true);
            if (isset($data['email'])) {
                $email = $data['email'];
                if ($this->model->checkEmail($email)) {
                    $otp = rand(100000, 999999);
                    $subject = 'Reset Password';
                    $message = 'Your OTP is: ' . $otp;
                    $mailStatus = $this->sendEmail($email, $subject, $message);
                    if ($mailStatus === "Message has been sent") {
                        echo json_encode(
                            [
                                'status' => 'success',
                                'otp' => $otp,
                                'message' => 'Password reset email sent'
                            ]
                        );
                        exit();
                    } else {
                        echo json_encode(
                            [
                                'status' => 'error',
                                'message' => 'Failed to send password reset email',
                                'error' => $mailStatus
                            ]
                        );
                        exit();
                    }
                } else {
                    echo json_encode(
                        [
                            'status'=> 'error',
                            'message' => 'Email not found',
                        ]
                    );
                    exit();
                }
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Please enter email'
                    ]
                );
                exit();
            }
        }
    }
}
