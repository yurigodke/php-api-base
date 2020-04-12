<?php
$errorMsg = '';
if ($_SERVER['PHP_AUTH_USER'] != 'admin' || $_SERVER['PHP_AUTH_PW'] != 'admin') {
	header('WWW-Authenticate: Basic realm="My Realm"');
	header('HTTP/1.0 401 Unauthorized');
	$errorMsg = 'Texto enviado caso o usuário clique no botão Cancelar';
} else {
	require __DIR__ . '/../vendor/autoload.php';
	require __DIR__ . '/../src/config.php';

	$uri = 'mysql://' . DBUSER . ':' . DBPASS . '@' . DBHOST . '/' . DBNAME;
	$connectionUri = new \ByJG\Util\Uri($uri);
	$migration = new \ByJG\DbMigration\Migration($connectionUri, __DIR__);
	$migration->registerDatabase('mysql', \ByJG\DbMigration\Database\MySqlDatabase::class);

	if ($_POST['type'] && $_POST['versionUp']) {
		$force = $_POST['force'] == '1' ? true : false;

		if ($_POST['type'] == 'up') {
			$migration->up($_POST['versionUp'], $force);
		} else if ($_POST['type'] == 'down') {
			$migration->down($_POST['versionDown'], $force);
		} else if ($_POST['type'] == 'update') {
			$migration->update($_POST['versionUpdate'], $force);
		}

		$migration->createVersion();
	}

	try {
		$currentVersion = $migration->getCurrentVersion();
	} catch (\Exception $e) {
		$errorMsg = $e->getMessage();
		$migration->reset();
	}

	$values = array(
		'status' => $currentVersion['status'],
		'version' => $currentVersion['version'],
		'up' => $currentVersion['version'] + 1,
		'down' => $currentVersion['version'] - 1
	);
}



?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Migrations</title>
	</head>
	<body>
		Error: <?php echo $errorMsg; ?><br><br><br>
		Versão atual é: <?php echo $values['version'] ?><br>
		Status atual: <?php echo $values['status'] ?><br><br>
		<form action="" method="post">
			<label for="">
				Force action
				<input type="checkbox" name="force" value="1">
			</label><br>
			<label for="">
				<input type="radio" name="type" value="up" checked>
				<span>UP to:</span>
				<input type="number" name="versionUp" min="<?php echo $values['up'] ?>" value="<?php echo $values['up'] ?>">
			</label><br>
			<?php if ($values['down'] >= 0) : ?>
			<label for="">
				<input type="radio" name="type" value="down">
				<span>Down to:</span>
				<input type="number" name="versionDown" max="<?php echo $values['down'] ?>" value="<?php echo $values['down'] ?>">
			</label><br>
			<?php endif; ?>
				<label for="">
					<input type="radio" name="type" value="update">
					<span>Update to:</span>
					<input type="number" name="versionUpdate" value="<?php echo $values['version'] ?>">
				</label>
			<br>
			<input type="submit" value="Aplicar">
		</form>
	</body>
</html>
