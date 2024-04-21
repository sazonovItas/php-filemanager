<?php

namespace app\domain\service;

use app\domain\entity\User;
use app\domain\repo\TokenRepository;
use app\domain\repo\UserRepository;
use app\exceptions\InternalErrorException;
use app\exceptions\TokenInvalidException;
use app\exceptions\TokenNotFoundException;
use app\exceptions\UserAlreadyExistsException;
use app\exceptions\UserNotFoundException;
use app\exceptions\UserPasswordDoesNotMatchException;
use Exception;

class UserService
{
    public static function signup(string $login, string $password): User {
        $candidate = UserRepository::getUserByLogin($login);
        if ($candidate) {
            throw new UserAlreadyExistsException("User with this login already exists");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = UserRepository::createUser(new User(0, $login, $hashedPassword));
        if (!$user) {
            throw new Exception("Couldn't create user");
        }

        return $user;
    }

    public static function signin(string $login, string $password, bool $genRefresh): array {
        $candidate = UserRepository::getUserByLogin($login);
        if (!$candidate) {
            throw new UserNotFoundException("User with this login does not exist");
        }

        if (!password_verify($password, $candidate->getPassword())) {
            throw new UserPasswordDoesNotMatchException("Password does not match");
        };

        $refreshToken = null;
        if ($genRefresh) {
            $token = TokenService::generateRefreshToken(['user_id' => $candidate->getId()]);
            $refreshToken = TokenService::saveToken($candidate->getId(), $token);
        }

        $accessToken = TokenService::generateAccessToken(['user_id' => $candidate->getId()]);
        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken?->getToken() ?? null,
            'user' => $candidate,
        ];
    }

    public static function logout(int $id): void {
        $refreshToken = TokenRepository::getByUserId($id);
        if (!$refreshToken) {
            throw new TokenNotFoundException("Couldn't find refresh token");
        }

        if (!TokenRepository::deleteToken($refreshToken->getId())) {
            throw new Exception("Couldn't delete refresh token");
        }
    }

    public static function refresh(string $checkRefreshToken): string {
        $refreshToken = TokenRepository::getByToken($checkRefreshToken);
        if (!$refreshToken) {
            throw new TokenNotFoundException("Couldn't find refresh token");
        }

        $token = TokenService::validateToken($refreshToken->getToken(), getenv("JWT_REFRESH_SECRET_KEY"));
        if (!$token) {
            TokenRepository::deleteToken($refreshToken->getId());
            throw new TokenInvalidException("Refresh token is invalid");
        }
        $payload = (array)json_decode(json_encode($token), true)['payload'];

        return TokenService::generateAccessToken(['user_id' => $payload['user_id']]);
    }

}