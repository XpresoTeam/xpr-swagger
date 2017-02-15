XprSwagger
=======

- Copyright: Xpreso Software Ltd. 2017
- Author: Pablo Santiago Sanchez
- License: Apache 2.0
- Current Stage: POC (Proof Of Concept). Versioning will be kept as 0.0.* until first roadmap is completed.

Main Features:
- Plots all the available API (routes) as a Swagger 2.0 description.

TODOs:
- Recursive plot of definitions
- Write tests based on the Swagger Pet Store example
- Add responses 500 based on thrown exceptions by the controller

Use examples for this library:
- Allow the frontend to compare and validate the contract it has with the actual API 
  contract from the backend  
- Make API testing easier by creating automatic input and checking the supposed output
  from the contract
- Allow check how much of the API is already completed when comparing to the defined
  contract.
- Make it easier to keep the contract updated with the actual backend API.

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
