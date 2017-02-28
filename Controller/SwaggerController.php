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
