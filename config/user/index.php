<?php
$configFile = SRCPATH . '/config.php';
$configFileSet = false;
$dbconnect = false;

if (file_exists($configFile)) {
	$configFileSet = true;
	include($configFile);

	$dsn = 'mysql:dbname=' . DBNAME . ';host=' . DBHOST;

	$result = array(
		'msg' => ''
	);

	try {
	  $dbh = new PDO($dsn, DBUSER, DBPASS);
		$dbconnect = true;

		if ($_POST) {
			// $stmt = $con->prepare("INSERT INTO pessoa(nome, email) VALUES(?, ?)");
			// $stmt->bindParam(1,”Nome da Pessoa”);
			// $stmt->bindParam(2,”email@email.com”);
			// $stmt->execute();
		}
	} catch (PDOException $e) {
		$dbconnect = false;
	}
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<?php if ($configFileSet && $dbconnect): ?>
			<form method="post">
				<h3>Cadastrar super usuário</h3>
				<label>
					<span>Username</span>
					<input type="text" name="user[name]" required >
				</label>
				<label>
					<span>Password</span>
					<input type="password" name="user[pass]" required >
				</label>
				<button type="submit">Enviar</button>
			</form>
		<?php elseif($configFileSet): ?>
			Não foi possível fazer a conexão com o banco de dados, <a href="/config/create/">clique aqui</a> editar as configurações
		<?php else: ?>
			O sistema ainda não foi configurado, <a href="/config/create/">clique aqui</a> para configurar
		<?php endif; ?>
	</body>
</html>
