<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$recoveryCtrl = new RecoveryController();

/**
 *	@OA\Post(
 *		path="/recovery",
 *		tags={"Recovery"},
 *		summary="Adds a new users",
 *		@OA\RequestBody(
 *			@OA\JsonContent(ref="#/components/schemas/recoveryPass")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK"
 *		)
 *	)
 */

$app->post('/recovery', function (Request $request, Response $response, array $args) {
	global $recoveryCtrl;
	$payload = $request->getParsedBody();
	$this->logger->debug('[route] /users', array(
		'method' => 'POST',
		'email' => $payload['email']
	));

	$dataResponse = $recoveryCtrl->sendRecovery($payload);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});
