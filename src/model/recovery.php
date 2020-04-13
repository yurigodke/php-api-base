<?php
class Recovery extends Model {
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

			$this->logger->error('[model] user connection', $errorMsg);
		}
	}

	/**
	 * @OA\Schema (
	 * 	schema="recoveryPass",
	 * 	@OA\Property(
	 * 		property="email",
	 * 		type="string"
	 * 	)
	 * )
	 */
	public function sendRecovery($paramsData, $emailTo) {
		$logName = '[model] recovery.sendRecovery';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('insert', 'tokens', $paramsData);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$email = new Email();

					$email->addAddress($emailTo);

					$email->setTemplate('passRecoveryInfo', array(
						'token' => $paramsData['tokenId']['value']
					));

					$sendStatus = $email->send();

					$this->respData = array(
						'sended' => $sendStatus
					);

					if (!$sendStatus) {
						$this->respData['error'] = $email->ErrorInfo;
					}

					$this->logger->debug($logName, $this->respData);
				}
			} catch (PDOException $err) {
				$this->logger->error($logName, array(
					'errorId' => $err->errorInfo[1],
					'message' => $err->errorInfo[2]
				));

				if ($err->errorInfo[1] == 1062) {
					$this->respData = array(
						'error' => 'User already exists.'
					);
				} else {
					$this->respData = array(
						'error' => 'Could not register user.'
					);
				}
			}

			return $this->respData;
		}
	}
}
