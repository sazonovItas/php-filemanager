<?php

namespace app\controllers;

use app\domain\repo\FileRepository;
use app\exceptions\BadRequestHttpException;

class DriveController extends AbstractController
{
    /**
     * get api/v1/drive/files?path=
     */
    public function getFiles() {
        $path = $_GET['path'] ?? '';
        $this->response->json(FileRepository::getFilesByPath($path));
    }

    /**
     * delete api/v1/drive/delete
     * body {
     *     path: "path"
     * }
     */
    public function delete() {
        if ($this->request->path === null) {
            throw new BadRequestHttpException("Missing path");
        }

        FileRepository::delete($this->request->path);
    }

    /**
     * patch api/v1/drive/rename
     * body {
     *     oldFileName: "oldFileName",
     *     newFileName: "newFileName",
     * }
     */
    public function rename() {
        if ($this->request->oldFileName === null || $this->request->newFileName) {
            throw new BadRequestHttpException("Missing files names");
        }

        FileRepository::rename($this->request->oldFileName, $this->request->newFileName);
    }

    /**
     * patch api/v1/drive/move
     * body {
     *     oldFilePath: "oldFilePath",
     *     newFilePath: "newFilePath",
     * }
     */
    public function move() {
        if ($this->request->oldFilePath === null || $this->request->newFilePath) {
            throw new BadRequestHttpException("Missing files names");
        }

        FileRepository::rename($this->request->oldFilePath, $this->request->newFilePath);
    }

    /**
     * post api/v1/drive/copy
     * body {
     *     oldFilePath: "oldFilePath",
     *     newFilePath: "newFilePath",
     * }
     */
    public function copy() {
        if ($this->request->oldFilePath === null || $this->request->newFilePath === null) {
            throw new BadRequestHttpException("Missing files paths");
        }

        FileRepository::copy($this->request->oldFilePath, $this->request->newFilePath);
    }

    public function download() {
        $path = $_GET['path'] ?? '';
    }

    public function upload() {
    }
}