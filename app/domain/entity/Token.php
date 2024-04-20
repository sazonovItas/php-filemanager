<?php

namespace app\domain\entity;

use JsonSerializable;

class Token implements JsonSerializable
{
    public function __construct(
        private int $id,
        private int $userId,
        private string $token,
    )
    {
    }

    /**
     * @return int token id
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return int user id
     */
    public function getUserId(): int {
        return $this->userId;
    }

    /**
     * @return string token string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array for json serialization
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}