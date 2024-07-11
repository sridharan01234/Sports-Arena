<?php
/**
 * AuthModel
 *
 * @author Sridharan sridharan01234@gmail.com
 * LastModified : 29-05-2024
 */

require './database/Database.php';
require_once './interface/BaseInterface.php';

class AuthModel extends Database implements BaseInterface
{
    /**
     * @var Database
     */
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Check if user exists
     *
     * @param string $email
     *
     * @return bool
     */
    public function checkEmail(string $email): bool
    {
        $user = $this->db->get('users', ['email' => $email], []);
        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * Create a new user
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->db->insert('users', $data);
    }

    /**
     * Get user by email
     *
     * @param string $email
     *
     * @return object|bool
     */
    public function getUserByEmail(string $email): object|bool
    {
        return $this->db->get('users', ['email' => $email], []);
    }

    /**
     * Change password
     *
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function changePassword(string $email, string $password): bool
    {
        return $this->db->update('users', ['password' => $password], ['email' => $email]);
    }

    /**
     * Get user by id
     *
     * @param int $id
     *
     * @return object|bool

     */
    public function getUser(int $id): object|bool
    {
        return $this->db->get('users', ['id' => $id], []);
    }

    /**
     * Checks otp
     *
     * @param string $email
     * @param string $otp
     *
     * @return bool
     */
    public function checkOtp(string $email, string $otp): bool
    {
        if ($this->db->get('otp_codes', [
            'user_email' => $email,
            'otp_code' => $otp
        ], [])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update user's otp
     *
     * @param string $email
     * @param string $otp
     *
     * @return bool
     */
    public function updateOtp(string $email, string $otp): bool
    {
        return $this->db->insert('otp_codes', [
            'user_email' => $email,
            'otp_code' => $otp,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
        ]);
    }

    /**
     *
     */
    public function emailTokenVerification(string $token): bool | string
    {
        if ($this->db->get('users', ['token' => $token], [])) {
            return $this->db->get('users', ['token' => $token], [])->email;
        } else {
            return false;
        }
    }

    /**
     * Update email verification status
     */
    public function updateEmailVerificationStatus(string $email): bool
    {
        return $this->db->update('users', ['email_verified' => 1], ['email' => $email]);
    }

    public function updateUser(int $id, array $data)
    {
        // TODO: Implement updateUser() method.
    }

    public function deleteUser(int $id)
    {
        // TODO: Implement deleteUser() method.
    }
}
