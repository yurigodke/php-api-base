<?php

/**
 * @OA\SecurityScheme(
 *  securityScheme="token_auth",
 *  in="header",
 *  type="http",
 *  scheme="bearer",
 *  bearerFormat="JWT"
 * )
 *
 * @OA\Schema (
 *  schema="SecurityError",
 *  @OA\Property(
 *   property="error",
 *   type="string"
 *  )
 * )
 */
class Auth {
	function __construct($request) {
		$this->token;
		$this->userData;

		$this->startConnection();
		$this->getTokenId($request);
		$this->getUserByToken();
	}

	private function startConnection() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		$dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;

		$this->respData;

		try {
			$this->conn = new PDO($dsn, DBUSER, DBPASS);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$errorMsg = $e->getMessage();

			$this->logger->error('[model] auth connection', $errorMsg);
		}
	}

	private function checkLastToken($userId) {
		$tokenChecked = false;
		$logName = '[model] auth.checkLastToken';

		try {
			$query = 'SELECT tokenId FROM tokens WHERE userId=:userId ORDER BY datetime DESC LIMIT 1;';

			$queryParams = array(
				'userId' => $userId
			);

			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$this->conn->commit();

			if ($stmt->execute($queryParams)) {
				$tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (isset($tableData[0])) {
					$tokenData = $tableData[0];

					$tokenChecked = $tokenData['tokenId'] == $this->token;

					$this->logger->debug($logName, array(
						'tokenChecked' => $tokenChecked
					));
				}

			}
		} catch (PDOException $err) {
			$this->logger->error($logName, array(
				'errorId' => $err->errorInfo[1],
				'message' => $err->errorInfo[2]
			));

			$this->userData = array(
				'error' => 'Could not login user.'
			);
		}

		return $tokenChecked;
	}

	private function getUserByToken() {
		$logName = '[model] auth.getUserByToken';
		try {
			$query = 'SELECT users.*, tokens.type FROM users LEFT JOIN tokens ON users.userId = tokens.userId WHERE tokenId=:tokenId AND type=:type;';

			$queryParams = array(
				'tokenId' => $this->token
			);

			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$this->conn->commit();

			if ($stmt->execute($queryParams)) {
				$tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if (isset($tableData[0])) {
					$userData = $tableData[0];

					if ($this->checkLastToken($userData['userId'])) {
						$this->userData = $userData;
					}

					$this->logger->debug($logName, $this->userData);
				}

			}
		} catch (PDOException $err) {
			$this->logger->error($logName, array(
				'errorId' => $err->errorInfo[1],
				'message' => $err->errorInfo[2]
			));

			$this->userData = array(
				'error' => 'Could not login user.'
			);
		}
	}

	private function getTokenId($request) {
		$headerLine = $request->getHeaderLine('Authorization');
		$handleToken = explode(' ', $headerLine);

		$this->token = $handleToken[1];
	}

	public function checkLevel($targetLevel) {
		$returnData;

		if (isset($this->userData['userId']) && $this->userData['level'] <= $targetLevel) {
			$returnData = true;
		} else {
			$returnData = array(
				'content' => array(
					'error' => 'Invalid token'
				),
				'statusCode' => 401
			);
		}

		return $returnData;
	}

	public function checkUser($targetUser) {
		$returnData;

		if (isset($this->userData['userId']) && ($this->userData['userId'] == $targetUser || $this->userData['level'] == 0)) {
			$returnData = true;
		} else {
			$returnData = array(
				'content' => array(
					'error' => 'Invalid token'
				),
				'statusCode' => 401
			);
		}

		return $returnData;
	}

	public function checkType($targetType) {
		$returnData;

		if (isset($this->userData['userId']) && ($this->userData['type'] == $targetType || $this->userData['level'] == 0)) {
			$returnData = true;
		} else {
			$returnData = array(
				'content' => array(
					'error' => 'Invalid token'
				),
				'statusCode' => 401
			);
		}

		return $returnData;
	}

	public function allCheck($level = null, $type = null, $userId = null) {
		$errorMsg = null;

		$levelCheck = null;
		if (isset($level)) {
			$levelCheck = $this->checkLevel($level);
		}

		$typeCheck = null;
		if (isset($type)) {
			$typeCheck = $this->checkType($type);
		}

		$userIdCheck = null;
		if (isset($userId)) {
			$userIdCheck = $this->checkUser($userId);
		}

		if (isset($levelCheck) && $levelCheck !== true) {
			$errorMsg = $levelCheck;
		}

		if ($errorMsg === null && isset($typeCheck) && $typeCheck !== true) {
			$errorMsg = $typeCheck;
		}

		if ($errorMsg === null && isset($userIdCheck) && $userIdCheck !== true) {
			$errorMsg = $userIdCheck;
		}

		return $errorMsg;
	}
}
