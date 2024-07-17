<?php

/**
 * JWTHelper class
 *
 * @author Sridharan sridharan01234@gmail.com
 */

require './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    private const JWT_SECRET_KEY = 's3cr3tK3y123!@#"';

    /**
     * Generate JWT
     *
     * @param object $user
     *
     * @return string
     */
    public function generateJWT(object $user): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'userId' => $user->user_id,
                'sessionId' => session_id()
            ]
        ];

        $jwt = JWT::encode($payload, self::JWT_SECRET_KEY, 'HS256');
        return $jwt;
    }

    /**
     * Get Bearer Token
     *
     * @return string|null
     */
    private function getBearerToken(): ?string
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Verify JWT
     *
     * @return void
     */
    public function verifyJWT(): void
    {
        $jwt = self::getBearerToken();
        if (!$jwt) {
            echo json_encode(
                [
                    "status" => "error",
                    "message" => "Unauthorized"
                ]
            );
            exit();
        } else {
            try {
                $decoded = JWT::decode($jwt, new Key(self::JWT_SECRET_KEY, 'HS256'));
                if (self::isTokenExpired($decoded)) {
                    echo json_encode(
                        [
                            "status" => "error",
                            "message" => "JWT expired"
                        ]
                    );
                    exit();
                }
                session_id($decoded->data->sessionId);
                session_start();

                return;
            } catch (DomainException $e) {
                echo json_encode(
                    [
                        "status" => "error",
                        "message" => "JWT verification failed",
                        "error" => $e->getMessage()
                    ]
                );
                exit();
            } catch (Exception $e) {
                echo json_encode(
                    [
                        "status" => "error",
                        "message" => "JWT verification failed",
                        "error" => $e->getMessage()
                    ]
                );
                exit();
            }
        }
    }

    /**
     * Checks token expiration
     *
     * @param object $token
     *
     * @return bool
     */
    private function isTokenExpired(object $token): bool
    {
        $expirationTime = $token->exp;
        return time() > $expirationTime;
    }
}
