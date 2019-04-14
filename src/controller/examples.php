<?php
include(__DIR__ . '/../model/examples.php');

class ExampleController extends Examples {
	function __construct() {
		global $app;
		$container = $app->getContainer();
		$this->logger = $container->logger;

		parent::__construct();
	}

	public function createExample($params) {
		$requireParams = array('name', 'email', 'pass');
		$response = array();

		$validData = $this->isValidData($params, $requireParams);

		$prepareInfo = $this->prepareConsult($validData);

		if ($prepareInfo === true) {
			$response['content'] = $this->add($params);

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response = $prepareInfo;
		}

		return $response;
	}

	public function getExample($id) {
		$response = array();

		if (preg_match('/[0-9]+/', $id)) {
			$prepareInfo = $this->prepareConsult();

			if ($prepareInfo === true) {
				$response['content'] = $this->getById($id);

				$response['statusCode'] = $response['content']['error'] ? 500 : 200;
			} else {
				$response = $prepareInfo;
			}
		} else {
			$response['content'] = array(
				'error' => 'Invalid id.'
			);

			$response['statusCode'] = 400;
		}

		return $response;
	}

	public function getExampleList() {
		$response = array();

		$prepareInfo = $this->prepareConsult();

		if ($prepareInfo === true) {
			$response['content'] = $this->getList();

			$response['statusCode'] = $response['content']['error'] ? 500 : 200;
		} else {
			$response = $prepareInfo;
		}

		return $response;
	}

	private function isValidData($param, $require) {
		$typeRegex = array(
			'name' => '/^([a-zA-ZÀ-ú]+\s?)+$/i',
			'email' => '/^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i'
		);

		$checkInfo = array();

		foreach ($require as $requireKey) {
			if (isset($param[$requireKey])) {
				if (isset($typeRegex[$requireKey])) {
					if (!preg_match($typeRegex[$requireKey], $param[$requireKey])) {
						$checkInfo[$requireKey] = 'Field is invalid.';
					}
				}
			} else {
				$checkInfo[$requireKey] = 'Not found. It is a required field.';
			}
		}

		return $checkInfo;
	}

	private function prepareConsult($validData = array()) {
		$prepareResp = array();

		if (count($validData)) {
			$prepareResp['content'] = array(
				'error' => array(
					'field' => $validData
				)
			);

			$this->logger->error('[controller] examples.prepareConsult', $validData);

			$prepareResp['statusCode'] = 400;
		}

		return count($prepareResp) ? $prepareResp : true;
	}
}
