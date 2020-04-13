<?php
class User extends Model {
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
	 * 	schema="addUser",
	 * 	@OA\Property(
	 * 		property="email",
	 * 		type="string"
	 * 	),
	 * 	@OA\Property(
	 * 		property="pass",
	 * 		type="string"
	 * 	),
	 * 	@OA\Property(
	 * 		property="level",
	 * 		type="number"
	 * 	)
	 * )
	 */
	public function add($paramsData) {
		$logName = '[model] users.add';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('insert', 'users', $paramsData);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$lastId = $this->conn->lastInsertId();

					$this->getByQuery(array(
						'userId' => $lastId
					));

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

	/**
	 * @OA\Schema (
	 * 	schema="getUser",
	 * 	@OA\Property(
	 * 		property="userId",
	 * 		type="number"
	 * 	),
	 * 	@OA\Property(
	 * 		property="email",
	 * 		type="string"
	 * 	),
	 * 	@OA\Property(
	 * 		property="pass",
	 * 		type="string"
	 * 	),
	 * 	@OA\Property(
	 * 		property="level",
	 * 		type="number"
	 * 	)
	 * )
	 */
	public function getByQuery($query) {
		$logName = '[model] users.getByQuery';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('select', 'users', array(), $query);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

					if (isset($tableData[0])) {
						$this->respData = $tableData[0];
					}

					$this->logger->debug($logName, array_merge($this->respData, $query));
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
						'error' => 'Could not get user.'
					);
				}
			}

			return $this->respData;
		}
	}


	/**
	 * @OA\Schema (
	 * 	schema="getUserList",
	 * 	type="array",
	 * 	@OA\Items(
	 *	 	@OA\Property(
	 * 			property="userId",
	 * 			type="number"
	 * 		),
	 *	 	@OA\Property(
	 *	 		property="email",
	 *	 		type="string"
	 *	 	),
	 * 		@OA\Property(
	 *	 		property="pass",
	 *	 		type="string"
	 * 		),
	 *	 	@OA\Property(
	 *	 		property="level",
	 *	 		type="number"
	 *	 	)
	 * 	)
	 * )
	 */
	public function getList() {
		$logName = '[model] users.getList';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('select', 'users', array());

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

					if (isset($tableData[0])) {
						$this->respData = $tableData;
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
						'error' => 'Could not get user.'
					);
				}
			}

			return $this->respData;
		}
	}

 public function edit($paramsData, $dataId) {
	 $logName = '[model] users.edit';

	 if ($this->isConnected()) {
		 $queryData = $this->getQueryData('update', 'users', $paramsData, $dataId);

		 try {
			 $this->conn->beginTransaction();
			 $stmt = $this->conn->prepare($queryData['query']);
			 $this->conn->commit();

			 if ($stmt->execute($queryData['data'])) {
				 $this->getByQuery($dataId);

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

	public function deleteByQuery($query) {
		$logName = '[model] users.deleteById';

		if ($this->isConnected()) {
			$queryData = $this->getQueryData('delete', 'users', array(), $query);

			try {
				$this->conn->beginTransaction();
				$stmt = $this->conn->prepare($queryData['query']);
				$this->conn->commit();

				if ($stmt->execute($queryData['data'])) {
					$this->respData = array();

					$this->logger->debug($logName, array_merge($this->respData, $query));
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
						'error' => 'Could not delete user.'
					);
				}
			}

			return $this->respData;
		}
	}
}
