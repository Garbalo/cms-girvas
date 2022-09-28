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

		if (!file_exists(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT))) {

			$form_database_server = (!empty($_POST['database_server'])) ? $_POST['database_server'] : '127.0.0.1';
			$form_database_name = $_POST['database_name'];
			$form_database_username = $_POST['database_username'];
			$form_database_password = $_POST['database_password'];
			$form_database_prefix = $_POST['database_prefix'];

			// Automatic creation of a configuration file for the database.

			$file_data = "<?php" . PHP_EOL . PHP_EOL
			. "# ATTENTION! This file is generated automatically during the installation of the content management system \"GIRVAS\"." . PHP_EOL
			. "# If you do not know what this file is for, then in no case do not edit it!" . PHP_EOL . PHP_EOL
			. "\$_CMS['database'] = [" . PHP_EOL
			. "\t# Database location address. (Leave this option blank if the database is located locally.)" . PHP_EOL
			. "\t'server' => '$form_database_server'" . PHP_EOL
			. "\t'name' => '$form_database_name'" . PHP_EOL
			. "\t'username' => '$form_database_username'" . PHP_EOL
			. "\t'password' => '$form_database_password'" . PHP_EOL
			. "\t'prefix' => '$form_database_prefix'" . PHP_EOL
			. "];" . PHP_EOL . PHP_EOL
			. "?>";

			$fp = fopen(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT), 'w');
			fwrite($fp, $file_data);
			fclose($fp);

		}

		if ($surl->get_query('stage') == 1) {

			$database = new \cron\library\Database();
			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-tables/users.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/check-exists-tables/users.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();
			
			$message = (!$execute)
				? '<span style="color:green;">База пользователей успешно создана.</span>'
				: '<span style="color:red;">База пользователей не была создана.</span>';
		}

		if ($surl->get_query('stage') == 2) {

			$database = new \cron\library\Database();
			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-tables/roles.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/check-exists-tables/roles.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$message = (!$execute)
				? '<span style="color:green;">База ролей пользователей успешно создана.</span>'
				: '<span style="color:red;">База ролей пользователей не была создана.</span>';
		}

		if ($surl->get_query('stage') == 3) {

			$database = new \cron\library\Database();
			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-tables/sessions.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();

			$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/check-exists-tables/sessions.sql', DOCUMENT_ROOT)));
			$execute = $database_query->execute();
			
			$message = (!$execute)
				? '<span style="color:green;">База сессий пользователей успешно создана.</span>'
				: '<span style="color:red;">База сессий пользователей не была создана.</span>';
		}

  }

	if ($surl->get_query('event') == 'admin-create') {
		
		$form_user_login = $_POST['user_login'];
		$form_user_email = $_POST['user_email'];
		$form_user_password = $_POST['user_password'];
		$form_user_password_repeat = $_POST['user_password_repeat'];

		if (isset($form_user_login) && isset($form_user_email) && isset($form_user_password) && isset($form_user_password_repeat)) {

			if (strlen($form_user_login) >= 4 && filter_var($form_user_email, FILTER_VALIDATE_EMAIL)) {

				$password_hash = password_hash($form_user_password, PASSWORD_ARGON2ID);

				if (password_verify($form_user_password_repeat, $password_hash)) {

					$database = new \cron\library\Database();

					$table_exists = false;

					try {

						$database_query = $database->connect->prepare('SELECT 1 FROM public.users;');
						$execute = $database_query->execute();

						$table_exists = true;
						
					} catch (Exception $e) {

						$api_event_id = 5;
						$message_type = 2;

					}

					if ($table_exists) {

						$database_query = $database->connect->prepare('SELECT * FROM public.users;');
						$database_query->execute();
						$result = $database_query->fetchAll(\PDO::FETCH_ASSOC);
						
						if (!$result) {

							$role_id = 1;

							$database_query = $database->connect->prepare(file_get_contents(sprintf('%s/install/database-generate/create-rows/users.sql', DOCUMENT_ROOT)));
							$database_query->bindParam(':login', $form_user_login, PDO::PARAM_STR);
							$database_query->bindParam(':email', $form_user_email, PDO::PARAM_STR);
							$database_query->bindParam(':role_id', $role_id, PDO::PARAM_INT);
							$database_query->bindParam(':hash', $password_hash, PDO::PARAM_STR);
							$execute = $database_query->execute();

							$api_event_id = 1;
							$message_type = 1;

						} else {

							$api_event_id = 6;
							$message_type = 2;

						}

					}

				} else {

					$api_event_id = 2;
					$message_type = 2;

				}

			} else {

				$api_event_id = 3;
				$message_type = 2;

			}

		} else {

			$api_event_id = 4;
			$message_type = 2;

		}
		
		$api_event_id = (isset($api_event_id)) ? $api_event_id : 0;

		switch ($api_event_id) {
			case 1: $message = 'Учетная запись администратора успешно создана.'; break;
			case 2: $message = 'Указанные пароли не совпадают.'; break;
			case 3: $message = 'Нарушены базовые требования к логину или адресу электронной почты.'; break;
			case 4: $message = 'Произошла внутренняя ошибка.'; break;
			case 5: $message = 'Невозможно создать учетную запись администратора. Таблица пользователей не создана.'; break;
			case 6: $message = 'Невозможно создать учетную запись администратора. Таблица пользователей уже содержит записи.'; break;
			default: $message = '{LANG:UNDEFINED_MESSAGE}'; break;
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