<?php
$jsonData = file_get_contents('php://input');
$dbInfo = json_decode($jsonData, true);

$dsn = 'mysql:dbname=' . $dbInfo['dbname'] . ';host=' . $dbInfo['dbhost'];

$result = array(
	'msg' => ''
);

try {
  $dbh = new PDO($dsn, $dbInfo['dbuser'], $dbInfo['dbpass']);
	header("HTTP/1.1 200 OK");
	$result['msg'] = "Conexão Ok";
} catch (PDOException $e) {
	header('HTTP/1.1 400 Bad Request');
	$result['msg'] = 'Conexão falhou: ' . $e->getMessage();
}


echo json_encode($result);
