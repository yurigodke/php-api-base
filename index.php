<?php
require __DIR__ . '/vendor/autoload.php';

define('BASEPATH', getcwd());
define('SRCPATH', BASEPATH . '/src');

$environment = 'prod';

if (array_key_exists('ENVIRONMENT', $_ENV)) {
	$environment = $_ENV['ENVIRONMENT'];
}

define('ENVIRONMENT', $environment);

$requestUri = $_SERVER['REQUEST_URI'];
$requestUriList = array_filter(explode('/', $requestUri));

if ($requestUri == '/docs/example.yaml') {
	$openapi = \OpenApi\scan('./src');
	header('Content-Type: application/x-yaml');
	echo $openapi->toYaml();
} else if ($requestUriList[1] == 'config') {
	$configPageFile = BASEPATH . '/config/' . $requestUriList[2] . '/index.php';

	if (is_file($configPageFile)) {
		include($configPageFile);
	}
} else {
	include('src/api.php');
}
