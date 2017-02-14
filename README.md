XprSwagger
=======

- Copyright: Xpreso Software Ltd. 2017
- Author: Pablo Santiago Sanchez
- License: Apache 2.0

Main Features:
- Plots all the available API (routes) as a Swagger 2.0 description.

TODO:
- Decide if the object returned by the controller action should be ploted recursively

## Installation:

### composer.json

    [...]
    "require-dev" : {
        [...]
        "xpreso/xpr-swagger": "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/XpresoTeam/xpr-swagger.git";
        "no-api" : true
    }],
    [...]
    
### app.php

Add the XprSwagger\XprModuleProvider to it.

## Usage:

Just point to /swagger.yml in your application (ex: http://localhost:8000/swagger.yml). 

ATTENTION! FOR PRODUCTION ENVIRONMENT, DUMP THE SWAGGER FILE TO YOUR PUBLIC FOLDER!

    php artisan xpreso:swagger:routes public/swagger.yml
