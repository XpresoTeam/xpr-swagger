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

namespace XprSwagger\Service\Provider;

use Illuminate\Routing\Router;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

abstract class SwaggerGenerator
{
    static public $router;

    static public $routes;

    static private $templatePath =
        __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
        'Resources' . DIRECTORY_SEPARATOR . 'skeletons' . DIRECTORY_SEPARATOR .
        'swagger-template' . DIRECTORY_SEPARATOR . 'swagger.yml';

    static private $primitives = [
        'Collection',
        'Mixed',
        'Nullable',
        'String'
    ];

    static public function setRouter(Router $router)
    {
        self::$router = $router;
        self::$routes = self::$router->getRoutes();
    }

    static public function getSwaggerPaths()
    {
        $routesInfo = [];
        $definitions = [];
        foreach (self::$routes as $route) {
            $url = $route->uri();
            if (strpos($url, '/') !== 0) {
                $url = '/' . $url;
            }
            $routesInfo[$url] = self::processMethodAndAction($route, $definitions, $url);
        }

        $definitions = self::prepareDefinitions($definitions);

        return self::prepareOutput($routesInfo, $definitions);
    }

    static public function processMethodAndAction($route, &$definitions, &$url)
    {
        $methods = $route->methods();
        $actionSignature = self::getActionSignature($route->getActionName(), $definitions, $url);

        $signature = [];
        foreach ($methods as $method) {
            if ($method != 'HEAD') {
                $signature[strtolower($method)] = $actionSignature;
            }
        }

        return $signature;
    }

    static public function getActionSignature($actionSignature, &$definitions, &$url)
    {
        $data = explode('@', $actionSignature);

        $signature = [];

        $ref = new \ReflectionClass($data[0]);

        $tagData = explode('\\',$data[0]);

        if (isset($data[1]) && $data[1]) {
            $method = $ref->getMethod($data[1]);
            $parameters = $method->getParameters();
            $returnType = $method->getReturnType();
            $return = self::filterReturn($returnType);
            /** @var \ReflectionParameter $parameter */
            $signature['summary'] = $method->getDocComment() ?
                trim(str_replace(['/**', ' * ', '*/', "\n"], '', $method->getDocComment())) :
                'No documentation available';
            $signature['description'] = $method->getDocComment() ?
                trim(str_replace(['/**', ' * ', '*/', "\n"], '', $method->getDocComment())) :
                'No documentation available';
            $signature['tags'] = [lcfirst($tagData[0])]; //[lcfirst(str_replace('Controller', '', $tagData[0].ucfirst($tagData[count($tagData) -1])))];
            $signature['operationId'] = str_replace('Controller','', lcfirst($tagData[count($tagData) -1])).ucFirst(($data[1] == 'index') ? 'get' : $data[1]);
            $signature['parameters'] = [];
            foreach ($parameters as $parameter) {
                $signature['parameters'][] = [
                    'name' => $parameter->getName(),
                    'in' => strstr($url, '{'.$parameter->getName().'}') ? 'path' : 'query',
                    'description' => $parameter->getName(),
                    'required' => true,
                    'type' => 'string'
                ];
            }
            $signature['responses'] = [
                'default' => [
                    'description' => 'Response should be ' . $return,
                    'schema' => [
                        '$ref' => '#/definitions/' . str_replace('\\', '_', $return)
                    ]
                ]
            ];

            $definitions[$return] = $return;

            if (count($signature['parameters']) == 0) {
                unset($signature['parameters']);
            }

        } else {
            $signature['summary'] = 'Closures have no documentation';
            $signature['description'] = 'Closures have no documentation';
            $signature['tags'] = ['closure'];
            $signature['responses'] = 'string';
            $signature['responses'] = [
                'default' => [
                    'description' => 'Can\'t guess return from Closures',
                    'schema' => [
                        '$ref' => '#/definitions/Mixed'
                    ]
                ]
            ];
            $definitions['Mixed'] = 'Mixed';
        }

        return $signature;
    }

    static public function filterReturn($returnType)
    {
        $returnType = ($returnType instanceof \ReflectionType) ? $returnType->__toString() : $returnType;

        if (is_null($returnType)) {
            $returnType = 'Nullable';
        }

        if ($returnType === 'array') {
            $returnType = 'Collection';
        }

        if ($returnType === 'string') {
            $returnType = 'String';
        }

        return $returnType;
    }

    static public function prepareDefinitions($definitions)
    {
        if (in_array('Collection', $definitions)) {
            $definitions['Collection'] = [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => 'number',
                            'description' => 'none'
                        ]
                    ]
                ]
            ];
        }

        if (in_array('Nullable', $definitions)) {
            $definitions['Nullable'] = [
                'type' => 'null',
            ];
        }

        if (in_array('String', $definitions)) {
            $definitions['String'] = [
                'type' => 'string',
            ];
        }

        if (in_array('Mixed', $definitions)) {
            $definitions['Mixed'] = [
                'type' => 'string',
            ];
        }


        foreach ($definitions as $k => $definition) {
            if (!in_array($k, self::$primitives)) {
                unset($definitions[$k]);
                $definitions[str_replace('\\', '_', $k)] = self::getDefinition($definition);
            }
        }

        return $definitions;
    }

    static public function getDefinition($className)
    {
        $arr = (class_exists($className)) ? class_parents($className) : [];

        if (in_array('Illuminate\Database\Eloquent\Model', $arr)) {
            return self::getEloquentDefinition($className);
        } else {
            return self::getPOPODefinition($className);
        }
    }

    static public function getEloquentDefinition($className)
    {
        /** @var  Model */
        $class = new $className;

        $classDefinition = [
            'type' => 'object',
            'properties' => []
        ];

        $column_names = Schema::getColumnListing($class->getTable());

        foreach ($column_names as $column) {
            $classDefinition['properties'][$column] = [
                'type' => 'string',
                'description' => 'none'
            ];
        }

        return $classDefinition;
    }

    static public function getPOPODefinition($className)
    {
        $ref = new \ReflectionClass($className);

        $classDefinition = [
            'type' => 'object',
            'properties' => []
        ];

        $props = $ref->getProperties();
        /** @var \ReflectionProperty $prop */
        foreach ($props as $prop) {

            /**
             * check if property is a class or a primitive
             */
            $prop->class;
            $classDefinition['properties'][$prop->getName()] = [
                'type' => 'string',
                'description' => 'none'
            ];
        }

        return $classDefinition;
    }

    static public function prepareOutput($routesInfo, $definitions)
    {
        $template = file_get_contents(self::$templatePath);
        $yaml = Yaml::parse($template);

        $yaml['host'] = str_replace(['http://', 'https://'], '', strtolower(config('app.url')));
        $yaml['paths'] = $routesInfo;
        $yaml['definitions'] = $definitions;

        return $yaml = Yaml::dump($yaml, 10, 2);
    }
}