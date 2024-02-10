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

use \core\PHPLibrary\Module as Module;
use \ZipArchive as ZipArchive;

if ($system_core->client->is_logged(2)) {
  if (isset($_POST['module_name']) && $system_core->urlp->get_path(2) == 'install') {
    $module_name = $_POST['module_name'];
    $module = new Module($system_core, $module_name);

    if (!$module->exists_core_file()) {
      $module_repository_url = sprintf('https://repository.cms-girvas.ru/modules/%s', $module_name);

      $curl = curl_init($module_repository_url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $curl_exucute_result = json_decode(curl_exec($curl), true);
      curl_close($curl);

      if (!empty($curl_exucute_result['outputData'])) {
        $module_folder_path = sprintf('%s/modules/%s', CMS_ROOT_DIRECTORY, $module_name);
        $module_archive_path = sprintf('%s/modules/%s.zip', CMS_ROOT_DIRECTORY, $module_name);

        $curl = curl_init($module_archive_path);
        curl_setopt($curl, CURLOPT_URL, $curl_exucute_result['outputData']['download_url']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curl_exucute_result = curl_exec($curl);
        curl_close($curl);

        $file = fopen($module_archive_path, "w+");
        fputs($file, $curl_exucute_result);
        fclose($file);

        if (file_exists($module_archive_path)) {
          $zip_archive = new ZipArchive();

          if ($zip_archive->open($module_archive_path) === true) {
            mkdir($module_folder_path);

            $zip_archive_extracted = $zip_archive->extractTo($module_folder_path);
            $zip_archive->close();

            unlink($module_archive_path);

            if ($zip_archive_extracted) {
              $module_core_path = sprintf('%s/core.class.php', $module_folder_path);
              $module_core_namespace = sprintf('\\modules\\%s\\Core', $module_name);
              
              if (file_exists($module_core_path)) {
                require_once($module_core_path);

                $module_core = new $module_core_namespace($system_core, $module);

                if (method_exists($module_core, 'install')) {
                  $module_core->install();
                }

                http_response_code(200);
                $handler_message = 'Модуль загружен.';
                $handler_status_code = 1;
              } else {
                http_response_code(500);
                $handler_message = 'Ошибка сервера: ядро загруженного модуля не обнаружено.';
                $handler_status_code = 0;
              }
            } else {
              http_response_code(500);
              $handler_message = 'Ошибка сервера: разархивация модуля невозможна.';
              $handler_status_code = 0;
            }
          } else {
            http_response_code(500);
            $handler_message = 'Ошибка сервера: сервер не смог открыть архив модуля.';
            $handler_status_code = 0;
          }
        } else {
          http_response_code(500);
          $handler_message = 'Ошибка сервера: архив модуля не был обнаружен.';
          $handler_status_code = 0;
        }
      } else {
        http_response_code(500);
        $handler_message = 'Ошибка сервера: репозиторий не смог предоставить данные по модулю.';
        $handler_status_code = 0;
      }
    } else {
      http_response_code(500);
      $handler_message = 'Ошибка сервера: модуль уже загружен.';
      $handler_status_code = 0;
    }
  } else {
    http_response_code(400);
    $handler_message = 'Ошибка запроса: отсутствует техническое имя модуля.';
    $handler_status_code = 0;
  }
} else {
  http_response_code(401);
  $handler_message = 'Ошибка авторизации: доступ запрещен.';
  $handler_status_code = 0;
}


?>