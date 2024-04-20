<?php

namespace app\domain\repo;

use app\domain\entity\Token;
use PDO;

class TokenRepository
{
    public static PDO $db;

    public static function createToken(Token $token): Token|false {
        $statement = self::$db->prepare("INSERT INTO token (user_id, token) VALUES (:id, :token) RETURNING *");
        $statement->bindValue(':id', $token->getId());
        $statement->bindValue(':token', $token->getToken());
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Token::class);
        return $statement->fetch();
    }

    public static function getById(int $id): Token|false {
        $statement = self::$db->prepare("SELECT * FROM token WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Token::class);
        return $statement->fetch();
    }

    public static function getByUserId(int $userId): Token|false {
        $statement = self::$db->prepare("SELECT * FROM token WHERE user_id = :id");
        $statement->bindValue('id', $userId);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Token::class);
        return $statement->fetch();
    }

    public static function updateToken(Token $token): Token|false {
        $statement = self::$db->prepare("UPDATE token SET token = :token WHERE id = :id RETURNING *");
        $statement->bindValue(':token', $token->getToken());
        $statement->bindValue(':id', $token->getId());
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Token::class);
        return $statement->fetch();
    }

    public static function deleteToken($id): bool {
        $statement = self::$db->prepare("DELETE FROM token WHERE id = :id");
        $statement->bindValue(':id', $id);

        return $statement->execute();
    }
}