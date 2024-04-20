<?php
declare(strict_types=1);

use Pecee\SimpleRouter\SimpleRouter as Router;
use app\middlewares\RawBodyToJson;

Router::setDefaultNamespace('app\controllers');

Router::group([
    "prefix" => "api/v1/auth",
    "middleware" => [RawBodyToJson::class]
], function () {
    Router::post("/signup", "UserController@signup");
    Router::post("/signin", "UserController@signin");
    Router::post("/logout", "UserController@logout");
    Router::get("/refresh", "UserController@refresh");
});

Router::get('/', 'VueController@run')->setMatch('//');

Router::error(function(Request $request, Exception $exception) {
    $response = Router::response();
    switch (get_class($exception)) {
        case NotAuthorizedHttpException::class: {
            $response->httpCode(401);
            break;
        }
        case Exception::class: {
            $response->httpCode(500);
            break;
        }
    }
    if (PROD) {
        return $response->json([]);
    } else {
        return $response->json([
            'status' => 'error',
            'message' => $exception->getMessage()
        ]);
    }
});
