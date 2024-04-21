<?php

namespace app\middlewares;

use app\domain\service\TokenService;
use app\exceptions\NotAuthorizedHttpException;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class Authenticate implements IMiddleware
{
    public function handle(Request $request): void
    {
        $accessToken = $request->getHeader('Authorization');
        if ($accessToken === null) {
            throw new NotAuthorizedHttpException('Authorization header missing');
        }

        $accessToken = explode(' ', $accessToken)[1] ?? '';
        $payload = TokenService::validateToken($accessToken, getenv("JWT_ACCESS_SECRET_KEY"));
        if (!$payload) {
            throw new NotAuthorizedHttpException('Access token is invalid');
        }

        $payload_array = (array)json_decode(json_encode($payload), true);
        $request->access_token_payload = $payload_array['payload'];
    }
}