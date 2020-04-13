<?php
class Model {
	public function isConnected() {
		$isConnected = isset($this->conn);

		if (!$isConnected) {
			$this->respData = array(
				'error' => 'Connection error, please try later.'
			);
		}

		return $isConnected;
	}

	public function getQueryData($type, $table, $data, $where = null) {
		$query;

		$dataValues = array_keys($data);

		switch ($type) {
			case 'insert':
				$columns = array();
				$values = array();

				foreach ($dataValues as $name) {
					array_push($columns, $name);
					array_push($values, ':' . $name);
				}

				$query = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) .
					') VALUES (' . implode(', ', $values) . ')';
				break;
			case 'select':
				$columns = array('*');
				if (count($dataValues)) {
					$columns = $dataValues;
				}

				$query = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table;
				break;
			case 'update':
				$fields = array();

				foreach ($dataValues as $name) {
					array_push($fields, "$name=:$name");
				}

				$query = 'UPDATE ' . $table . ' SET ' . implode(', ', $fields);
				break;
			case 'delete':
			 	if (isset($where)) {
					$query = 'DELETE FROM ' . $table;
			 	}
				break;
			default:
				break;
		}

		$queryData = array();

		if (isset($where) && count($where)) {
			$query .= ' WHERE ';
			$fields = array();

			foreach ($where as $fieldName => $fieldValue) {
				$queryData[':' . $fieldName] = $fieldValue;

				array_push($fields, "$fieldName=:$fieldName");
			}

			$query .= implode(', ', $fields);
		}

		foreach ($data as $name => $info) {
			if (isset($info['value'])) {
				$queryData[':' . $name] = $info['value'];
			}
		}

		return array(
			'query' => $query,
			'data' => $queryData
		);
	}
}
