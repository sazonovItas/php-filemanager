<?php

namespace app\controllers;

use Pecee\Http\Request as Request;
use Pecee\Http\Response as Response;

abstract class AbstractController
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Response
     */
    protected Response $response;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response($this->request);
    }
}
