<?php
class Login extends Model {
	function __construct() {
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

			$this->logger->error('[model] login connection', $errorMsg);
		}
	}

	/**
	 * @OA\Schema (
	 * 	schema="loginUser",
	 * 	@OA\Property(
	 * 		property="email",
	 * 		type="string"
	 * 	),
	 * 	@OA\Property(
	 * 		property="pass",
	 * 		type="string"
	 * 	)
	 * )
	 */
	public function registerLogin($paramsData) {
		$logName = '[model] recovery.registerLogin';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('insert', 'tokens', $paramsData);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$this->respData = array(
						'token' => $paramsData['tokenId']['value']
					);

					$this->logger->debug($logName, $this->respData);
				}
			} catch (PDOException $err) {
				$this->logger->error($logName, array(
					'errorId' => $err->errorInfo[1],
					'message' => $err->errorInfo[2]
				));

				$this->respData = array(
					'error' => 'Could not login user.'
				);
			}

			return $this->respData;
		}
	}
}
