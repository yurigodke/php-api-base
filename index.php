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
$requestUriList = array_values(array_filter(explode('/', $requestUri)));

$configFilePath = BASEPATH . '/src/config.php';

if ($requestUri == '/docs/example.yaml') {
	$openapi = \OpenApi\scan('./src');
	header('Content-Type: application/x-yaml');
	echo $openapi->toYaml();
} else if ($requestUriList[0] == 'config') {
		$configPageName = 'user';

		if (isset($requestUriList[1])) {
			$configPageName = $requestUriList[1];
		}

		$configPageFile = BASEPATH . '/config/' . $configPageName . '/index.php';

		if (is_file($configPageFile)) {
			include($configPageFile);
		}
} else {
	if (is_file($configFilePath)) {
		include('src/api.php');
	} else {
		header("Location: /config");
	}
}
