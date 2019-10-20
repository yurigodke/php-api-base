<?php

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Example api",
 *     version="0.0.1",
 *     @OA\Contact(name="Example API Team"),
 *   )
 * )
 */

define('CONFIGFILE', SRCPATH . '/config.php');

$environment = 'prod';

if (array_key_exists('ENVIRONMENT', $_ENV)) {
	$environment = $_ENV['ENVIRONMENT'];
}

define('ENVIRONMENT', $environment);

if (file_exists(CONFIGFILE)) {
	include(CONFIGFILE);

	echo DBHOST;
} else {
	header('Location: /config/create');
}

// use \Slim\App as SlimApi;
// use \Monolog\Logger;
// use \Monolog\Handler\StreamHandler;
//
// $app = new SlimApi();
//
// $container = $app->getContainer();
// unset($container['errorHandler']);
// unset($container['phpErrorHandler']);
//
// $container['logger'] = function($c) {
// 	$logger = new Logger('example');
// 	$file_handler = new StreamHandler('/var/www/logs/' . date('Ymd') . '.log');
// 	$logger->pushHandler($file_handler);
// 	return $logger;
// };
//
// $container['notFoundHandler'] = function ($c) {
// 	return function ($request, $response) use ($c) {
// 		$data = array(
// 			'error' => 'Page not found'
// 		);
//
// 		return $response->withJson($data, 404);
// 	};
// };
//
// // include(__DIR__ . '/routes/examples.php');
//
// $app->run();
