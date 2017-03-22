<?php

/**
 * Copyright 2007 Xpreso Software Ltd
 * @Author Pablo Santiago Sanchez <psanchez@xpreso.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace XprSwagger\Controller;

use App\Http\Controllers\Controller;
use XprSwagger\Service\Provider\SwaggerGenerator;
use Illuminate\Routing\Router;

class SwaggerController extends Controller
{
    /**
     * Router instance to get all routes
     *
     * @var \Illuminate\Routing\Router
     */
    protected static $router;

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        static::$router = $router;
    }

    /**
     * Returns the API as a Swagger 2.0 YAML file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        SwaggerGenerator::setRouter(self::$router);
        $yaml = SwaggerGenerator::getSwaggerPaths();

        return response($yaml);
    }

    /**
     * Get the router instance.
     *
     * @return \Illuminate\Routing\Router
     */
    public static function getRouter()
    {
        return static::$router;
    }

    /**
     * Set the router instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public static function setRouter(Router $router)
    {
        static::$router = $router;
    }
}
