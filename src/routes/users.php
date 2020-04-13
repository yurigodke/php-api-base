<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

include(__DIR__ . '/../controller/users.php');

$userCtrl = new UserController();

/**
 *	@OA\Post(
 *		path="/users",
 *		tags={"User"},
 *		summary="Adds a new users",
 *		@OA\RequestBody(
 *			@OA\JsonContent(ref="#/components/schemas/addUser")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/getUser")
 *		)
 *	)
 */

$app->post('/users', function (Request $request, Response $response, array $args) {
	global $userCtrl;
	$payload = $request->getParsedBody();
	$this->logger->debug('[route] /users', array(
		'method' => 'POST',
		'email' => $payload['email']
	));

	$dataResponse = $userCtrl->createUser($payload);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});


/**
 *	@OA\Get(
 *		path="/users",
 *		tags={"User"},
 *		summary="Get user list",
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/getUserList")
 *		)
 *	)
 */

$app->get('/users', function (Request $request, Response $response, array $args) {
 global $userCtrl;

 $dataResponse = $userCtrl->getUserList();

 return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});


/**
 *	@OA\Get(
 *		path="/users/{userId}",
 *		tags={"User"},
 *		summary="Get user by id",
 *		@OA\Parameter(
 *			name="userId",
 *			in="path",
 *			required=true,
 *			@OA\Schema(type="string")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/getUser")
 *		)
 *	)
 */

$app->get('/users/{userId}', function (Request $request, Response $response, array $args) {
	global $userCtrl;
	$userId = $args['userId'];

	$dataResponse = $userCtrl->getUser($userId);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});

/**
 *	@OA\Put(
 *		path="/users/{userId}",
 *		tags={"User"},
 *		summary="Edit user by id",
 *		@OA\RequestBody(
 *			@OA\JsonContent(ref="#/components/schemas/addUser")
 *		),
 *		@OA\Parameter(
 *			name="userId",
 *			in="path",
 *			required=true,
 *			@OA\Schema(type="string")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK",
 *			@OA\JsonContent(ref="#/components/schemas/getUser")
 *		)
 *	)
 */

$app->put('/users/{userId}', function (Request $request, Response $response, array $args) {
	global $userCtrl;

	$userId = $args['userId'];

	$payload = $request->getParsedBody();
	$this->logger->debug('[route] /users', array(
		'method' => 'PUT',
		'userId' => $userId
	));

	$dataResponse = $userCtrl->editUser($payload, $userId);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});


/**
 *	@OA\Delete(
 *		path="/users/{userId}",
 *		tags={"User"},
 *		summary="Delete user by id",
 *		@OA\Parameter(
 *			name="userId",
 *			in="path",
 *			required=true,
 *			@OA\Schema(type="string")
 *		),
 *		@OA\Response(
 *			response=200,
 *			description="OK"
 *		)
 *	)
 */

$app->delete('/users/{userId}', function (Request $request, Response $response, array $args) {
	global $userCtrl;
	$userId = $args['userId'];

	$dataResponse = $userCtrl->deleteUser($userId);

	return $response->withJson($dataResponse['content'], $dataResponse['statusCode']);
});
