<?php

namespace app\controllers;

use app\domain\service\TokenService;
use app\domain\service\UserService;
use app\exceptions\BadRequestHttpException;
use app\exceptions\NotAuthorizedHttpException;
use app\exceptions\NotFoundHttpException;
use app\exceptions\TokenInvalidException;
use app\exceptions\TokenNotFoundException;
use app\exceptions\UserAlreadyExistsException;
use app\exceptions\UserNotFoundException;
use app\exceptions\UserPasswordDoesNotMatchException;

class UserController extends AbstractController
{
    /**
     * post /api/v1/auth/signup
     * body: {
     *      login: "login",
     *      password: "password"
     * }
     */
    public function signup() {
        if (empty($this->request->login) || empty($this->request->password)) {
            throw new BadRequestHttpException("login or password are missing");
        }

        try {
            $user = UserService::signup($this->request->login, $this->request->password);
            //$this->response->json(["id" => $user->getId(), "login" => $user->getLogin()]);
        } catch (UserAlreadyExistsException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * post /api/v1/auth/signin
     * body: {
     *      login: "login",
     *      password: "password",
     *      remember_me: "yes"
     * }
     */
    public function signin() {
        if (empty($this->request->login) || empty($this->request->password)) {
            throw new BadRequestHttpException("login, password or remember_me are missing");
        }

        try {
            $signInData = UserService::signin($this->request->login, $this->request->password, !empty($this->request->remember_me) && $this->request->remember_me === "yes");
            if ($signInData["refreshToken"] !== null) {
                setcookie("refreshToken", $signInData["refreshToken"], time() + 60 * 60 * 24, "/", "", false, true);
            }

            $this->response->json(['accessToken' => $signInData["accessToken"], "login" => ($signInData["user"])->getLogin()]);
        } catch (UserNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (UserPasswordDoesNotMatchException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * post /api/v1/auth/logout
     */
    public function logout() {
        if ($this->request->access_token_payload === null) {
            throw new TokenInvalidException("Couldn't read user id from token");
        }

        try {
            setcookie("refreshToken", "", -1, "/", "", false, true);
            UserService::logout($this->request->access_token_payload['user_id']);
        } catch (UserNotFoundException|TokenNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * get /api/v1/auth/refresh
     */
    public function refresh() {
        if ($this->request->access_token_payload === null) {
            throw new TokenInvalidException("Couldn't read user id from token");
        }

        isset($_COOKIE["refreshToken"]) ? $refreshToken = $_COOKIE["refreshToken"] : $refreshToken = null;
        if ($refreshToken === null) {
            throw new NotAuthorizedHttpException("couldn't find refresh token");
        }

        try {
            $token = UserService::refresh($this->request->access_token_payload['user_id'], $refreshToken);
            $this->response->json(["accessToken" => $token]);
        } catch (UserNotFoundException|TokenNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        } catch (TokenInvalidException $exception) {
            setcookie("refreshToken", "", -1, "/", "", false, true);
            throw new NotAuthorizedHttpException($exception->getMessage());
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}