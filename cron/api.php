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

$output_data['install_crush'] = false;

if (file_exists(sprintf('%s/install', DOCUMENT_ROOT)) && $surl->get_query('query') == 'install') {

  if ($surl->get_query('event') == 'database-generate') {

		$output_data['install_stage_status'] = 0;

		try {

			if ($surl->get_query('stage') == 1) {

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
				. "\t'server' => '$form_database_server'," . PHP_EOL
				. "\t'name' => '$form_database_name'," . PHP_EOL
				. "\t'username' => '$form_database_username'," . PHP_EOL
				. "\t'password' => '$form_database_password'," . PHP_EOL
				. "\t'prefix' => '$form_database_prefix'" . PHP_EOL
				. "];" . PHP_EOL . PHP_EOL
				. "?>";

				try {

					$fp = fopen(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT), 'w');
					fwrite($fp, $file_data);
					fclose($fp);

					$message = '<span style="color:green;">Конфигурация для работы с базой данных успешно создана.</span>';

				} catch (Exception $exception) {

					$message = '<span style="color:red;">Произошла внутренняя ошибка. Конфигурация для работы с базой данных не была создана.</span>';

				}

			}

			if (file_exists(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT))) {
				require(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT));
			}

			if ($surl->get_query('stage') == 2) {

				$table_name = sprintf('%s_users', $_CMS['database']['prefix']);
				$table_exists = false;

				$database = new \cron\library\Database();

				try {

					$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM %s LIMIT 1;', $table_name));
					$database_query->execute();

					$message = sprintf('<span style="color:red;">Таблица %s уже была создана.</span>', $table_name);
					$table_exists = true;
				
				} catch (PDOException $exception) {

					$message = sprintf('<span style="color:red;">Таблица %s не найдена. Требуется ее создание.</span>', $table_name);

				}

				if (!$table_exists) {

					try {

						$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-tables/users.sql', DOCUMENT_ROOT)), $table_name));
						$execute = $database_query->execute();

						$message = sprintf('<span style="color:green;">Таблица %s успешно создана.</span>', $table_name);

					} catch (PDOException $exception) {

						$message = sprintf('<span style="color:red;">Таблица %s не была создана. Ошибка: %s</span>', $table_name, $exception->getMessage());

					}

				}

			}

			if ($surl->get_query('stage') == 3) {

				$table_name = sprintf('%s_roles', $_CMS['database']['prefix']);
				$table_exists = false;

				$database = new \cron\library\Database();

				try {

					$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM %s LIMIT 1;', $table_name));
					$database_query->execute();

					$message = sprintf('<span style="color:red;">Таблица %s уже была создана.</span>', $table_name);
					$table_exists = true;
				
				} catch (PDOException $exception) {

					$message = sprintf('<span style="color:red;">Таблица %s не найдена. Требуется ее создание.</span>', $table_name);

				}

				if (!$table_exists) {

					try {

						$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-tables/roles.sql', DOCUMENT_ROOT)), $table_name));
						$execute = $database_query->execute();

						$message = sprintf('<span style="color:green;">Таблица %s успешно создана.</span>', $table_name);

					} catch (PDOException $exception) {

						$message = sprintf('<span style="color:red;">Таблица %s не была создана. Ошибка: %s</span>', $table_name, $exception->getMessage());

					}

				}
				
			}

			if ($surl->get_query('stage') == 4) {

				$table_name = sprintf('%s_sessions', $_CMS['database']['prefix']);
				$table_exists = false;

				$database = new \cron\library\Database();

				try {

					$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM %s LIMIT 1;', $table_name));
					$database_query->execute();

					$message = sprintf('<span style="color:red;">Таблица %s уже была создана.</span>', $table_name);
					$table_exists = true;
				
				} catch (PDOException $exception) {

					$message = sprintf('<span style="color:red;">Таблица %s не найдена. Требуется ее создание.</span>', $table_name);

				}

				if (!$table_exists) {

					try {

						$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-tables/sessions.sql', DOCUMENT_ROOT)), $table_name));
						$execute = $database_query->execute();

						$message = sprintf('<span style="color:green;">Таблица %s успешно создана.</span>', $table_name);

					} catch (PDOException $exception) {

						$message = sprintf('<span style="color:red;">Таблица %s не была создана. Ошибка: %s</span>', $table_name, $exception->getMessage());

					}

				}
				
			}

			if ($surl->get_query('stage') == 5) {

				$table_name = sprintf('%s_entries', $_CMS['database']['prefix']);
				$table_exists = false;

				$database = new \cron\library\Database();

				try {

					$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM %s LIMIT 1;', $table_name));
					$database_query->execute();

					$message = sprintf('<span style="color:red;">Таблица %s уже была создана.</span>', $table_name);
					$table_exists = true;
				
				} catch (PDOException $exception) {

					$message = sprintf('<span style="color:red;">Таблица %s не найдена. Требуется ее создание.</span>', $table_name);

				}

				if (!$table_exists) {

					try {

						$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-tables/entries.sql', DOCUMENT_ROOT)), $table_name));
						$execute = $database_query->execute();

						$message = sprintf('<span style="color:green;">Таблица %s успешно создана.</span>', $table_name);

					} catch (PDOException $exception) {

						$message = sprintf('<span style="color:red;">Таблица %s не была создана. Ошибка: %s</span>', $table_name, $exception->getMessage());

					}

				}
				
			}

			if ($surl->get_query('stage') == 6) {

				$table_name = sprintf('%s_settings', $_CMS['database']['prefix']);
				$table_exists = false;

				$database = new \cron\library\Database();

				try {

					$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM %s LIMIT 1;', $table_name));
					$database_query->execute();

					$message = sprintf('<span style="color:red;">Таблица %s уже была создана.</span>', $table_name);
					$table_exists = true;
				
				} catch (PDOException $exception) {

					$message = sprintf('<span style="color:red;">Таблица %s не найдена. Требуется ее создание.</span>', $table_name);

				}

				if (!$table_exists) {

					try {

						$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-tables/settings.sql', DOCUMENT_ROOT)), $table_name));
						$execute = $database_query->execute();

						$message = sprintf('<span style="color:green;">Таблица %s успешно создана.</span>', $table_name);

					} catch (PDOException $exception) {

						$message = sprintf('<span style="color:red;">Таблица %s не была создана. Ошибка: %s</span>', $table_name, $exception->getMessage());

					}

				}
				
			}

		} catch (PDOException $exception) {

			$message = sprintf('<span style="color:red;">%s</span>', $exception->getMessage());
			$output_data['install_crush'] = true;

		}

		$output_data['install_stage'] = 4;

  }

	if ($surl->get_query('event') == 'admin-create') {
		
		if (file_exists(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT))) {
			require(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT));
		}

		$table_name = sprintf('%s_users', $_CMS['database']['prefix']);

		$output_data['install_stage_status'] = 0;

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

						$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM public.%s;', $table_name));
						$execute = $database_query->execute();

						$table_exists = true;
						
					} catch (Exception $e) {

						$api_event_id = 5;
						$message_type = 2;

					}

					if ($table_exists) {

						$database_query = $database->connect->prepare(sprintf('SELECT 1 FROM public.%s;', $table_name));
						$database_query->execute();
						$result = $database_query->fetchAll(\PDO::FETCH_ASSOC);
						
						if (!$result) {

							$role_id = 1;

							$database_query = $database->connect->prepare(sprintf(file_get_contents(sprintf('%s/install/database-generate/create-rows/users.sql', DOCUMENT_ROOT)), $table_name));
							$database_query->bindParam(':login', $form_user_login, PDO::PARAM_STR);
							$database_query->bindParam(':email', $form_user_email, PDO::PARAM_STR);
							$database_query->bindParam(':role_id', $role_id, PDO::PARAM_INT);
							$database_query->bindParam(':hash', $password_hash, PDO::PARAM_STR);
							$execute = $database_query->execute();

							$api_event_id = 1;
							$message_type = 1;

							$output_data['install_stage_status'] = 1;

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

		$output_data['install_stage'] = 5;

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