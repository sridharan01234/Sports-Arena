<?php


/**
 * AuthController class
 *
 * @author Sridharan sridharan01234@gmail.com
 * @author Sridharan sridharan01234@gmail.com
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once './model/AuthModel.php';
require_once './vendor/autoload.php';
require_once './helper/JWTHelper.php';
require_once 'BaseController.php';
require_once './model/AuthModel.php';
require_once './vendor/autoload.php';
require_once './helper/JWTHelper.php';
require_once 'BaseController.php';

class AuthController extends BaseController
class AuthController extends BaseController
{
    private $model;
    private $jwt;

    public function __construct()
    {
        $this->model = new AuthModel();
        $this->jwt = new JWTHelper();
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

        $requiredFields = ['firstName' => 'First Name', 'lastName' => 'Last Name', 'email' => 'Email', 'password' => 'Password', 'confirmPassword' => 'Confirm Password', 'gender' => 'Gender', 'phoneNumber' => 'Phone'];

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
        return true;
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
        $mail->Password = "jxhrmeoqgtfgsdry"; // Sender password
        $mail->SetFrom("sridharan01234@gmail.com", "Sports Arena"); // Sender details
        $mail->Password = "jxhrmeoqgtfgsdry"; // Sender password
        $mail->SetFrom("sridharan01234@gmail.com", "Sports Arena"); // Sender details
        $mail->Subject = $subject; // Email subject
        $mail->Body = $message; // Email body
        $mail->AddAddress($email, "Sports Arena User"); // Recipient email
        $mail->AddAddress($email, "Sports Arena User"); // Recipient email

        // Sending email
        if (!$mail->Send()) {
            return $mail->ErrorInfo;
            return $mail->ErrorInfo;
        } else {
            return "Mail sent successfully";
            return "Mail sent successfully";
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
            $data = $this->decodeRequest();
            // Check if data is null
            if (is_null($data)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Invalid request',
                        'data' => $data
                    ]
                );
                exit();
            }
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
                if ($user->email_verified == 0) {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Email not verified'
                        ]
                    );
                    exit();
                }
                if ($user->email_verified == 0) {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Email not verified'
                        ]
                    );
                    exit();
                }
                if (password_verify($password, $user->password)) {
                    session_start();
                    session_start();
                    $_SESSION['user_id'] = $user->user_id;
                    echo json_encode(([
                        'status' => 'success',
                        'message' => 'Login successful',
                        'jwt' => $this->jwt->generateJWT($user),
                        'session_id' => session_id(),
                        'role' => $user->is_admin ? 'admin' : 'user'
                    ]));
                    exit();
                } else {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Incorrect password'
                            'message' => 'Incorrect password'
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
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
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
            $data = $this->decodeRequest();
            // Check if data is null
            if (is_null($data)) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Invalid request'
                    ]
                );
                exit();
            }
            $email = $data['email'];
            $password = $data['password'];
            // Validate registration form entries
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
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'password' => $hashed_password,
                'gender' => $data['gender'],
                'dob' => $data['dob'],
                'token' => bin2hex(random_bytes(8)),
                'phonenumber' => $data['phoneNumber'],
            ];
            unset($data['age']);
            $subject = 'Registration Successful';
            $message = "
        <html>
        <head>
            <p>Dear
                $data[first_name]
                $data[last_name],</p>
            <p>Thank you for registering with us. Your account has been successfully created.</p>
            <p>Your username is: $data[email]</p>
            <p>Your password is: $password</p>
            <p>Please use this password to login to your account.</p>
            <p>Thank you for using our service.</p>
            <br>

            <p>Click This link to verify your email address: <a href='http://172.24.220.187:8080/email/verify?token=$data[token]'>Click Here</a></p>

            <p>Best regards,</p>
            <p>Sports Arena Team</p>
        </body>
        </html>
            ";
            $message = "
        <html>
        <head>
            <p>Dear
                $data[first_name]
                $data[last_name],</p>
            <p>Thank you for registering with us. Your account has been successfully created.</p>
            <p>Your username is: $data[email]</p>
            <p>Your password is: $password</p>
            <p>Please use this password to login to your account.</p>
            <p>Thank you for using our service.</p>
            <br>

            <p>Click This link to verify your email address: <a href='http://172.24.220.187:8080/email/verify?token=$data[token]'>Click Here</a></p>

            <p>Best regards,</p>
            <p>Sports Arena Team</p>
        </body>
        </html>
            ";
            $this->sendEmail($data['email'], $subject, $message);
            if ($this->model->create($data)) {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Registration successful'
                    ]
                );
                exit();
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Registration failed'
                    ]
                );
                exit();
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
            if ($this->model->create($data)) {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Registration successful'
                    ]
                );
                exit();
            } else {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Registration failed'
                    ]
                );
                exit();
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
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
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
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
            $data = $this->decodeRequest();
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
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
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
            $data = $this->decodeRequest();
            if (isset($data['email'])) {
                $email = $data['email'];
                if ($this->model->checkEmail($email)) {
                    $otp = rand(100000, 999999);
                    $this->model->updateOtp($email, $otp);
                    $this->model->updateOtp($email, $otp);
                    $subject = 'Reset Password';
                    $message =
                        "<html>
                    <body>
                        <p>Please use the following OTP to reset your password:</p>
                        <p>Your OTP is: <strong>$otp</strong></p>
                        <p>This OTP is valid for 5 minutes.</p>
                        <p>If you did not request a password reset, please ignore this email.</p>
                        <p>Thank you for using our service.</p>
                        <br>
                        <p>Best regards,</p>
                        <p>Sports Arena Team</p>                        
                    </body>
                    </html>";
                    $message =
                        "<html>
                    <body>
                        <p>Please use the following OTP to reset your password:</p>
                        <p>Your OTP is: <strong>$otp</strong></p>
                        <p>This OTP is valid for 5 minutes.</p>
                        <p>If you did not request a password reset, please ignore this email.</p>
                        <p>Thank you for using our service.</p>
                        <br>
                        <p>Best regards,</p>
                        <p>Sports Arena Team</p>                        
                    </body>
                    </html>";
                    $mailStatus = $this->sendEmail($email, $subject, $message);
                    if ($mailStatus === "Mail sent successfully") {
                    if ($mailStatus === "Mail sent successfully") {
                        echo json_encode(
                            [
                                'status' => 'success',
                                'message' => 'Password reset email sent',
                                'email' => $email
                                'message' => 'Password reset email sent',
                                'email' => $email
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
                            'status' => 'error',
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
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }
    }

    /**
     * Verifies OTP
     *
     * @return void
     */
    public function verifyOTP(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->decodeRequest();
            if (!$this->model->checkOtp($data['email'], $data['otp'])) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Incorrect OTP'
                    ]
                );
                exit();
            } else {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Correct OTP'
                    ]
                );
                exit();
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->decodeRequest();
            if (!$this->model->checkOtp($data['email'], $data['otp'])) {
                echo json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Incorrect OTP'
                    ]
                );
                exit();
            } else {
                echo json_encode(
                    [
                        'status' => 'success',
                        'message' => 'Correct OTP'
                    ]
                );
                exit();
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }
    }

    /**
     * Verify JWT token
     *
     * @return void
     */
    public function verifyToken(): void
    {
        $this->jwt->verifyJWT();
    }

    /**
     * Change password
     *
     * @return void
     */
    public function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->decodeRequest();
            if (isset($data['password'])) {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->model->changePassword($data['email'], $password)) {
                    echo json_encode(
                        [
                            'status' => 'success',
                            'message' => 'Password changed successfully'
                        ]
                    );
                    exit();
                } else {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Failed to change password'
                        ]
                    );
                    exit();
                }
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }
    }

    /**
     * Email Confirmation
     *
     * @return void
     */
    public function EmailConfirmation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'This ' . $_SERVER['REQUEST_METHOD'] . ' request method is not supported'
                ]
            );
            exit();
        }
        $email = $this->model->emailTokenVerification($_GET['token']);
        if ($email) {
            $this->model->updateEmailVerificationStatus($email);
            echo
            "<div style='border:1px solid black;padding:20px;width:400px;margin:auto;text-align:center;margin-top:300px'>
            <h1 style='color:green'>Email Verified</h1>"
            ."<p>Your email has been verified successfully.</p>"
            ."<p>You can now login with your email and password.</p></div>";
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'Invalid token'
                ]
            );
            exit();
        }
    }

    /**
     * Change password
     *
     * @return void
     */
    public function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->decodeRequest();
            if (isset($data['password'])) {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->model->changePassword($data['email'], $password)) {
                    echo json_encode(
                        [
                            'status' => 'success',
                            'message' => 'Password changed successfully'
                        ]
                    );
                    exit();
                } else {
                    echo json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Failed to change password'
                        ]
                    );
                    exit();
                }
            }
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => "This " . $_SERVER['REQUEST_METHOD'] . " request method is not supported",
                ]
            );
            exit();
        }
    }

    /**
     * Email Confirmation
     *
     * @return void
     */
    public function EmailConfirmation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'This ' . $_SERVER['REQUEST_METHOD'] . ' request method is not supported'
                ]
            );
            exit();
        }
        $email = $this->model->emailTokenVerification($_GET['token']);
        if ($email) {
            $this->model->updateEmailVerificationStatus($email);
            echo
            "<div style='border:1px solid black;padding:20px;width:400px;margin:auto;text-align:center;margin-top:300px'>
            <h1 style='color:green'>Email Verified</h1>"
            ."<p>Your email has been verified successfully.</p>"
            ."<p>You can now login with your email and password.</p></div>";
            exit();
        } else {
            echo json_encode(
                [
                    'status' => 'error',
                    'message' => 'Invalid token'
                ]
            );
            exit();
        }
    }
}
