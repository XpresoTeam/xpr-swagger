<?php

namespace XprSwagger\Controller;

use App\Http\Controllers\Controller;
use XprSwagger\Service\Provider\SwaggerGenerator;

class SwaggerController extends Controller
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
