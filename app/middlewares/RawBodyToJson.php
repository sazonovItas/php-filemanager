<?php

namespace app\middlewares;

use app\exceptions\BadRequestHttpException;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class RawBodyToJson implements IMiddleware
{

    /**
     * @param Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        $rawBody = file_get_contents('php://input');

        if ($rawBody) {
            $body = json_decode($rawBody, true);
            foreach ($body as $key => $value) {
                $request->$key = $value;
            }
        }
    }
}