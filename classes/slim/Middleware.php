<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Slim To enable CORS
 */
// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });

/**
 * Slim Middleware
 */
// $app->add(function ($req, $res, $next) {
//     $response = $next($req, $res);
//     return $response
//         ->withHeader('Access-Control-Allow-Origin', '*')
//         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
// });