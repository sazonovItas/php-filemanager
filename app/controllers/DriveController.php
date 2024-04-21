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

    /**
     * get api/v1/drive/download?path
     */
    public function download() {
        if (!isset($_GET['path'])) {
            throw new BadRequestHttpException("Missing path");
        }

        $filePath = $_GET['path'];
        $this->response->header("X-File-Path: " . $filePath);
        if (!file_exists($filePath)) {
            throw new BadRequestHttpException("File does not exist");
        }

        $this->response->header('Content-Description: File Transfer');
        $this->response->header('Content-Type: application/octet-stream');
        $this->response->header('Content-Disposition: attachment; filename="'.basename($_GET['path']).'"');
        $this->response->header('Expires: 0');
        $this->response->header('Cache-Control: must-revalidate');
        $this->response->header('Pragma: public');
        $this->response->header('Content-Length: ' . filesize($filePath));
        flush();
        readfile($filePath);
    }

    /**
     * post api/v1/drive/upload
     */
    public function upload() {
        FileRepository::upload($this->request->getHeader("X-Load-Path"));
    }
}