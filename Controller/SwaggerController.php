<?php

namespace XCoreDev\Controller;

use XCore\Controller\ApiController;
use XCoreDev\Service\Provider\SwaggerGenerator;
use Illuminate\Routing\Router;

class SwaggerController extends ApiController
{
    /**
     * Returns the API as a Swagger 2.0 YAML file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index($data = null)
    {
        SwaggerGenerator::setRouter(self::$router);
        $yaml = SwaggerGenerator::getSwaggerPaths();

        return response($yaml);
    }
}
