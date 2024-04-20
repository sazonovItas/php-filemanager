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

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $statement->fetch();
    }

    public static function getUserByLogin(string $login): User|false {
        $statement = self::$db->prepare("SELECT * FROM users WHERE login=:login");
        $statement->bindValue(':login', $login);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $statement->fetch();
    }

    public static function createUser(User $user): User|false {
        $statement = self::$db->prepare("INSERT INTO users (login, password) VALUES (:login, :password) RETURNING *");
        $statement->bindValue(':login', $user->getLogin());
        $statement->bindValue(':password', $user->getPassword());
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $statement->fetch();
    }

    public static function updateUser(User $user): User|false {
        $statement = self::$db->prepare("UPDATE users SET login=:login, password=:password WHERE id=:id RETURNING *");
        $statement->bindValue(':login', $user->getLogin());
        $statement->bindValue(':password', $user->getPassword());
        $statement->bindValue(':id', $user->getId());
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $statement->fetch();
    }
}