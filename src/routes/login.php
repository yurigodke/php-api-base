<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$loginCtrl = new LoginController();

/**
 *	@OA\Post(
 *		path="/login",
 *		tags={"Login"},
 *		summary="Login users",
 *		@OA\RequestBody(
 *			@OA\JsonContent(ref="#/components/schemas/loginUser")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK"
 *		)
 *	)
 */

$app->post('/login', function (Request $request, Response $response, array $args) {
	global $loginCtrl;
	$payload = $request->getParsedBody();
	$this->logger->debug('[route] /users', array(
		'method' => 'POST',
		'email' => $payload['email']
	));

	$dataResponse = $loginCtrl->getLogin($payload);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});
