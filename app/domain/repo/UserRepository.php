<?php

namespace app\domain\repo;

use app\domain\entity\User;
use PDO;

class UserRepository
{
    public static PDO $db;

    public static function getUserById(int $id): User|false {
        $statement = self::$db->prepare("SELECT * FROM users WHERE id=:id");
        $statement->bindValue(':id', $id);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }

        return new User($user['id'], $user['login'], $user['password']);
    }

    public static function getUserByLogin(string $login): User|false
    {
        $statement = self::$db->prepare("SELECT * FROM users WHERE login=:login");
        $statement->bindValue(':login', $login);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return false;
        }

        return new User($user['id'], $user['login'], $user['password']);
    }

    public static function createUser(User $user): User|false {
        $statement = self::$db->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
        $statement->bindValue(':login', $user->getLogin());
        $statement->bindValue(':password', $user->getPassword());
        $statement->execute();

        return self::getUserByLogin($user->getLogin());
    }

    public static function updateUser(User $user): bool {
        $statement = self::$db->prepare("UPDATE users SET login=:login, password=:password WHERE id=:id");
        $statement->bindValue(':login', $user->getLogin());
        $statement->bindValue(':password', $user->getPassword());
        $statement->bindValue(':id', $user->getId());
        return $statement->execute();
    }
}