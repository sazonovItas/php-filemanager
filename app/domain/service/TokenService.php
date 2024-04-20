<?php
declare(strict_types=1);

namespace app\domain\service;

use app\domain\entity\Token;
use app\domain\repo\TokenRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        $expireAt = $issuedAt + 60 * 60 * 24;
        $payload = [
            'iat' => $issuedAt,
            'nbf' => $issuedAt,
            'exp' => $expireAt,
            'payload' => $payload,
        ];

        return JWT::encode($payload, $key, "HS512");
    }

    // TODO: does not throw exception from cannot save refresh token
    public static function saveToken(int $userId, string $refreshToken): Token|false {
        $tokenData = TokenRepository::getByUserId($userId);
        if ($tokenData) {
            $token = new Token($tokenData->getId(), $tokenData->getUserId(), $refreshToken);

            if (TokenRepository::updateToken($token)) {
                return $token;
            } else {
                throw new \Exception("Couldn't save token");
            }
        }

        return TokenRepository::createToken(new Token(0, $userId, $refreshToken));
    }

    public static function validateToken($token, $key): \stdClass|false {
        try {
            return JWT::decode($token->getToken(), new Key($key, "HS512"));
        } catch (\Exception $e) {
            return false;
        }
    }
}