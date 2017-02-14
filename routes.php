<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
 */
Route::group([
    'prefix'    => '/',
    'namespace' => 'XprSwagger\Controller'
], function () {
    Route::get('/swagger.yml', 'SwaggerController@index');
});