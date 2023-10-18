<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
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

  // Modules API
  if ($system_core->urlp->get_path(1) == 'module') {
    $api_file_path = sprintf('%s/api/module.api.php', CMS_ROOT_DIRECTORY);
    include_once($api_file_path);
  }

  // Users API
  if ($system_core->urlp->get_path(1) == 'user' && !is_null($system_core->urlp->get_path(2))) {
    $api_file_path = sprintf('%s/api/user.api.php', CMS_ROOT_DIRECTORY);
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

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'webChannel' && is_numeric($system_core->urlp->get_path(2))) {
    $web_channel_id = (int)$system_core->urlp->get_path(2);

    if (\core\PHPLibrary\WebChannel::exists_by_id($system_core, $web_channel_id)) {
      $web_channel = new \core\PHPLibrary\WebChannel($system_core, $web_channel_id);
      $web_channel->init_data(['name', 'type_id', 'entries_category_id', 'texts', 'created_unix_timestamp', 'updated_unix_timestamp']);
      $web_channel_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $system_core->configurator->get_database_entry_value('base_locale');

      $handler_output_data['webChannel'] = [];
      $handler_output_data['webChannel']['id'] = $web_channel->get_id();
      $handler_output_data['webChannel']['name'] = $web_channel->get_name();
      $handler_output_data['webChannel']['title'] = $web_channel->get_title($web_channel_locale);
      $handler_output_data['webChannel']['description'] = $web_channel->get_description($web_channel_locale);
      $handler_output_data['webChannel']['typeID'] = $web_channel->get_type_id();
      $handler_output_data['webChannel']['entriesCategoryID'] = $web_channel->get_entries_category_id();
      $handler_output_data['webChannel']['createdUnixTimestamp'] = $web_channel->get_created_unix_timestamp();
      $handler_output_data['webChannel']['updatedUnixTimestamp'] = $web_channel->get_updated_unix_timestamp();

      $handler_message = 'Данные по веб-каналу успешно получены.';
      $handler_status_code = 1;
    } else {
      $handler_message = 'Данные по веб-каналу не были получены, так как его не существует.';
      $handler_status_code = 0;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && $system_core->urlp->get_path(1) == 'webChannel' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PATCH['web_channel_event_save']) && $system_core->client->is_logged(2)) {
      if (isset($_PATCH['web_channel_id'])) {
        $web_channel_id = (is_numeric($_PATCH['web_channel_id'])) ? (int)$_PATCH['web_channel_id'] : 0;

        if (\core\PHPLibrary\WebChannel::exists_by_id($system_core, $web_channel_id)) {
          $web_channel = new \core\PHPLibrary\WebChannel($system_core, $web_channel_id);
          
          $web_channel_data = [];

          if (array_key_exists('web_channel_title_rus', $_PATCH) || array_key_exists('web_channel_description_rus', $_PATCH)) {
            if (!array_key_exists('texts', $web_channel_data)) $web_channel_data['texts'] = [];
            if (!array_key_exists('ru_RU', $web_channel_data['texts'])) $web_channel_data['texts']['ru_RU'] = [];

            if (array_key_exists('web_channel_title_rus', $_PATCH)) $web_channel_data['texts']['ru_RU']['title'] = $_PATCH['web_channel_title_rus'];
            if (array_key_exists('web_channel_description_rus', $_PATCH)) $web_channel_data['texts']['ru_RU']['description'] = $_PATCH['web_channel_description_rus'];
          }

          if (array_key_exists('web_channel_title_eng', $_PATCH) || array_key_exists('web_channel_description_eng', $_PATCH)) {
            if (!array_key_exists('texts', $web_channel_data)) $web_channel_data['texts'] = [];
            if (!array_key_exists('en_US', $web_channel_data['texts'])) $web_channel_data['texts']['en_US'] = [];

            if (array_key_exists('web_channel_title_eng', $_PATCH)) $web_channel_data['texts']['en_US']['title'] = $_PATCH['web_channel_title_eng'];
            if (array_key_exists('web_channel_description_eng', $_PATCH)) $web_channel_data['texts']['en_US']['description'] = $_PATCH['web_channel_description_eng'];
          }

          if (isset($_PATCH['web_channel_name'])) $web_channel_data['name'] = $_PATCH['web_channel_name'];
          if (isset($_PATCH['web_channel_type_id'])) $web_channel_data['type_id'] = $_PATCH['web_channel_type_id'];
          if (isset($_PATCH['web_channel_entries_category_id'])) $web_channel_data['entries_category_id'] = $_PATCH['web_channel_entries_category_id'];

          $web_channel_is_updated = $web_channel->update($web_channel_data);

          if ($web_channel_is_updated) {
            $handler_message = 'Веб-канал успешно сохранен.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Веб-канал не был сохранен, поскольку произошел неизвестный сбой.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Веб-канал не обновлен, поскольку его не существует.';
          $handler_status_code = 0;
        }
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $system_core->urlp->get_path(1) == 'webChannel' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PUT['web_channel_event_save']) && $system_core->client->is_logged(2)) {
      $web_channel_name = isset($_PUT['web_channel_name']) ? $_PUT['web_channel_name'] : '';
      $web_channel_entries_category_id = isset($_PUT['web_channel_entries_category_id']) ? (int)$_PUT['web_channel_entries_category_id'] : '';
      $web_channel_type_id = isset($_PUT['web_channel_type_id']) ? (int)$_PUT['web_channel_type_id'] : '';

      $texts = [];

      $cms_locales_names = $system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new \core\PHPLibrary\SystemCore\Locale($system_core, $cms_locale_name);

          $title_input_name = sprintf('web_channel_title_%s', $cms_locale->get_iso_639_2());
          $description_textarea_name = sprintf('web_channel_description_%s', $cms_locale->get_iso_639_2());

          if (array_key_exists($title_input_name, $_PUT) || array_key_exists($description_textarea_name, $_PUT)) {
            if (!array_key_exists($cms_locale->get_name(), $texts)) $texts[$cms_locale->get_name()] = [];

            if (array_key_exists($title_input_name, $_PUT)) $texts[$cms_locale->get_name()]['title'] = $_PUT[$title_input_name];
            if (array_key_exists($description_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['description'] = $_PUT[$description_textarea_name];
          }
        }
      }

      //$client_session = $system_core->client->get_session(2, ['user_id']);
      $web_channel = \core\PHPLibrary\WebChannel::create($system_core, $web_channel_name, $web_channel_entries_category_id, $web_channel_type_id, $texts);
      if (!is_null($web_channel)) {
        $handler_message = 'Веб-канал успешно создан.';
        $handler_status_code = 1;

        $handler_output_data['href'] = sprintf('/admin/webChannel/%d', $web_channel->get_id());
      } else {
        $handler_message = 'Произошла внутренняя ошибка. Веб-канал не был создан.';
        $handler_status_code = 0;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $system_core->urlp->get_path(1) == 'webChannel' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_DELETE['web_channel_event_delete']) && $system_core->client->is_logged(2)) {
      if (isset($_DELETE['web_channel_id'])) {
        $web_channel_id = (is_numeric($_DELETE['web_channel_id'])) ? (int)$_DELETE['web_channel_id'] : 0;

        if (\core\PHPLibrary\WebChannel::exists_by_id($system_core, $web_channel_id)) {
          $web_channel = new \core\PHPLibrary\WebChannel($system_core, $web_channel_id);

          $web_channel_is_deleted = $web_channel->delete();
          if ($web_channel_is_deleted) {
            $handler_message = 'Веб-канал успешно удален.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Веб-канал не была удален, поскольку произошел неизвестный сбой.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Веб-канал не удален, поскольку его не существует.';
          $handler_status_code = 0;
        }

        $handler_output_data['modalClose'] = true;
        $handler_output_data['reload'] = true;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'webChannels') {
    if ($system_core->urlp->get_path(2) == 'types') {
      $handler_output_data['webChannelsTypes'] = [
        ['id' => 1, 'name' => 'rss1-0', 'title' => 'RSS 1.0'],
        ['id' => 2, 'name' => 'rss2-0', 'title' => 'RSS 2.0'],
        ['id' => 3, 'name' => 'atom', 'title' => 'Atom']
      ];
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

  if ($_SERVER['REQUEST_METHOD'] == 'GET' && $system_core->urlp->get_path(1) == 'modal') {
    if (preg_match('/^(admin)\-/i', $system_core->urlp->get_param('name'), $matches)) {
      $template = new \core\PHPLibrary\Template($system_core, 'default', 'admin');
    } else {
      $template = new \core\PHPLibrary\Template($system_core, 'official');
    }
    
    switch ($system_core->urlp->get_param('name')) {
      case 'admin-entry-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/entryDelete.tpl', [
        'ENTRY_ID' => $system_core->urlp->get_param('entryID')
      ]); http_response_code(200); break;
      case 'admin-page-static-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/pageStaticDelete.tpl', [
        'PAGE_STATIC_ID' => $system_core->urlp->get_param('pageStaticID')
      ]); http_response_code(200); break;
      case 'admin-user-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/userDelete.tpl', [
        'USER_ID' => $system_core->urlp->get_param('userID')
      ]); http_response_code(200); break;
      case 'admin-web-channel-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/webChannelDelete.tpl', [
        'WEB_CHANNEL_ID' => $system_core->urlp->get_param('webChannelID')
      ]); http_response_code(200); break;
      case 'admin-user-group-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/userGroupDelete.tpl', [
        'USER_GROUP_ID' => $system_core->urlp->get_param('userGroupID')
      ]); http_response_code(200); break;
      case 'admin-template-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/templateDelete.tpl', [
        'TEMPLATE_NAME' => $system_core->urlp->get_param('templateName')
      ]); http_response_code(200); break;
      case 'admin-module-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/moduleDelete.tpl', [
        'MODULE_NAME' => $system_core->urlp->get_param('moduleName')
      ]); http_response_code(200); break;
      case 'admin-media-delete': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/mediaDelete.tpl', [
        'MEDIA_FILE_FULLNAME' => $system_core->urlp->get_param('mediaFileFullname')
      ]); http_response_code(200); break;
      case 'user-auth': $template = \core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/modal/userAuth.tpl', [
        //
      ]); http_response_code(200); break;
    }

    $handler_output_data['template'] = $template;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && $system_core->urlp->get_path(1) == 'registration') {
    $handler_output_data['notificationContainerTargetID'] = 'NE7648538676';
    
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
  }

  // Манипуляция с пользователями
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $system_core->urlp->get_path(1) == 'user' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_DELETE['user_event_delete']) && $system_core->client->is_logged(2)) {
      if (isset($_DELETE['user_id'])) {
        $user_id = (is_numeric($_DELETE['user_id'])) ? (int)$_DELETE['user_id'] : 0;

        if (\core\PHPLibrary\User::exists_by_id($system_core, $user_id)) {
          $user = new \core\PHPLibrary\User($system_core, $user_id);

          $user_is_deleted = $user->delete();
          if ($user_is_deleted) {
            $handler_message = 'Пользователь успешно удален.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Пользователь не был удален, поскольку произошел неизвестный сбой.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Пользователь не удален, поскольку его не существует.';
          $handler_status_code = 0;
        }

        $handler_output_data['modalClose'] = true;
        $handler_output_data['reload'] = true;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $system_core->urlp->get_path(1) == 'user' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PUT['user_event_save']) && $system_core->client->is_logged(2)) {
      $user_login = isset($_PUT['user_login']) ? $_PUT['user_login'] : '';
      $user_email = isset($_PUT['user_email']) ? $_PUT['user_email'] : '';
      $user_password = isset($_PUT['user_password']) ? $_PUT['user_password'] : '';
      $user_password_repeat = isset($_PUT['user_password_repeat']) ? $_PUT['user_password_repeat'] : '';

      $client_session = $system_core->client->get_session(2, ['user_id']);

      if ($user_password == $user_password_repeat) {
        if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
          $user = \core\PHPLibrary\User::create($system_core, $user_login, $user_email, $user_password);
          if (!is_null($user)) {
            $handler_message = 'Пользователь успешно создан.';
            $handler_status_code = 1;

            $handler_output_data['href'] = sprintf('/admin/user/%d', $user->get_id());
          } else {
            $handler_message = 'Произошла внутренняя ошибка. Пользователь не был создан.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Данные пользователя не были сохранены, поскольку e-mail имеет неверный формат.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Данные пользователя не были сохранены, поскольку пароли не совпадают.';
        $handler_status_code = 0;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && $system_core->urlp->get_path(1) == 'user' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PATCH['user_event_save']) && $system_core->client->is_logged(2)) {
      if (isset($_PATCH['user_id'])) {
        $user_id = (is_numeric($_PATCH['user_id'])) ? (int)$_PATCH['user_id'] : 0;

        if (\core\PHPLibrary\User::exists_by_id($system_core, $user_id)) {
          $user = new \core\PHPLibrary\User($system_core, $user_id);
          $user->init_data(['security_hash']);

          $user_data = [];

          if (isset($_PATCH['user_login'])) $user_data['login'] = $_PATCH['user_login'];
          if (isset($_PATCH['user_email'])) $user_email = $_PATCH['user_email'];
          if (isset($_PATCH['user_password'])) $user_password = $_PATCH['user_password'];
          if (isset($_PATCH['user_password_repeat'])) $user_password_repeat = $_PATCH['user_password_repeat'];

          if ($user_password == $user_password_repeat) {
            $user_data['password_hash'] = \core\PHPLibrary\User::password_hash($system_core, $user->get_security_hash(), $user_password);
            if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
              $user_data['email'] = $user_email;
              $user_is_updated = $user->update($user_data);

              if ($user_is_updated) {
                $handler_message = 'Данные пользователя успешно сохранены.';
                $handler_status_code = 1;
              } else {
                $handler_message = 'Данные пользователя не были сохранены, поскольку произошел неизвестный сбой.';
                $handler_status_code = 0;
              }
            } else {
              $handler_message = 'Данные пользователя не были сохранены, поскольку e-mail имеет неверный формат.';
              $handler_status_code = 0;
            }
          } else {
            $handler_message = 'Данные пользователя не были сохранены, поскольку пароли не совпадают.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Данные пользователя не были сохранены, поскольку его не существует.';
          $handler_status_code = 0;
        }
      }
    }
  }

  // Манипуляция с группами пользователей
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && $system_core->urlp->get_path(1) == 'userGroup' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_DELETE['user_group_event_delete']) && $system_core->client->is_logged(2)) {
      if (isset($_DELETE['user_group_id'])) {
        $user_group_id = (is_numeric($_DELETE['user_group_id'])) ? (int)$_DELETE['user_group_id'] : 0;

        if (\core\PHPLibrary\UserGroup::exists_by_id($system_core, $user_group_id)) {
          $user_group = new \core\PHPLibrary\UserGroup($system_core, $user_group_id);

          $user_group_is_deleted = $user_group->delete();
          if ($user_group_is_deleted) {
            $handler_message = 'Группа пользователей успешно удалена.';
            $handler_status_code = 1;
          } else {
            $handler_message = 'Группа пользователей не была удалена, поскольку произошел неизвестный сбой.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Группа пользователей не удалена, поскольку ее не существует.';
          $handler_status_code = 0;
        }

        $handler_output_data['modalClose'] = true;
        $handler_output_data['reload'] = true;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PUT' && $system_core->urlp->get_path(1) == 'userGroup' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PUT['user_group_event_save']) && $system_core->client->is_logged(2)) {
      $user_group_name = isset($_PUT['user_group_name']) ? $_PUT['user_group_name'] : '';
      
      if (!empty($user_group_name)) {
        if (!\core\PHPLibrary\UserGroup::exists_by_name($system_core, $user_group_name)) {
          if (preg_match('/[a-z\_]+/i', $user_group_name)) {
            $user_group_permissions = 0x0000000000000000;
            $user_group_permissions_array = isset($_PUT['user_group_permissions']) ? $_PUT['user_group_permissions'] : [];
            if (!empty($user_group_permissions_array)) {
              foreach ($user_group_permissions_array as $user_group_permission) {
                switch ($user_group_permission) {
                  case 'admin_panel_auth': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_PANEL_AUTH; break;
                  case 'admin_users_ban': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_USERS_BAN; break;
                }
              }
            }

            $user_group = \core\PHPLibrary\UserGroup::create($system_core, $user_group_name, $user_group_permissions);
            if (!is_null($user_group)) {
              $handler_message = 'Группа пользователей успешно создана.';
              $handler_status_code = 1;

              $handler_output_data['href'] = sprintf('/admin/userGroup/%d', $user_group->get_id());
            } else {
              $handler_message = 'Произошла внутренняя ошибка. Группа пользователей не была создана.';
              $handler_status_code = 0;
            }
          } else {
            $handler_message = 'Данные группы пользователей не были сохранены, поскольку наименование имеет неверный формат.';
            $handler_status_code = 0;
          }
        } else {
          $handler_message = 'Данные группы пользователей не были сохранены, поскольку указанное наименование уже используется.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Данные группы пользователей не были сохранены, поскольку наименование не может быть пустым.';
        $handler_status_code = 0;
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'PATCH' && $system_core->urlp->get_path(1) == 'userGroup' && is_null($system_core->urlp->get_path(2))) {
    if (isset($_PATCH['user_group_event_save']) && $system_core->client->is_logged(2)) {
      if (isset($_PATCH['user_group_id'])) {
        $user_group_id = (is_numeric($_PATCH['user_group_id'])) ? (int)$_PATCH['user_group_id'] : 0;

        if (\core\PHPLibrary\UserGroup::exists_by_id($system_core, $user_group_id)) {
          $user_group = new \core\PHPLibrary\UserGroup($system_core, $user_group_id);

          $user_group_data = [];

          if (isset($_PATCH['user_group_name'])) $user_group_data['name'] = $_PATCH['user_group_name'];
          
          $user_group_permissions = 0x0000000000000000;
          $user_group_permissions_array = isset($_PATCH['user_group_permissions']) ? $_PATCH['user_group_permissions'] : [];
          
          if (!empty($user_group_permissions_array)) {
            foreach ($user_group_permissions_array as $user_group_permission) {
              switch ($user_group_permission) {
                case 'admin_panel_auth': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_PANEL_AUTH; break;
                case 'admin_users_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_USERS_MANAGEMENT; break;
                case 'admin_users_groups_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_USERS_GROUPS_MANAGEMENT; break;
                case 'admin_modules_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_MODULES_MANAGEMENT; break;
                case 'admin_templates_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_TEMPLATES_MANAGEMENT; break;
                case 'admin_settings_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_SETTINGS_MANAGEMENT; break;
                case 'admin_viewing_logs': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_ADMIN_VIEWING_LOGS; break;
                case 'moder_users_ban': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_MODER_USERS_BAN; break;
                case 'moder_entries_comments_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT; break;
                case 'moder_users_warns': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_MODER_USERS_WARNS; break;
                case 'editor_media_files_management': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_EDITOR_MEDIA_FILES_MANAGEMENT; break;
                case 'editor_entries_edit': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_EDITOR_ENTRIES_EDIT; break;
                case 'editor_entries_categories_edit': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT; break;
                case 'editor_pages_static_edit': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_EDITOR_PAGES_STATIC_EDIT; break;
                case 'base_entry_comment_rate': $user_group_permissions = $user_group_permissions | \core\PHPLibrary\UserGroup::PERMISSION_BASE_ENTRY_COMMENT_RATE; break;
              }
            }
          }

          $user_group_data['permissions'] = $user_group_permissions;

          if (preg_match('/[a-z\_]+/i', $user_group_data['name'])) {
            $user_group_is_updated = $user_group->update($user_group_data);

            if ($user_group_is_updated) {
              $handler_message = 'Данные группы пользователей успешно сохранены.';
              $handler_status_code = 1;
            } else {
              $handler_message = 'Данные группы пользователей не были сохранены, поскольку произошел неизвестный сбой.';
              $handler_status_code = 0;
            }
          }
          
        } else {
          $handler_message = 'Данные группы пользователей не были сохранены, поскольку ее не существует.';
          $handler_status_code = 0;
        }
      }
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

          // Если сессия не была найдена, то создаем новую.
          if (!\core\PHPLibrary\Client\Session::exists_by_ip($system_core, $user_ip, 1)) {
            
            /** @var \core\PHPLibrary\Client\Session|null $user_session */
            $user_session = \core\PHPLibrary\Client\Session::create($system_core, [
              'user_id' => $user->get_id(),
              'token' => $user_token,
              'user_ip' => $user_ip,
              'type_id' => 1
            ]);

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
              $handler_message = 'Авторизация прошла успешно. Создана новая сессия.';
              $handler_status_code = 1;
            } else {
              /** @var string $handler_message Сообщение обработчика */
              $handler_message = 'Авторизация не была пройдена, так как сессия не была создана.';
            }
          
          // Если сессия была найдена, то восстанавливаем или обновляем старую.
          } else {

            /** @var \core\PHPLibrary\Session|null $user_session Объект сессии пользователя */
            $user_session = \core\PHPLibrary\Client\Session::get_by_ip($system_core, $user_ip, 1);
            
            if (!is_null($user_session)) {
              $user_session->reset_expire();
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

              $handler_message = 'Авторизация успешно завершена. Сессия обновлена.';
              $handler_status_code = 1;
            } else {
              /** @var string $handler_message Сообщение обработчика */
              $handler_message = 'Авторизация не была пройдена, так как сессия не была восстановлена.';
            }

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
          $user_token = \core\PHPLibrary\Client\Session::generate_token();

          // Если сессия не была найдена, то создаем новую.
          if (!\core\PHPLibrary\Client\Session::exists_by_ip($system_core, $user_ip, 2)) {
            
            /** @var \core\PHPLibrary\Client\Session|null $user_session */
            $user_session = \core\PHPLibrary\Client\Session::create($system_core, [
              'user_id' => $user->get_id(),
              'token' => $user_token,
              'user_ip' => $user_ip,
              'type_id' => 2
            ]);

            if (!is_null($user_session)) {
              $user_session->init_data(['updated_unix_timestamp', 'token']);
              $user_session_expires = $user_session->get_updated_unix_timestamp() + $system_core->configurator->get('session_expires');

              setcookie('_grv_atoken', $user_session->get_token(), [
                'expires' => $user_session_expires,
                'path' => '/',
                'domain' => $system_core->configurator->get('domain'),
                'secure' => true,
                'httponly' => true
              ]);

              $handler_output_data['reload'] = true;

              /** @var string $handler_message Сообщение обработчика */
              $handler_message = 'Авторизация прошла успешно. Создана новая сессия.';
              $handler_status_code = 1;
            } else {
              /** @var string $handler_message Сообщение обработчика */
              $handler_message = 'Авторизация не была пройдена, так как сессия не была создана.';
            }
          
          // Если сессия была найдена, то восстанавливаем или обновляем старую.
          } else {

            /** @var \core\PHPLibrary\Session|null $user_session Объект сессии пользователя */
            $user_session = \core\PHPLibrary\Client\Session::get_by_ip($system_core, $user_ip, 2);
            
            if (!is_null($user_session)) {
              $user_session->reset_expire();
              $user_session->init_data(['updated_unix_timestamp', 'token']);
              $user_session_expires = $user_session->get_updated_unix_timestamp() + $system_core->configurator->get('session_expires');

              setcookie('_grv_atoken', $user_session->get_token(), [
                'expires' => $user_session_expires,
                'path' => '/',
                'domain' => $system_core->configurator->get('domain'),
                'secure' => true,
                'httponly' => true
              ]);

              $handler_output_data['reload'] = true;

              $handler_message = 'Авторизация успешно завершена. Сессия обновлена.';
              $handler_status_code = 1;
            } else {
              /** @var string $handler_message Сообщение обработчика */
              $handler_message = 'Авторизация не была пройдена, так как сессия не была восстановлена.';
            }

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