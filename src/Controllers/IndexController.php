<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class IndexController
{
    protected $view;

    public function __construct(Twig $view) {
        $this->view = $view;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, $args) {

        $response->getBody()->write("Welcome!");
        return $response;
    }

}