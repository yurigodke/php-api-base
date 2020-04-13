<?php
class RecoveryController extends Controller {
	function __construct() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		$this->recoveryModel = new Recovery();
		$this->userModel = new User();
	}

	public function sendRecovery($requestData) {
		$response = array();

		$userData = $this->userModel->getByQuery(array(
			'email' => $requestData['email']
		));

		if (isset($userData['userId'])) {
			$paramsData = array(
				'tokenId' => array(
					'required' => true,
					'value' => md5(uniqid())
				),
				'userId' => array(
					'required' => true,
					'value' => $userData['userId']
				),
				'type' => array(
					'required' => true,
					'value' => 2
				)
			);


			$response['content'] = $this->recoveryModel->sendRecovery($paramsData, $requestData['email']);

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response = $userData;
		}

		return $response;
	}
}
