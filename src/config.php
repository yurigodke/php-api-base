<?php
switch (ENVIRONMENT) {
	case 'local':
		// Data base host name in local environment
		define('DBHOST', 'mysql');
		// Data base user name in local environment
		define('DBUSER', 'example');
		// Data base password in local environment
		define('DBPASS', 'pwdef');
		// Data base name in local environment
		define('DBNAME', 'exampledb');
		break;
	case 'qa':
		// Data base host name in qa environment
		define('DBHOST', '');
		// Data base user name in qa environment
		define('DBUSER', '');
		// Data base password in qa environment
		define('DBPASS', '');
		// Data base name in qa environment
		define('DBNAME', '');
		break;
	case 'prod':
		// Data base host name in prod environment
		define('DBHOST', '');
		// Data base user name in prod environment
		define('DBUSER', '');
		// Data base password in prod environment
		define('DBPASS', '');
		// Data base name in prod environment
		define('DBNAME', '');
		break;
};