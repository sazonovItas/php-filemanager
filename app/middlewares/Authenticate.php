<?php

namespace app\middlewares;


use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class Authenticate implements IMiddleware
{
    // TODO: change secret key on env variable
    public function handle(Request $request): void
    {

    }
}