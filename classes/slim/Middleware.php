<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Tuupola\Middleware\JwtAuthentication;
use Tuupola\Middleware\JwtAuthentication\RequestMethodRule;
use Tuupola\Middleware\JwtAuthentication\RequestPathRule;
use Buff\classes\services\ResponseService;
use Buff\classes\utils\Environment;
use Buff\classes\middlewares\PmsAuthentication;
use Buff\classes\middlewares\UsrIPAddress;

// alexberce/Slim-API
// tuupola/slim-api-skeleton
/**
 * Slim To enable CORS
 */
// $app->options('/{routes:.+}', function ($request, $response, $args) {
//     return $response;
// });

/**
 * Slim Middleware
 */
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-Token')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
		->withHeader('Allow', 'GET, POST, PUT, DELETE, OPTIONS')
		->withHeader('Content-Type', 'application/json; charset=UTF-8');
});
/**
 * Slim UsrIPAddress
 */
$app->add(
    new UsrIPAddress(true,['10.0.0.1', '10.0.0.2'])
);
/**
 * Slim JwtAuthentication
 */
$app->add(
	new JwtAuthentication([
		"secure" => false,
		"secret" => Environment::$jwtSecretKey,
        "relaxed" => ["localhost", "127.0.0.1"],
        "algorithm" => Environment::$jwtAlgorithm,
        "header" => "X-Token",
        "regexp" => "/(.*)/",
        "cookie" => "X-Cookie",
        "attribute" => "token",
		"logger" => $app->getContainer()['logger'],
		"rules" => [
            new RequestPathRule([
                "path"   => ["/api/members"]
            ]),
            new RequestMethodRule([
                "ignore" => ["OPTIONS"]
            ]),
            new RequestMethodRule([
                "ignore" => ["POST"],
                "path"   => ["/api/members/create","/api/members/auth"]
            ]),
            new RequestMethodRule([
                "ignore" => ["GET"],
                "path"   => ["/api/members/show"]
            ])
        ],
        "before" => function ($request, $arguments) use ($container) {

        },
        "after" => function ($response, $arguments) use ($container) {
            
        },
        "error" => function ($response, $arguments) use ($container) {
        	$responseService = new ResponseService();
        	$responseService
        	    ->withFailure()
        	    ->withCode(9001);
            
			return $response
                ->withStatus(200)
                ->write($responseService->write());
		}
    ])
);
/**
 * Slim PmsAuthentication
 */
$app->add(
    new PmsAuthentication()
);
