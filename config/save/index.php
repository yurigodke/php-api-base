<?php
$configWrited = false;

if ($_POST) {
	$environments = array('local', 'qa', 'prod');

	$configFileContent = "";
	$configFileContent .= "switch (ENVIRONMENT) {\n";
	foreach ($environments as $environmentItemName) {

		$configFileContent .= "\tcase '$environmentItemName':\n";
		$configFileContent .= "\t\t// Data base host name in $environmentItemName environment\n";
		$configFileContent .= "\t\tdefine('DBHOST', '" . $_POST[$environmentItemName]['dbhost'] . "');\n";

		$configFileContent .= "\t\t// Data base user name in $environmentItemName environment\n";
		$configFileContent .= "\t\tdefine('DBUSER', '" . $_POST[$environmentItemName]['dbuser'] . "');\n";

		$configFileContent .= "\t\t// Data base password in $environmentItemName environment\n";
		$configFileContent .= "\t\tdefine('DBPASS', '" . $_POST[$environmentItemName]['dbpass'] . "');\n";

		$configFileContent .= "\t\t// Data base name in $environmentItemName environment\n";
		$configFileContent .= "\t\tdefine('DBNAME', '" . $_POST[$environmentItemName]['dbname'] . "');\n";

		$configFileContent .= "\t\tbreak;\n";
	}

	$configFileContent .= '};';

} else {
	header('Location: /config/create');
}

if (is_writable(SRCPATH)) {
	$configFilePath = SRCPATH . '/config.php';
	$configWrited = true;
	file_put_contents($configFilePath, "<?php\n" . $configFileContent);
};
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		<?php if ($configWrited): ?>
			<span>Mensagem de sucesso</span>
			<a href="/config/user/">Continuar</a>
		<?php else: ?>
			<span>Não foi possível criar o arquivo de configuração, por favor, crie o arquivo <pre>/src/config.php</pre> com o conteúdo abaixo e salve. Ou então altere os privilégios para que o sistema possa gravar arquivos</span>
			<pre><?php echo $configFileContent; ?></pre>
		<?php endif; ?>
	</body>
</html>
