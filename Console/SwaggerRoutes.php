<?php

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