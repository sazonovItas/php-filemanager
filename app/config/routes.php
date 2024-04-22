<?php
declare(strict_types=1);

require_once '../exceptions/CommonExceptions.php';
require_once '../exceptions/HttpExceptions.php';
require_once '../exceptions/UserExceptions.php';
require_once '../exceptions/TokenExceptions.php';

use app\exceptions\BadRequestHttpException;
use app\exceptions\NotFoundHttpException;
use app\middlewares\Authenticate;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter as Router;
use app\middlewares\RawBodyToJson;

use app\exceptions\NotAuthorizedHttpException;

Router::setDefaultNamespace('app\controllers');

Router::group([
    "prefix" => "api/v1",
], function () {

    Router::group([
        "prefix" => "/auth",
        "middleware" => [RawBodyToJson::class]
    ], function () {
        Router::post("/signup", "UserController@signup");
        Router::post("/signin", "UserController@signin");
        Router::get("/refresh", "UserController@refresh");

        Router::group([
            "middleware" => [Authenticate::class]
        ], function () {
            Router::post("/logout", "UserController@logout");
        });
    });

    Router::group([
        "prefix" => "/drive",
        "middleware" => [Authenticate::class]
    ], function () {
        // TODO: implements methods
        Router::get("/file", "DriveController@download");
        Router::post("/file", "DriveController@upload");

        Router::get("", "DriveController@getFiles");
        Router::get("/file-preview", "DriveController@getFile");
        Router::get("/file-info", "DriveController@getFileInfo");


        Router::group([
            "middleware" => [RawBodyToJson::class]
        ], function() {
            Router::delete("/file", "DriveController@delete");
            Router::post("file/copy", "DriveController@copy");
            Router::patch("file/rename", "DriveController@rename");
            Router::patch("file/move", "DriveController@move");
        });
    });
});

Router::get('/', 'VueController@run')->setMatch('//');

Router::error(function(Request $request, Exception $exception) {
    $response = Router::response();

    $response->httpCode(500);
    switch (get_class($exception)) {
        case BadRequestHttpException::class: {
            $response->httpCode(400);
            break;
        }

        case NotAuthorizedHttpException::class: {
            $response->httpCode(401);
            break;
        }

        case NotFoundHttpException::class: {
            $response->httpCode(404);
            break;
        }
    }


    $response->json([
        'status' => 'error',
        'message' => $exception->getMessage()
    ]);
});