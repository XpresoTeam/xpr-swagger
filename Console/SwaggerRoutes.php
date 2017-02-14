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

namespace XprSwagger\Console;

use Illuminate\Console\Command;
use XprSwagger\Service\Provider\SwaggerGenerator;
use Illuminate\Routing\Router;

class SwaggerRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xpreso:swagger:routes {swaggerfile?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xpreso Swagger file generation';

    /**
     * Router instance to get all routes
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $swaggerfile = $this->argument('swaggerfile');
        SwaggerGenerator::setRouter($this->router);
        $yaml = SwaggerGenerator::getSwaggerPaths();
        if ($swaggerfile) {
            file_put_contents($swaggerfile, $yaml);
        } else {
            echo $yaml;
        }

    }

}