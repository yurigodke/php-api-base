<?php

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Base api",
 *     version="0.0.1",
 *     @OA\Contact(name="Base API Team"),
 *   )
 * )
 */


include(__DIR__ . '/model/_model.php');
include(__DIR__ . '/controller/_controller.php');
include(__DIR__ . '/utils/email.php');

use \Slim\App as SlimApi;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

$routes = array(
	'users',
	'recovery'
);

define('CONFIGFILE', SRCPATH . '/config.php');

if (file_exists(CONFIGFILE)) {
	include(CONFIGFILE);

	$app = new SlimApi();

	$container = $app->getContainer();
	unset($container['errorHandler']);
	unset($container['phpErrorHandler']);

	$container['logger'] = function($c) {
		$logger = new Logger('base');
		$file_handler = new StreamHandler('/var/www/html/logs/' . date('Ymd') . '.log');
		$logger->pushHandler($file_handler);
		return $logger;
	};

	$container['notFoundHandler'] = function ($c) {
		return function ($request, $response) use ($c) {
			$data = array(
				'error' => 'Page not found'
			);

			return $response->withJson($data, 404);
		};
	};

	foreach ($routes as $routeValue) {
		include(__DIR__ . "/model/$routeValue.php");
		include(__DIR__ . "/controller/$routeValue.php");
		include(__DIR__ . "/routes/$routeValue.php");
	}
} else {
	header('Location: /config/create');
}

$app->run();
