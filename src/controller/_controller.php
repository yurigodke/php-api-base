<?php
class Controller {
	public function isValidData($requestData, $paramsData) {
		$typeRegex = array(
			'name' => '/^([a-zA-ZÀ-ú]+\s?)+$/i',
			'email' => '/^[a-z0-9\.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i'
		);

		$checkInfo = array();

		foreach ($paramsData as $paramName => $paramValues) {
			if (isset($requestData[$paramName])) {
				if (isset($typeRegex[$paramName])) {
					if (!preg_match($typeRegex[$paramName], $requestData[$paramName])) {
						$checkInfo[$paramName] = 'Field is invalid.';
					}
				}
			} else if ($paramValues['required']) {
				$checkInfo[$paramName] = 'Not found. It is a required field.';
			}
		}

		return $checkInfo;
	}

	public function preparData($validData = array(), $name) {
		$preparedData = array();

		if (count($validData)) {
			$preparedData['content'] = array(
				'error' => array(
					'field' => $validData
				)
			);

			$this->logger->error("[controller] $name.preparData", $validData);

			$preparedData['statusCode'] = 400;
		}

		return count($preparedData) ? $preparedData : true;
	}
}
