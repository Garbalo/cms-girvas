<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

if (!isset($system_core)) {
  http_response_code(500);
  die('CMS system core not initialized.');
}

if (defined('IS_NOT_HACKED')) {
  header(sprintf('Access-Control-Allow-Origin: %s', $system_core->configurator->get('domain')));

  switch ($_SERVER['REQUEST_METHOD']) {
    case 'PATCH': $_PATCH = $system_core::parse_raw_http_request(file_get_contents('php://input'), $_SERVER['CONTENT_TYPE']); break;
    case 'PUT': $_PUT = $system_core::parse_raw_http_request(file_get_contents('php://input'), $_SERVER['CONTENT_TYPE']); break;
    case 'DELETE': $_DELETE = $system_core::parse_raw_http_request(file_get_contents('php://input'), $_SERVER['CONTENT_TYPE']); break;
  }

  $handler_output_data = [];

  /** ===================================================
   * Обработчик CMS GIRVAS
   * ==================================================== */

  // Installation API
  if ($system_core->urlp->get_path(1) == 'install') {
    $api_file_path = sprintf('%s/api/installation.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Modules API
  if ($system_core->urlp->get_path(1) == 'module') {
    $api_file_path = sprintf('%s/api/module.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Users API
  if ($system_core->urlp->get_path(1) == 'user') {
    $api_file_path = sprintf('%s/api/user.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Users Group API
  if ($system_core->urlp->get_path(1) == 'usersGroup') {
    $api_file_path = sprintf('%s/api/usersGroup.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Users Groups API
  if ($system_core->urlp->get_path(1) == 'usersGroups') {
    $api_file_path = sprintf('%s/api/usersGroups.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Entries API
  if ($system_core->urlp->get_path(1) == 'entry') {
    $api_file_path = sprintf('%s/api/entry.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Pages static API
  if ($system_core->urlp->get_path(1) == 'pageStatic') {
    $api_file_path = sprintf('%s/api/pageStatic.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Templates API
  if ($system_core->urlp->get_path(1) == 'template') {
    $api_file_path = sprintf('%s/api/template.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // WebChannel API
  if ($system_core->urlp->get_path(1) == 'webChannel') {
    $api_file_path = sprintf('%s/api/webChannel.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // WebChannels API
  if ($system_core->urlp->get_path(1) == 'webChannels') {
    $api_file_path = sprintf('%s/api/webChannels.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'dms-available') {
    $handler_output_data['charsets'] = ['UTF-8', 'UTF-16', 'Windows-1252', 'ISO-8859'];
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'charset') {
    $charset = ($system_core->configurator->exists_database_entry_value('base_site_charset')) ? $system_core->configurator->get_database_entry_value('base_site_charset') : 'UTF-8';
    $handler_output_data['charset'] = $charset;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'charsets') {
    $handler_output_data['charsets'] = ['UTF-8', 'UTF-16', 'Windows-1252', 'ISO-8859'];
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'ew-status') {
    $ew_status = ($system_core->configurator->exists_database_entry_value('base_engineering_works_status')) ? $system_core->configurator->get_database_entry_value('base_engineering_works_status') : 'off';
    $handler_output_data['status'] = ($ew_status == 'on') ? true : false;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'timezone') {
    $timezone_name = ($system_core->configurator->exists_database_entry_value('base_timezone')) ? $system_core->configurator->get_database_entry_value('base_timezone') : date_default_timezone_get();
    $timezone_utc = new DateTimeImmutable('now', new DateTimeZone($timezone_name));

    $handler_output_data['timezone'] = [
      'name' => $timezone_name,
      'utc' => $timezone_utc->format('P')
    ];
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'timezones') {
    $timezones = [];
    $timezone_names_array = DateTimeZone::listIdentifiers();
    foreach ($timezone_names_array as $timezone_name) {
      $timezone_utc = new DateTimeImmutable('now', new DateTimeZone($timezone_name));

      array_push($timezones, [
        'name' => $timezone_name,
        'utc' => $timezone_utc->format('P')
      ]);
    }

    $handler_output_data['timezones'] = $timezones;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'profile') {
    if ($system_core->urlp->get_path(2) == 'additional-fields') {
      $cms_locale_setted = $system_core->configurator->get_database_entry_value('base_locale');
      $fields_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $cms_locale_setted;

      $fields_types = ($system_core->configurator->exists_database_entry_value('users_additional_field_type')) ? json_decode($system_core->configurator->get_database_entry_value('users_additional_field_type'), true) : [];
      $fields_titles = ($system_core->configurator->exists_database_entry_value('users_additional_field_title')) ? json_decode($system_core->configurator->get_database_entry_value('users_additional_field_title'), true) : [];
      $fields_descriptions = ($system_core->configurator->exists_database_entry_value('users_additional_field_description')) ? json_decode($system_core->configurator->get_database_entry_value('users_additional_field_description'), true) : [];
      $fields_names = ($system_core->configurator->exists_database_entry_value('users_additional_field_name')) ? json_decode($system_core->configurator->get_database_entry_value('users_additional_field_name'), true) : [];
      
      $fields = [];
      foreach ($fields_types as $field_index => $field_type) {
        array_push($fields, [
          'type' => $field_type,
          'title' => $fields_titles[$fields_locale][$field_index],
          'description' => $fields_descriptions[$fields_locale][$field_index],
          'name' => $fields_names[$field_index]
        ]);
      }

      $handler_output_data['additionalFields'] = $fields;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'locale') {
    if ($system_core->urlp->get_path(2) == 'base') {
      $cms_locale_setted = $system_core->configurator->get_database_entry_value('base_locale');
      $cms_locale = new \core\PHPLibrary\SystemCore\Locale($system_core, $cms_locale_setted);
      $handler_output_data['locale'] = [
        'title' => $cms_locale->get_title(),
        'iconURL' => $cms_locale->get_icon_url(),
        'name' => $cms_locale->get_name(),
        'iso639_1' => $cms_locale->get_iso_639_1(),
        'iso639_2' => $cms_locale->get_iso_639_2(),
      ];
    }

    if ($system_core->urlp->get_path(2) == 'admin') {
      $cms_locale_setted = $system_core->configurator->get_database_entry_value('base_admin_locale');
      $cms_locale = new \core\PHPLibrary\SystemCore\Locale($system_core, $cms_locale_setted);
      $handler_output_data['locale'] = [
        'title' => $cms_locale->get_title(),
        'iconURL' => $cms_locale->get_icon_url(),
        'name' => $cms_locale->get_name(),
        'iso639_1' => $cms_locale->get_iso_639_1(),
        'iso639_2' => $cms_locale->get_iso_639_2(),
      ];
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'locales') {
    $handler_output_data['locales'] = [];
    $cms_locales_names = $system_core->get_array_locales_names();
    if (count($cms_locales_names) > 0) {
      foreach ($cms_locales_names as $index => $cms_locale_name) {
        $cms_locale = new \core\PHPLibrary\SystemCore\Locale($system_core, $cms_locale_name);

        if ($cms_locale->exists_file_metadata_json()) {
          array_push($handler_output_data['locales'], [
            'title' => $cms_locale->get_title(),
            'iconURL' => $cms_locale->get_icon_url(),
            'name' => $cms_locale->get_name(),
            'iso639_1' => $cms_locale->get_iso_639_1(),
            'iso639_2' => $cms_locale->get_iso_639_2(),
          ]);
        }
      }

      $handler_message = 'Данные по локализациям успешно получены.';
      $handler_status_code = 1;
    } else {
      $handler_message = 'Данные по локализациям не были получены, поскольку они не обнаружены в системе.';
      $handler_status_code = 0;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'entries') {
    if ($system_core->urlp->get_path(2) == 'categories' && is_null($system_core->urlp->get_path(3))) {
      $entries_categories_object = new \core\PHPLibrary\EntriesCategories($system_core);
      $entries_categories_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $system_core->configurator->get_database_entry_value('base_locale');

      $handler_output_data['entriesCategories'] = [];
      foreach ($entries_categories_object->get_all() as $entry_category) {
        $entry_category->init_data(['name', 'parent_id', 'texts']);
        array_push($handler_output_data['entriesCategories'], [
          'id' => $entry_category->get_id(),
          'parent_id' => $entry_category->get_parent_id(),
          'name' => $entry_category->get_name(),
          'title' => $entry_category->get_title($entries_categories_locale),
          'description' => $entry_category->get_description($entries_categories_locale),
        ]);
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'media' && $system_core->urlp->get_path(2) == 'totalPages') {
    if ($system_core->client->is_logged(2)) {
      $media_files_path = sprintf('%s/uploads/media', $system_core->get_cms_path());
      $media_files = array_diff(scandir($media_files_path), ['.', '..']);

      $handler_output_data['count'] = ceil(count($media_files) / 6);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'media' && $system_core->urlp->get_path(2) == 'list') {
    if ($system_core->client->is_logged(2)) {
      $handler_output_data['dom'] = [];

      $media_files_path = sprintf('%s/uploads/media', $system_core->get_cms_path());
      $media_files = array_diff(scandir($media_files_path), ['.', '..']);
      
      $files = [];
      foreach ($media_files as $file) {
        $file_path = sprintf('%s/%s', $media_files_path, $file);
        $file_url = $file;
        
        array_push($files, [
          'file_url' => $file_url,
          'created_unix_timestamp' => filemtime($file_path)
        ]);
      }

      usort($files, function($a, $b) {
        if ($a['created_unix_timestamp'] == $b['created_unix_timestamp']) {
          return 0;
        }

        return ($a['created_unix_timestamp'] > $b['created_unix_timestamp']) ? -1 : 1;
      });

      $media_files = [];
      foreach ($files as $file) {
        error_log(date('Y-m-d H:i:s', $file['created_unix_timestamp']));
        array_push($media_files, $file['file_url']);
      }

      $media_files_transformed = [];

      foreach ($media_files as $media_file) {
        array_push($media_files_transformed, sprintf('/uploads/media/%s', $media_file));
      }

      $handler_output_data['items'] = $media_files_transformed;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $system_core->urlp->get_path(1) == 'media' && is_null($system_core->urlp->get_path(2))) {
    if ($system_core->client->is_logged(2)) {
      $file_fullname = $_DELETE['media_file_fullname'];

      if (isset($_DELETE['media_file_fullname'])) {
        $media_dir_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
        $media_file_dir_path = sprintf('%s/%s', $media_dir_path, $file_fullname);

        if (file_exists($media_file_dir_path)) {
          unlink($media_file_dir_path);

          if (!file_exists($media_file_dir_path)) {
            $handler_output_data['reload'] = true;

            $handler_message = 'Файл успешно удален.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Система попыталась удалить файл, но это действие не было выполнено.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Несуществующий файл не может быть удален.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Невозможно выполнить запрос из-за нехватки его параметров.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Ошибка авторизации в системе.';
      $handler_status_code = 0;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->urlp->get_path(1) == 'upload') {
    if ($system_core->urlp->get_path(2) == 'media') {
      if ($system_core->client->is_logged(2)) {
        $handler_output_data['debug_files'] = $_FILES;

        $uploaded_file_extention = pathinfo($_FILES['mediaFile']['name'], PATHINFO_EXTENSION);
        $file_extention_allowed = ['png', 'gif', 'jpg', 'jpeg', 'webp'];
        $uploaded_dir_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
        
        if (file_exists($uploaded_dir_path)) {
          
          if (in_array($uploaded_file_extention, $file_extention_allowed)) {
            $file_uploaded_folder_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
           
            $file_converter = new \core\PHPLibrary\SystemCore\FileConverter($system_core);
            $file_converted = $file_converter->convert($_FILES['mediaFile'], $file_uploaded_folder_path, \core\PHPLibrary\SystemCore\FileConverter\EnumFileFormat::WEBP, true);

            if (is_array($file_converted)) {
              $handler_output_data['file'] = [];
              $handler_output_data['file']['url'] = sprintf('/uploads/media/%s', $file_converted['file_name']);
              $handler_output_data['file']['fullname'] = $file_converted['file_name'];

              $handler_message = 'Файл успешно загружен на сервер.';
              $handler_status_code = 1;
            } else {
              $handler_message = 'Файл не был загружен из-за внутренней ошибки.';
              $handler_status_code = 0;
            }
          } else {
            $handler_message = sprintf('Файл с данным разрешением [%s] не подлежит загрузке на сервер.', $uploaded_file_extention);
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Файл не может быть загружен, поскольку директория [/uploads/media] для загрузки медиа-файлов отсутствует.';
          $handler_status_code = 0;
        }
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $system_core->urlp->get_path(1) == 'template') {
    if ($system_core->client->is_logged(2)) {
      $template_name = $_DELETE['template_name'];
      $template_category = $_DELETE['template_category'];
      $template = new \core\PHPLibrary\Template($system_core, $template_name, $template_category);

      if ($template->exists_core_file()) {
        $system_core::recursive_files_remove($template->get_path());
        
        $handler_message = 'Шаблон успешно удален.';
        $handler_status_code = 1;
      } else {
        if (file_exists($template->get_path())) {
          $system_core::recursive_files_remove($template->get_path());

          $handler_message = 'Ядро шаблона не было обнаружено, но осталась его папка, которая только что была удалена.';
          $handler_status_code = 1;
        } else {
          $handler_message = 'Невозможно удалить шаблон, так как его не существует.';
          $handler_status_code = 0;
        }
      }
    } else {
      $handler_message = 'Шаблон не был удален, так как произошла ошибка авторизации.';
      $handler_status_code = 0;
    }
  }

  if ($system_core->urlp->get_path(1) == 'admin' && $system_core->urlp->get_path(2) == 'templates' && $system_core->urlp->get_path(3) == 'download' && isset($_POST['template_name'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->client->is_logged(2)) {
      $template_url = 'https://repository.cms-girvas.ru/templates/' . $_POST['template_name'];

      $ch = curl_init($template_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $curl_exucute_result = json_decode(curl_exec($ch), true);
      curl_close($ch);

      if (!empty($curl_exucute_result['outputData'])) {
        $template_dir_path = sprintf('%s/templates/%s', CMS_ROOT_DIRECTORY, $_POST['template_name']);
        $template_archive_path = sprintf('%s/templates/%s.zip', CMS_ROOT_DIRECTORY, $_POST['template_name']);
        
        $ch_archive = curl_init();
        curl_setopt($ch_archive, CURLOPT_URL, $curl_exucute_result['outputData']['download_url']);
        curl_setopt($ch_archive, CURLOPT_RETURNTRANSFER, 1);
        $curl_archive_exucute_result = curl_exec($ch_archive);
        curl_close($ch_archive);
        
        $file = fopen($template_archive_path, "w+");
        fputs($file, $curl_archive_exucute_result);
        fclose($file);

        if (file_exists($template_archive_path)) {
          $zip = new ZipArchive();
          error_log($zip->open($template_archive_path));
          if ($zip->open($template_archive_path) === true) {
            mkdir($template_dir_path);

            $zip->extractTo($template_dir_path);
            $zip->close();

            unlink($template_archive_path);

            $handler_message = 'Шаблон загружен и установлен.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Невозможно установить загруженный шаблон из-за невозможности разархивации.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Невозможно загрузить несуществующий шаблон, так как его архив не был загружен.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Невозможно загрузить несуществующий шаблон.';
        $handler_status_code = 0;
      }
    }
  }

  if ($system_core->urlp->get_path(1) == 'admin' && $system_core->urlp->get_path(2) == 'settings') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->client->is_logged(2)) {
      if (!empty($_POST)) {

        foreach ($_POST as $setting_name => $setting_value) {
          if (preg_match('/^setting_([a-z0-9_]+)$/', $setting_name, $matches, PREG_OFFSET_CAPTURE)) {
            $setting_name = $matches[1][0];

            if ($setting_name == 'users_additional_field_title' && isset($_POST['_users_additional_fields_locale'])) {
              if ($system_core->configurator->exists_database_entry_value($setting_name)) {
                $fields_titles = json_decode($system_core->configurator->get_database_entry_value($setting_name), true);
                $fields_titles[$_POST['_users_additional_fields_locale']] = $setting_value;
                $setting_value = $fields_titles;
              }
            }

            if ($setting_name == 'users_additional_field_description' && isset($_POST['_users_additional_fields_locale'])) {
              if ($system_core->configurator->exists_database_entry_value($setting_name)) {
                $fields_descriptions = json_decode($system_core->configurator->get_database_entry_value($setting_name), true);
                $fields_descriptions[$_POST['_users_additional_fields_locale']] = $setting_value;
                $setting_value = $fields_descriptions;
              }
            }

            if (is_array($setting_value)) $setting_value = json_encode($setting_value);

            switch ($setting_name) {
              case 'security_allowed_admin_ip': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
              case 'security_allowed_emails': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
              case 'seo_site_keywords': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
              case 'security_premoderation_words_filter_list': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
            }

            if ($system_core->configurator->exists_database_entry_value($setting_name)) {
              $system_core->configurator->update_database_entry_value($setting_name, $setting_value);
            } else {
              $system_core->configurator->insert_database_entry_value($setting_name, $setting_value);
            }
          }
        }

        $handler_output_data['reload'] = true;

        $handler_message = 'Данные успешно сохранены.';
        $handler_status_code = 1;
      }
    }
  }

  if ($system_core->urlp->get_path(1) == 'admin' && $system_core->urlp->get_path(2) == 'codes-access') {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && $system_core->client->is_logged(2)) {
      $chars = 'qwertyuiopasdfghjklzxcvbnm123456789';
      for ($code_index = 0; $code_index < 4; $code_index++) {
        $code_chars_array = [];
        for ($char_index = 0; $char_index < 4; $char_index++) {
          array_push($code_chars_array, $chars[rand(0, strlen($chars) - 1)]);
        }

        switch ($code_index) {
          case 0: $code_char = 'a'; break;
          case 1: $code_char = 'b'; break;
          case 2: $code_char = 'c'; break;
          case 3: $code_char = 'd'; break;
        }

        $system_core->configurator->update_database_entry_value(
          sprintf('security_admin_code_%s', $code_char),
          password_hash(implode($code_chars_array), PASSWORD_ARGON2ID)
        );

        unset($code_chars_array);
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->urlp->get_path(1) == 'registration') {
    $handler_output_data['notificationContainerTargetID'] = 'NE7648538676';
    
    if ($system_core->configurator->get_database_entry_value('security_allowed_users_registration_status') == 'on') {
      if (isset($_POST['user_login']) && isset($_POST['user_email']) && isset($_POST['user_password']) && isset($_POST['user_password_repeat'])) {
        $user_login = $_POST['user_login'];
        $user_email = $_POST['user_email'];
        $user_password = $_POST['user_password'];
        $user_password_repeat = $_POST['user_password_repeat'];
        
        if (preg_match('/^[a-z0-9\_]{4,}$/i', $user_login)) {
          if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            if (preg_match('/^[a-z0-9\_\$\%\&\#\@\?]{6,}$/i', $user_password)) {
              if ($user_password == $user_password_repeat) {
                if (!\core\PHPLibrary\User::exists_by_login($system_core, $user_login)) {
                  if (!\core\PHPLibrary\User::exists_by_email($system_core, $user_email)) {
                    $allowed_emails = [];
                    if ($system_core->configurator->exists_database_entry_value('security_allowed_emails')) {
                      $allowed_emails = $system_core->configurator->get_database_entry_value('security_allowed_emails');
                      $allowed_emails = json_decode($allowed_emails, true);
                    }

                    if ($system_core->configurator->exists_database_entry_value('security_allowed_emails_status')) {
                      $allowed_emails_status = $system_core->configurator->get_database_entry_value('security_allowed_emails_status');
                    } else {
                      $allowed_emails_status = 'off';
                    }
                    
                    $user_email_exploded = explode('@', $user_email);

                    if (empty($allowed_emails) || in_array($user_email_exploded[1], $allowed_emails) || $allowed_emails_status == 'off') {
                      $user = \core\PHPLibrary\User::create($system_core, $user_login, $user_email, $user_password);
                      
                      if (!is_null($user)) {
                        $template = new \core\PHPLibrary\Template($system_core, 'official');
                        $registration_submit = $user->create_registration_submit();

                        if (is_array($registration_submit)) {
                          $email_sender = new \core\PHPLibrary\EmailSender($system_core);
                          $email_sender->set_from_user('CMS GIRVAS', 'no-reply@garbalo.com');
                          $email_sender->set_to_user_email($user_email);
                          $email_sender->add_header(sprintf('From: %s <%s>', 'CMS GIRVAS', 'no-reply@garbalo.com'));
                          $email_sender->add_header(sprintf("\r\nX-Mailer: PHP/%s", phpversion()));
                          $email_sender->add_header("\r\nMIME-Version: 1.0");
                          $email_sender->add_header("\r\nContent-type: text/html; charset=UTF-8");

                          $email_sender->set_subject('Регистрация на сайте');
                          $email_sender->set_content(\core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/email/default.tpl', [
                            'EMAIL_TITLE' => 'Регистрация прошла успешно!',
                            'EMAIL_CONTENT' => sprintf('%s, здравствуйте! Прежде чем продолжить пользоваться нашим сайтом, Вам необходимо <a href="%s">подтвердить</a> регистрацию. Если Вы не подавали заявку, то <a href="%s">отмените</a> ее.', $user_login, sprintf('%s/registration?submit=%s', $system_core->get_site_url(), $registration_submit['submit_token']), sprintf('%s/registration?refusal=%s', $system_core->get_site_url(), $registration_submit['refusal_token'])),
                            'EMAIL_COPYRIGHT' => 'С уважением, администрация сайта.'
                          ]));

                          $email_sender->send();

                          $handler_message = 'Регистрация успешно завершена. На Ваш почтовый адрес выслано уведомление со ссылкой на страницу с активацией аккаунта.';
                          $handler_status_code = 1;
                        } else {
                          $handler_message = 'Регистрация не завершена из-за ошибки: Процесс подтверждения регистрации не был зарегистрирован в БД.';
                          $handler_status_code = 0;
                        }
                      } else {
                        $handler_message = 'Регистрация не завершена из-за ошибки: Пользователь не был создан.';
                        $handler_status_code = 0;
                      }
                    } else {
                      $handler_message = 'Регистрация не завершена из-за ошибки: Указанный E-Mail не разрешен для регистрации.';
                      $handler_status_code = 0;
                    }
                  } else {
                    $handler_message = 'Регистрация не завершена из-за ошибки: Указанный E-Mail уже занят.';
                    $handler_status_code = 0;
                  }
                } else {
                  $handler_message = 'Регистрация не завершена из-за ошибки: Указанный логин уже занят.';
                  $handler_status_code = 0;
                }
              } else {
                $handler_message = 'Регистрация не завершена из-за ошибки: Пароли не совпадают.';
                $handler_status_code = 0;
              }
            } else {
              $handler_message = 'Регистрация не завершена из-за ошибки: Пароль не соответствует формату.';
              $handler_status_code = 0;
            }
          } else {
            $handler_message = 'Регистрация не завершена из-за ошибки: E-Mail не соответствует формату.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Регистрация не завершена из-за ошибки: Логин не соответствует формату.';
          $handler_status_code = 0;
        }
      }
    } else {
      $handler_message = 'Регистрация на веб-сайте отключена.';
      $handler_status_code = 0;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->urlp->get_path(1) == 'parsedown' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_POST['markdown_text'])) {
      $parsedown = new \core\PHPLibrary\Parsedown();
      $handler_output_data['parsedown'] = $parsedown->text($_POST['markdown_text']);

      $handler_message = 'Текст успешно преобразован.';
      $handler_status_code = 1;
    }
  }

  // Авторизация пользователя
  if ($system_core->urlp->get_path(1) == 'auth' && is_null($system_core->urlp->get_path(2))) {
    header('Access-Control-Allow-Methods: POST');

    $handler_output_data['notificationContainerTargetID'] = 'NE7648538675';

    /** @var string|null $user_login */
    $user_login = (isset($_POST['user_login'])) ? $_POST['user_login'] : null;
    /** @var string|null $user_password */
    $user_password = (isset($_POST['user_password'])) ? $_POST['user_password'] : null;
    
    if (!is_null($user_login) && !is_null($user_password)) {
      /** @var \core\PHPLibrary\User|null $user */
      $user = \core\PHPLibrary\User::get_by_login($system_core, $user_login);
      
      if (!is_null($user)) {
        // Инициализация данных пользователя
        $user->init_data(['password_hash', 'security_hash']);
        
        // Проверяем правильность пароля
        if ($user->password_verify($user_password)) {
          /** @var string $user_ip */
          $user_ip = $_SERVER['REMOTE_ADDR'];
          /** @var string $user_token */
          $user_token = \core\PHPLibrary\Client\Session::generate_token();

          if (!\core\PHPLibrary\Client\Session::exists_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 1)) {
            /** @var \core\PHPLibrary\Client\Session|null $user_session */
            $user_session = \core\PHPLibrary\Client\Session::create($system_core, [
              'user_id' => $user->get_id(),
              'token' => $user_token,
              'user_ip' => $user_ip,
              'type_id' => 1
            ]);
          } else {
            $user_session = \core\PHPLibrary\Client\Session::get_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 1);
            $user_session->update([]);
          }

          if (!is_null($user_session)) {
            $user_session->init_data(['updated_unix_timestamp', 'token']);
            $user_session_expires = $user_session->get_updated_unix_timestamp() + $system_core->configurator->get('session_expires');

            setcookie('_grv_utoken', $user_session->get_token(), [
              'expires' => $user_session_expires,
              'path' => '/',
              'domain' => $system_core->configurator->get('domain_cookies'),
              'secure' => true,
              'httponly' => true
            ]);

            $handler_output_data['reload'] = true;

            /** @var string $handler_message Сообщение обработчика */
            $handler_message = 'Авторизация прошла успешно.';
            $handler_status_code = 1;
          } else {
            /** @var string $handler_message Сообщение обработчика */
            $handler_message = 'Авторизация не была пройдена, так как сессия не была создана.';
          }

        } else {
          /** @var string $handler_message Сообщение обработчика */
          $handler_message = 'Невозможно завершить авторизацию, поскольку пользователь с такими данными не был найден.';
        }
      } else {
        /** @var string $handler_message Сообщение обработчика */
        $handler_message = 'Невозможно завершить авторизацию, поскольку пользователь с такими данными не был найден.';
      }

    } else {
      /** @var string $handler_message Сообщение обработчика */
      $handler_message = 'Невозможно завершить авторизацию, поскольку не были получены необходимые данные.';

    }
  }

  // Авторизация пользователя
  if ($system_core->urlp->get_path(1) == 'admin' && $system_core->urlp->get_path(2) == 'auth' && is_null($system_core->urlp->get_path(3))) {
    header('Access-Control-Allow-Methods: POST');

    /** @var string|null $user_login */
    $user_login = (isset($_POST['user_login'])) ? $_POST['user_login'] : null;
    /** @var string|null $user_password */
    $user_password = (isset($_POST['user_password'])) ? $_POST['user_password'] : null;
    $admin_access_codes = (isset($_POST['admin_access-code'])) ? $_POST['admin_access-code'] : [];
    
    if (!is_null($user_login) && !is_null($user_password) && !empty($admin_access_codes)) {
      /** @var \core\PHPLibrary\User|null $user */
      $user = \core\PHPLibrary\User::get_by_login($system_core, $user_login);

      if (!is_null($user)) {
        // Инициализация данных пользователя
        $user->init_data(['password_hash', 'security_hash']);
        
        $admin_access_codes_is_valid = true;
        foreach ($admin_access_codes as $admin_access_code_index => $admin_access_code) {
          switch ($admin_access_code_index) {
            case 0: $code_char = 'a'; break;
            case 1: $code_char = 'b'; break;
            case 2: $code_char = 'c'; break;
            case 3: $code_char = 'd'; break;
          }

          if (!password_verify($admin_access_code, $system_core->configurator->get_database_entry_value(sprintf('security_admin_code_%s', $code_char)))) {
            $admin_access_codes_is_valid = false; break;
          }
        }

        // Проверяем правильность пароля
        if ($user->password_verify($user_password) && $admin_access_codes_is_valid) {
          /** @var string $user_ip */
          $user_ip = $_SERVER['REMOTE_ADDR'];
          /** @var string $user_token */
          $user_token_base = \core\PHPLibrary\Client\Session::generate_token();
          $user_token_admin = \core\PHPLibrary\Client\Session::generate_token();

          $user_session_base = null;
          $user_session_admin = null;

          // Если сессия не была найдена, то создаем новую.
          if (!\core\PHPLibrary\Client\Session::exists_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 1)) {
            /** @var \core\PHPLibrary\Client\Session|null $user_session */
            $user_session_base = \core\PHPLibrary\Client\Session::create($system_core, [
              'user_id' => $user->get_id(),
              'token' => $user_token_base,
              'user_ip' => $user_ip,
              'type_id' => 1
            ]);
          } else {
            $user_session_base = \core\PHPLibrary\Client\Session::get_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 1);
            $user_session_base->update([]);
          }

          // Если сессия не была найдена, то создаем новую.
          if (!\core\PHPLibrary\Client\Session::exists_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 2)) {
            /** @var \core\PHPLibrary\Client\Session|null $user_session */
            $user_session_admin = \core\PHPLibrary\Client\Session::create($system_core, [
              'user_id' => $user->get_id(),
              'token' => $user_token_admin,
              'user_ip' => $user_ip,
              'type_id' => 2
            ]);
          } else {
            $user_session_admin = \core\PHPLibrary\Client\Session::get_by_ip_and_user_id($system_core, $user_ip, $user->get_id(), 2);
            $user_session_admin->update([]);
          }

          if (!is_null($user_session_base)) {
            $user_session_base->init_data(['updated_unix_timestamp', 'token']);
            $user_session_base_expires = $user_session_base->get_updated_unix_timestamp() + $system_core->configurator->get('session_expires');

            setcookie('_grv_utoken', $user_session_base->get_token(), [
              'expires' => $user_session_base_expires,
              'path' => '/',
              'domain' => $system_core->configurator->get('domain_cookies'),
              'secure' => true,
              'httponly' => true
            ]);
          }

          if (!is_null($user_session_admin)) {
            $user_session_admin->init_data(['updated_unix_timestamp', 'token']);
            $user_session_admin_expires = $user_session_admin->get_updated_unix_timestamp() + $system_core->configurator->get('session_expires');

            setcookie('_grv_atoken', $user_session_admin->get_token(), [
              'expires' => $user_session_admin_expires,
              'path' => '/',
              'domain' => $system_core->configurator->get('domain_cookies'),
              'secure' => true,
              'httponly' => true
            ]);

            $sc_report = \core\PHPLibrary\SystemCore\Report::create($system_core, \core\PHPLibrary\SystemCore\Report::REPORT_TYPE_ID_AP_AUTHORIZATION_SUCCESS, [
              'clientIP' => $system_core->client->get_ip_address(),
              'date' => date('Y/m/d H:i:s', time())
            ]);

            $handler_output_data['reload'] = true;

            /** @var string $handler_message Сообщение обработчика */
            $handler_message = 'Авторизация прошла успешно. Создана новая сессия.';
            $handler_status_code = 1;
          } else {
            /** @var string $handler_message Сообщение обработчика */
            $handler_message = 'Авторизация не была пройдена, так как сессия не была создана.';
          }

        } else {
          $sc_report = \core\PHPLibrary\SystemCore\Report::create($system_core, \core\PHPLibrary\SystemCore\Report::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL, [
            'clientIP' => $system_core->client->get_ip_address(),
            'date' => date('Y/m/d H:i:s', time())
          ]);

          /** @var string $handler_message Сообщение обработчика */
          $handler_message = 'Невозможно завершить авторизацию, поскольку пользователь с такими данными не был найден. Код: 1001';
        }
      } else {
        $sc_report = \core\PHPLibrary\SystemCore\Report::create($system_core, \core\PHPLibrary\SystemCore\Report::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL, [
          'clientIP' => $system_core->client->get_ip_address(),
          'date' => date('Y/m/d H:i:s', time())
        ]);
        
        /** @var string $handler_message Сообщение обработчика */
        $handler_message = 'Невозможно завершить авторизацию, поскольку пользователь с такими данными не был найден. Код: 1002';
      }

    } else {
      /** @var string $handler_message Сообщение обработчика */
      $handler_message = 'Невозможно завершить авторизацию, поскольку не были получены необходимые данные.';

    }
  }

  /** @var string $handler_message Сообщение обработчика */
  $handler_message = (isset($handler_message)) ? $handler_message : 'Обработчик CMS GIRVAS не смог обработать запрос.';
  /** @var int $handler_status_code Статус обработчика */
  $handler_status_code = (isset($handler_status_code)) ? $handler_status_code : 0;
  /** @var array $handler_output_data Выходные данные обработчика */
  $handler_output_data = (isset($handler_output_data)) ? $handler_output_data : [];
  $handler_output_data['debug']['method'] = $_SERVER['REQUEST_METHOD'];
  $handler_output_data['debug']['client_ip'] = $_SERVER['REMOTE_ADDR'];
  $handler_output_data['debug']['post_data'] = (isset($_POST)) ? $_POST : null;
  $handler_output_data['debug']['get_data'] = (isset($_GET)) ? $_GET : null;
  $handler_output_data['debug']['patch_data'] = (isset($_PATCH)) ? $_PATCH : null;
  $handler_output_data['debug']['put_data'] = (isset($_PUT)) ? $_PUT : null;
  $handler_output_data['debug']['delete_data'] = (isset($_DELETE)) ? $_DELETE : null;

  // Выводим результат работы обработчика в JSON-формате
  echo json_encode([
    'message' => $handler_message,
    'statusCode' => $handler_status_code,
    'outputData' => $handler_output_data
  // Убираем экранирующие слеши из ответа, а также преобразовываем UNICODE в текст
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

?>