<?php
declare(strict_types=1);

namespace app\domain\service;

use app\domain\entity\Token;
use app\domain\repo\TokenRepository;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class TokenService
{
    public static function generateAccessToken(array $payload): string {
        $key = getenv('JWT_ACCESS_SECRET_KEY');

        $issuedAt = time();
        $expireAt = $issuedAt + 15 * 60;
        $payload = [
            'iat' => $issuedAt,
            'nbf' => $issuedAt,
            'exp' => $expireAt,
            'payload' => $payload,
        ];

        return JWT::encode($payload, $key, "HS512");
    }

    public static function generateRefreshToken(array $payload): string {
        $key = getenv('JWT_REFRESH_SECRET_KEY');

        $issuedAt = time();
        $expireAt = $issuedAt + 24 * 60 * 60;
        $payload = [
            'iat' => $issuedAt,
            'nbf' => $issuedAt,
            'exp' => $expireAt,
            'payload' => $payload,
        ];

        return JWT::encode($payload, $key, "HS512");
    }

    // TODO: do not throw exception from cannot save refresh token
    public static function saveToken(int $userId, string $refreshToken): Token|false {
        $tokenData = TokenRepository::getByUserId($userId);
        if ($tokenData) {
            $token = new Token($tokenData->getId(), $tokenData->getUserId(), $refreshToken);

            if (TokenRepository::updateToken($token)) {
                return $token;
            } else {
                throw new Exception("Couldn't save token");
            }
        }

        return TokenRepository::createToken(new Token(0, $userId, $refreshToken));
    }

    public static function validateToken(string $token, string $key): stdClass|false {
        try {
            return JWT::decode($token, new Key($key, "HS512"));
        } catch (Exception $e) {
            return false;
        }
    }
}