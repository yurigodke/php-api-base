<?php
class LoginController extends Controller {
	function __construct() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		$this->loginModel = new Login();
		$this->userModel = new User();
	}

	public function getLogin($requestData) {
		$response = array();

		$userData = $this->userModel->getByQuery(array(
			'email' => $requestData['email'],
			'pass' => md5($requestData['pass'])
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
					'value' => 1
				)
			);


			$response['content'] = $this->loginModel->registerLogin($paramsData);

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response['content'] = array(
				'error' => "Invalid user or pass."
			);
			$response['statusCode'] = 400;
		}

		return $response;
	}
}
