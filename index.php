<?php
require __DIR__ . '/vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri == '/docs/example.yaml') {
	$openapi = \OpenApi\scan('./src');
	header('Content-Type: application/x-yaml');
	echo $openapi->toYaml();
} else {
	include('src/api.php');
}
