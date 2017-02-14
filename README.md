XprSwagger
=======

Main Features:
- Plots all the available API (routes) as a Swagger 2.0 description.

## Installation:

### composer.json

    [...]
    "require" : {
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