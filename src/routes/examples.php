<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

include(__DIR__ . '/../controller/examples.php');

$exampleCtrl = new ExampleController();

/**
 *	@OA\Post(
 *		path="/example",
 *		summary="Adds a new example",
 *		@OA\RequestBody(
 *			@OA\JsonContent(ref="#/components/schemas/addExample")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/ExampleResponse")
 *		)
 *	)
 */
$app->post('/example', function (Request $request, Response $response, array $args) {
	global $exampleCtrl;
	$payload = $request->getParsedBody();
	$this->logger->debug('[route] /example', array(
		'method' => 'POST',
		'email' => $payload['email']
	));

	$dataResponse = $exampleCtrl->createExample($payload);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});

/**
 *	@OA\Get(
 *		path="/example",
 *		summary="Get example list",
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/ExampleResponse")
 *		)
 *	)
 */
$app->get('/example', function (Request $request, Response $response, array $args) {
	global $exampleCtrl;

	$dataResponse = $exampleCtrl->getExampleList();

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});

/**
 *	@OA\Get(
 *		path="/example/{exampleId}",
 *		summary="Get example by id",
 *		@OA\Parameter(
 *			name="exampleId",
 *			in="path",
 *			required=true,
 *			@OA\Schema(type="string")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/ExampleResponse")
 *		)
 *	)
 */
$app->get('/example/{exampleId}', function (Request $request, Response $response, array $args) {
	global $exampleCtrl;
	$exampleId = $args['exampleId'];

	$dataResponse = $exampleCtrl->getExample($exampleId);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});
