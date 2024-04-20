<?php

namespace app\domain\entity;

use JsonSerializable;

class User implements JsonSerializable
{
    public function __construct(
        private int $id,
        private string $login,
        private string $password,
    )
    {
    }

    /**
     * @return int user id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string user login
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string password hash
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array for json serialization
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}