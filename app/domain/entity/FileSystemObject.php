<?php

namespace app\domain\entity;

class FileSystemObject
{
    public const TYPE_FOLDER = 1;
    public const TYPE_FILE = 2;

    public function __construct(
        private string $path,
        private string $name,
        private string $size,
        private string $type,
        private ?string $mimeType,
    )
    {
    }

    /**
     * @return string path to file system object
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string name of the file
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string size of the file
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string type of the file
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null mime type of the file
     */
    public function getMimeType(): ?string {
        return $this->mimeType;
    }
}
