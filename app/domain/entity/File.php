<?php

namespace app\domain\entity;

class File implements \JsonSerializable
{
    public function __construct(
        private string $path,
        private string $name,
        private int $size,
        private string $extension,
        private ?string $mimeType,
        private ?string $mime,
    )
    {
    }

    /**
     * @return string path to a file
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string name of a file
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int size of a file
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string extension of a file
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string|null mime of a file
     */
    public function getMime(): ?string
    {
        return $this->mime;
    }

    /**
     * @return string|null mime type of file
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @return array for json serialization
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}