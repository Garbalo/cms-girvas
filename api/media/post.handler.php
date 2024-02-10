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

use \core\PHPLibrary\SystemCore\FileConverter as FileConverter;
use \core\PHPLibrary\SystemCore\FileConverter\EnumFileFormat as EnumFileFormat;

if ($system_core->client->is_logged(2)) {
  if (!empty($_FILES)) {
    $handler_output_data['debug_files'] = $_FILES;

    $uploaded_file_extention = pathinfo($_FILES['mediaFile']['name'], PATHINFO_EXTENSION);
    $file_extention_allowed = ['png', 'gif', 'jpg', 'jpeg', 'webp'];
    $uploaded_dir_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);

    if (!file_exists($uploaded_dir_path)) {
      mkdir($uploaded_dir_path, 0777);
    }

    if (file_exists($uploaded_dir_path)) {
      if (in_array($uploaded_file_extention, $file_extention_allowed)) {
        $file_uploaded_folder_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
           
        $file_converter = new FileConverter($system_core);
        $file_converted = $file_converter->convert($_FILES['mediaFile'], $file_uploaded_folder_path, EnumFileFormat::WEBP, true);
        
        if (is_array($file_converted)) {
          $handler_output_data['file'] = [];
          $handler_output_data['file']['url'] = sprintf('/uploads/media/%s', $file_converted['file_name']);
          $handler_output_data['file']['fullname'] = $file_converted['file_name'];

          $handler_message = (!isset($handler_message)) ? 'Медиа-файл успешно загружен.' : $handler_message;
          $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
        } else {
          $handler_message = (!isset($handler_message)) ? 'Произошла неизвестная внутренняя ошибка. Медиа-файл не был загружен.' : $handler_message;
          $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
        }
      } else {
        $handler_message = (!isset($handler_message)) ? sprintf('Произошла внутренняя ошибка. Медиа-файл с данным расширением [%s] не подлежит загрузке на сервер.', $uploaded_file_extention) : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Медиа-файл не может быть загружен, поскольку директория [/uploads/media] для их загрузки отсутствует.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Медиа-файлы не были переданы на сервер.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  http_response_code(401);
  $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>