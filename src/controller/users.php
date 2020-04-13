<?php
class UserController extends Controller {
	function __construct() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		$this->userModel = new User();
	}

	public function createUser($requestData) {
		$paramsData = array(
			'email' => array(
				'required' => true,
				'value' => $requestData['email']
			),
			'pass' => array(
				'required' => true,
				'value' => md5($requestData['pass'])
			),
			'level' => array(
				'required' => true,
				'value' => $requestData['level']
			)
		);

		$validData = $this->isValidData($requestData, $paramsData);

		$preparedData = $this->preparData($validData, 'createUser');

		$response = array();

		if ($preparedData === true) {
			$response['content'] = $this->userModel->add($paramsData);

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response = $preparedData;
		}

		return $response;
	}

	public function getUser($id) {
		$response = array();

		if (preg_match('/[0-9]+/', $id)) {
			$response['content'] = $this->userModel->getByQuery(array(
				'userId' => $id
			));

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response['content'] = array(
				'error' => 'Invalid id.'
			);

			$response['statusCode'] = 400;
		}

		return $response;
	}

	public function getUserList() {
		$response = array();

		$response['content'] = $this->userModel->getList();

		$response['statusCode'] = $response['content']['error'] ? 500 : 200;

		return $response;
	}

	public function editUser($requestData, $id) {
		$paramsData = array();

		foreach ($requestData as $fieldName => $fieldValue) {
			$valueApply = $fieldValue;
			if ($fieldName == 'pass') {
				$valueApply = md5($fieldValue);
			}

			$paramsData[$fieldName] = array(
				'required' => true,
				'value' => $valueApply
			);
		}

		$validData = $this->isValidData($requestData, $paramsData);

		$preparedData = $this->preparData($validData, 'editUser');

		$response = array();

		if ($preparedData === true) {
			$response['content'] = $this->userModel->edit($paramsData, array(
				'userId' => $id
			));

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response = $prepareInfo;
		}

		return $response;
	}

	public function deleteUser($id) {
		$response = array();

		if (preg_match('/[0-9]+/', $id)) {
			$response['content'] = $this->userModel->deleteByQuery(array(
				'userId' => $id
			));

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response['content'] = array(
				'error' => 'Invalid id.'
			);

			$response['statusCode'] = 400;
		}

		return $response;
	}
}
