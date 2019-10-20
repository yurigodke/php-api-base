<?php
$configFile = SRCPATH . '/config.php';

if (file_exists($configFile)) {
	include($configFile);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Create config file</title>
	</head>
	<body>
		<form action="/config/save/" method="post">
			<select id="envName" onchange="environmentSelect(event.target.value)">
				<option value="local">Local</option>
				<option value="qa">QA</option>
				<option value="prod">Production</option>
			</select>

			<div id="local">
				<h3>Local</h3>
				<label>
					<span>Database host name</span>
					<input type="text" name="local[dbhost]" required >
				</label>
				<label>
					<span>Database user name</span>
					<input type="text" name="local[dbuser]" required >
				</label>
				<label>
					<span>Database password</span>
					<input type="text" name="local[dbpass]" required >
				</label>
				<label>
					<span>Database name</span>
					<input type="text" name="local[dbname]" required >
				</label>
			</div>
			<div id="qa" style="display: none">
				<h3>QA</h3>
				<label>
					<span>Database host name</span>
					<input type="text" name="qa[dbhost]" >
				</label>
				<label>
					<span>Database user name</span>
					<input type="text" name="qa[dbuser]" >
				</label>
				<label>
					<span>Database password</span>
					<input type="text" name="qa[dbpass]" >
				</label>
				<label>
					<span>Database name</span>
					<input type="text" name="qa[dbname]" >
				</label>
			</div>
			<div id="prod" style="display: none">
				<h3>Production</h3>
				<label>
					<span>Database host name</span>
					<input type="text" name="prod[dbhost]" >
				</label>
				<label>
					<span>Database user name</span>
					<input type="text" name="prod[dbuser]" >
				</label>
				<label>
					<span>Database password</span>
					<input type="text" name="prod[dbpass]" >
				</label>
				<label>
					<span>Database name</span>
					<input type="text" name="prod[dbname]" >
				</label>
			</div>
			<button type="submit">Enviar</button>
			<button type="button" onclick="testConection();">Testar conexão</button>
			<span id="connectionResult"></span>
		</form>

		<script type="text/javascript">
			function environmentSelect(environment) {
				const environments = ['local', 'qa', 'prod'];

				environments.forEach(function(environmentItem) {
					if (environmentItem == environment) {
						document.getElementById(environmentItem).style.display = null;
					} else {
						document.getElementById(environmentItem).style.display = 'none';
					}
				})
			}

			function testConection() {
				const envName = document.getElementById('envName').value;

				const dbhost = document.querySelector(`input[name^='${envName}[dbhost]']`).value;
				const dbuser = document.querySelector(`input[name^='${envName}[dbuser]']`).value;
				const dbpass = document.querySelector(`input[name^='${envName}[dbpass]']`).value;
				const dbname = document.querySelector(`input[name^='${envName}[dbname]']`).value;

				const dataPost = {
					dbhost: dbhost,
					dbuser: dbuser,
					dbpass: dbpass,
					dbname: dbname,
				}

				const connectionResult = document.getElementById('connectionResult');

				fetch('/config/connection/', {
				  method: 'POST',
				  body: JSON.stringify(dataPost),
				  headers: {
				    'Content-Type': 'application/json'
				  }
				})
				  .then(function(body) {
						if (body.status == '200') {
							connectionResult.innerHTML = "OK";
						} else {
							connectionResult.innerHTML = "Failed";
						};
					});
			}

			const localInputs = document.querySelectorAll("input[name^='local']");
			const qaInputs = document.querySelectorAll("input[name^='qa']")

			localInputs.forEach(function(inputItem) {
				inputItem.addEventListener('keyup', function(e) {
					const inputValue = e.target.value;
					const inputName = e.target.name;

					const inputQaName = inputName.replace('local', 'qa');
					const inputProdName = inputName.replace('local', 'prod');

					const inputQaItem = document.querySelector(`input[name='${inputQaName}']`);
					const inputProdItem = document.querySelector(`input[name='${inputProdName}']`);

					inputQaItem.placeholder = inputValue;
					inputProdItem.placeholder = inputValue;
				})
			});

			qaInputs.forEach(function(inputItem) {
				inputItem.addEventListener('keyup', function(e) {
					const inputValue = e.target.value;
					const inputName = e.target.name;

					const inputProdName = inputName.replace('qa', 'prod');

					const inputProdItem = document.querySelector(`input[name='${inputProdName}']`);

					inputProdItem.placeholder = inputValue;
				})
			});
		</script>
	</body>
</html>
