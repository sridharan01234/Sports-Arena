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
            if ($this->model->getUserByEmail($_POST['email'])) {
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

    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                if ($this->model->checkEmail($email)) {
                    $otp = rand(100000, 999999);
                    $subject = 'Reset Password';
                    $message = 'Your OTP is: ' . $otp;
                    if ($this->sendEmail($email, $subject, $message) === "Message has been sent") {
                        echo json_encode(
                            [
                                'status' => 'success',
                                'message' => 'Password reset email sent'
                            ]
                        );
                        exit();
                    } else {
                        echo json_encode(
                            [
                                'status' => 'error',
                                'message' => 'Failed to send password reset email'
                            ]
                        );
                        exit();
                    }
                } else {
                    echo json_encode(
                        [
                            'status'=> 'error',
                            'message' => 'Invalid email'
                        ]
                    );
                    exit();
                }
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
     * Send a email
     *
     * @return bool|string
     */
    public function sendEmail(string $email, string $subject, string $message): bool|string
    {
        // Send email
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
    //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'sridharan01234@gmail.com';                     //SMTP username
            $mail->Password   = 'quqyymmbzmqqntrh';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
            $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');

    //Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return "Message has been sent";
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
