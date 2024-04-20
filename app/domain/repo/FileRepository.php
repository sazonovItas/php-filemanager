<?php

namespace app\domain\repo;

class FileRepository
{
    private static string $prefixPath;

    /**
     * @return string
     */
    public function getPrefixPath(): string
    {
        return self::$prefixPath;
    }

    /**
     * @param string $prefix sets prefix path to files persistence
     */
    public function setPrefixPath(string $prefix): void {
      self::$prefixPath = $prefix;
    }
}
