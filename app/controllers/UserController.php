<?php

namespace app\controllers;

use app\domain\service\UserService;
use app\exceptions\BadRequestHttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

class UserController extends AbstractController
{
    public function signup() {
        if (!$this->request->login && !$this->request->password) {
            throw new BadRequestHttpException();
        }

        UserService::signup($this->request->login, $this->request->password);
    }

    public function signin() {

    }

    public function logout() {
    }

    public function refresh() {

    }
}