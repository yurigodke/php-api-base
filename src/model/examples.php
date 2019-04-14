<?php
/**
 *
 */

require(__DIR__ . '/../config.php');

class Examples {
	function __construct() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		$dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;

		try {
			$this->conn = new PDO($dsn, DBUSER, DBPASS);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$errorMsg = $e->getMessage();

			$this->logger->error('[model] examples connection', $errorMsg);
		}
	}

	private function isConnected() {
		return isset($this->conn);
	}

	/**
	* @OA\Schema (
	*	schema="addExample",
	*	@OA\Property(
	*		property="name",
	*		type="string"
	*	),
	*	@OA\Property(
	*		property="email",
	*		type="string"
	*	),
	*	@OA\Property(
	*		property="pass",
	*		type="string"
	*	),
	* )
	*/
	public function add($dataInfo) {
		$respData;

		if ($this->isConnected) {
			$respData = array(
				'error' => 'Connection error, please try later.'
			);
		} else {
			$query = 'INSERT INTO example (name, email, pass) VALUES (:name, :email, :pass)';
			$queryParams = array(
				':name' => $dataInfo['name'],
				':email' => $dataInfo['email'],
				':pass' => md5($dataInfo['pass'])
			);

			$respData = array(
				'error' => 'Could not register example.'
			);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($query);
				$this->conn->commit();

				if ($stmt->execute($queryParams)) {
					$addExampleId = $this->conn->lastInsertId();

					$respData = $this->getById($addExampleId);
					$this->logger->debug('[model] examples.add', $respData);
				}
			} catch (PDOException $err) {
				$this->logger->error('[model] examples.add', array(
					'errorId' => $err->errorInfo[1],
					'message' => $err->errorInfo[2]
				));

				if ($err->errorInfo[1] == 1062) {
					$respData = array(
						'error' => 'Example already exists.'
					);
				}
			}

			return $respData;
		}
	}

	/**
	 *	@OA\Schema (
	 *		schema="ExampleResponse",
	 *		type="array",
	 *		@OA\Items (
	 *			@OA\Property(
	 *				property="id",
	 *				type="integer"
	 *			),
	 *			@OA\Property(
	 *				property="name",
	 *				type="string"
	 *			),
	 *			@OA\Property(
	 *				property="email",
	 *				type="string"
	 *			)
	 *		)
	 *	)
	*/
	public function getById($id) {
		$respData;

		if ($this->isConnected) {
			$respData = array(
				'error' => 'Connection error, please try later.'
			);
		} else {
			$query = 'SELECT exampleId, name, email FROM example WHERE exampleId=:exampleId;';
			$queryParams = array(
				':exampleId' => $id
			);

			$respData = array(
				'error' => 'Could not find example.'
			);

			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$this->conn->commit();

			if ($stmt->execute($queryParams)) {
				$exampleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (isset($exampleData[0])) {
					$respData = $exampleData;
				}
			}

			$this->logger->debug('[model] examples.getById', array_merge($respData, array(
				'exampleId' => '$id'
			)));
		}

		return $respData;
	}

	public function getList() {
		$respData;

		if ($this->isConnected) {
			$respData = array(
				'error' => 'Connection error, please try later.'
			);
		} else {
			$query = 'SELECT exampleId, name, email FROM example';

			$respData = array(
				'error' => 'Could not have examples.'
			);

			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			$this->conn->commit();

			if ($stmt->execute($queryParams)) {
				$exampleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($exampleData)) {
					$respData = $exampleData;
				}
			}

			$this->logger->debug('[model] examples.getList', array_merge($respData));
		}

		return $respData;
	}
}
