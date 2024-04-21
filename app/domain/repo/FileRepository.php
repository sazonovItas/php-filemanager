<?php

namespace app\domain\repo;

use app\domain\entity\File;
use app\exceptions\NotFoundHttpException;
use Bayfront\MimeTypes\MimeType;

class FileRepository
{
    public static string $prefixPath;

    /**
     * @return string
     */
    public static function getPrefixPath(): string
    {
        return self::$prefixPath;
    }

    /**
     * @param string $prefix sets prefix path to files persistence
     */
    public static function setPrefixPath(string $prefix): void {
      self::$prefixPath = $prefix;
    }

    public static function upload(string $path) {
        $path = self::$prefixPath . $path;
        if (!self::checkFolder($path)) {
            throw new NotFoundHttpException("could not found {$path}");
        }

        if (!@move_uploaded_file($_FILES["file"]["tmp_name"], $path . '/' . $_FILES["file"]["name"])) {
            throw new \Exception("could not upload file to {$path}/{$_FILES["file"]["name"]}");
        };
    }

    public static function download(string $path) {
        if (!self::checkFolder($path)) {
            throw new NotFoundHttpException("could not found {$path}");
        }


    }

    public static function getFilesByPath(string $path): array {
        $path = self::$prefixPath . $path;
        if (!self::checkFolder($path)) {
            throw new NotFoundHttpException("could not found {$path}");
        }

        $content = scandir($path);
        if ($content === false) {
            return [];
        }

        $files = [];
        foreach ($content as $file) {
            if ($file === '.') {
                continue;
            }

            $files[] = new File($path . '/' . $file, $file, @filesize($path . '/' . $file), is_dir($path . '/' . $file) ? File::TYPE_FOLDER : File::TYPE_FILE, MimeType::fromFile($file));
        }

        return $files;
    }

    public static function rename(string $oldFilePath, string $newFilePath): void {
        if (!self::checkFolder($oldFilePath)) {
            throw new NotFoundHttpException("could not found {$oldFilePath}");
        }

        if (!rename($oldFilePath, $newFilePath)) {
            throw new \Exception("could not rename {$oldFilePath} to {$newFilePath}");
        }
    }

    public static function move(string $oldFilePath, string $newFilePath): void {
        if (!self::checkFolder($oldFilePath)) {
            throw new NotFoundHttpException("could not found {$oldFilePath}");
        }

        if (!rename($oldFilePath, $newFilePath)) {
            throw new \Exception("could not move {$oldFilePath} to {$newFilePath}");
        }
    }

    public static function copy(string $oldFilePath, string $newFilePath): void {
        if (!self::checkFolder($oldFilePath)) {
            throw new NotFoundHttpException("could not found {$oldFilePath}");
        }

        if (!self::checkFolder($newFilePath)) {
            throw new NotFoundHttpException("could not found {$newFilePath}");
        }

        if (!copy($oldFilePath, $newFilePath)) {
            throw new \Exception("could not copy {$oldFilePath} to {$newFilePath}");
        }
    }

    public static function delete(string $path): void {
        if (!self::checkFolder($path)) {
            throw new NotFoundHttpException("could not found {$path}");
        }

        if (is_dir($path)) {
            $files = array_diff(scandir($path), array('.','..'));
            foreach ($files as $file) {
                self::delete($path . '/' . $file);
            }
        } else if (!unlink($path)) {
            throw new \Exception("could not delete {$path}");
        }
    }

    private static function checkFolder(string $path): bool {
        $base     = realpath(self::$prefixPath);
        $filename = realpath($path);
        if ($filename === false || strncmp($filename, $base, strlen($base)) !== 0) {
            return false;
        }

        return true;
    }
}
