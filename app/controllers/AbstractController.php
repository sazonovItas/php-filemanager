<?php

namespace app\controllers;

use Pecee\Http\Request as Request;
use Pecee\Http\Response as Response;
use Pecee\SimpleRouter\SimpleRouter as Router;

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
        $this->request = Router::request();
        $this->response =  new Response($this->request);
        $this->setCors();
    }

    public function setCors()
    {
        $this->response->header('Access-Control-Allow-Origin: http://0.0.0.0:8080');
        $this->response->header('Access-Control-Allow-Credentials: true');
        $this->response->header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        // $this->response->header('Access-Control-Max-Age: 3600');
    }
}
