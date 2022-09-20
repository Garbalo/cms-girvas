<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
ini_set('error_log', sprintf('%s/logs/error.log', DOCUMENT_ROOT));

define('IS_NOT_HACKED', true);

require_once(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

$libraries_list = scandir(sprintf('%s/cron/php-library', DOCUMENT_ROOT));
foreach($libraries_list as $library_index => $library_file) {
	if (preg_match('/^([a-zA-Z_]+)\.class\.php$/', $library_file)) {
		require_once(sprintf('%s/cron/php-library/%s', DOCUMENT_ROOT, $library_file));
	}
}

$surl = new cron\library\SimpleUrl();

if (file_exists(sprintf('%s/install', DOCUMENT_ROOT)) && $surl->get_query('query') == 'install') {

  if ($surl->get_query('event') == 'database-generate') {

		if ($surl->get_query('stage') == 1) {

			$database = new \cron\library\Database();
			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-tables/users.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$message = 'Стадия 1';
		}

		if ($surl->get_query('stage') == 2) {

			$database = new \cron\library\Database();
			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-tables/roles.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$message = 'Стадия 2';
		}

  }
  
}

$message = (isset($message)) ? $message : 'API обработал запрос, но не выдал результат. ¯\_(ツ)_/¯';
$message_type = (isset($message_type)) ? $message_type : 4;

$output_data = (isset($output_data)) ? $output_data : [];
//$output_data = (is_null($output_data)) ? [] : $output_data;

echo json_encode([
	'message' => $message,
	'messageType' => $message_type,
	'outputData' => $output_data
]);

?>