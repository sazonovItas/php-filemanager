<?php

namespace app\domain\repo;

use app\domain\entity\Token;
use app\domain\entity\User;
use PDO;

class TokenRepository
{
    public static PDO $db;

    public static function createToken(Token $token): Token|false {
        $statement = self::$db->prepare("INSERT INTO tokens (user_id, token) VALUES (:user_id, :token)");
        $statement->bindValue(':user_id', $token->getUserId());
        $statement->bindValue(':token', $token->getToken());
        $statement->execute();

        return self::getByUserId($token->getUserId());
    }

    public static function getById(int $id): Token|false {
        $statement = self::$db->prepare("SELECT * FROM tokens WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();

        $token = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$token) {
            return false;
        }

        return new Token($token['id'], $token['user_id'], $token['token']);
    }

    public static function getByUserId(int $userId): Token|false {
        $statement = self::$db->prepare("SELECT * FROM tokens WHERE user_id = :user_id");
        $statement->bindValue('user_id', $userId);
        $statement->execute();

        $token = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$token) {
            return false;
        }

        return new Token($token['id'], $token['user_id'], $token['token']);
    }

    public static function updateToken(Token $token): bool {
        $statement = self::$db->prepare("UPDATE tokens SET token = :token WHERE id = :id");
        $statement->bindValue(':token', $token->getToken());
        $statement->bindValue(':id', $token->getId());
        return $statement->execute();
    }

    public static function deleteToken($id): bool {
        $statement = self::$db->prepare("DELETE FROM tokens WHERE id = :id");
        $statement->bindValue(':id', $id);

        return $statement->execute();
    }
}