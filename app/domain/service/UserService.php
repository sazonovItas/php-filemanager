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
            throw new InternalErrorException("Couldn't create user");
        }

        return $user;
    }

    public static function signin(string $login, string $password, bool $genRefresh): array {
        $candidate = UserRepository::getUserByLogin($login);
        if (!$candidate) {
            throw new UserNotFoundException("User with this login does not exist");
        }

        if (password_verify($password, $candidate->getPassword())) {
            throw new UserPasswordDoesNotMatchException("Password does not match");
        };

        $refreshToken = null;
        if ($genRefresh) {
            $token = TokenService::generateRefreshToken(['userId' => $candidate->getId()]);
            $refreshToken = TokenService::saveToken($candidate->getId(), $token);
        }

        $accessToken = TokenService::generateAccessToken(['userId' => $candidate->getId()]);
        return [
            'AccessToken' => $accessToken,
            'RefreshToken' => $refreshToken,
            'User' => $candidate,
        ];
    }

    public static function refresh(int $userId): string {
        $refreshToken = TokenRepository::getById($userId);
        if (!$refreshToken) {
            throw new TokenNotFoundException("Couldn't find refresh token");
        }

        if (!TokenService::validateToken($refreshToken, getenv("JWT_REFRESH_SECRET_KEY"))) {
            throw new TokenInvalidException("Refresh token is invalid");
        }

        return TokenService::generateAccessToken(['userId' => $userId]);
    }

    public static function logout(int $userId): void {
        $refreshToken = TokenRepository::getByUserId($userId);
        if (!$refreshToken) {
            throw new TokenNotFoundException("Couldn't find refresh token");
        }

        if (TokenRepository::deleteToken($refreshToken->getId())) {
            throw new InternalErrorException("Couldn't delete refresh token");
        }
    }
}