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

if ($system_core->client->is_logged(2)) {
  $file_fullname = $_DELETE['media_file_fullname'];

  if (isset($_DELETE['media_file_fullname'])) {
    $media_dir_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
    $media_file_dir_path = sprintf('%s/%s', $media_dir_path, $file_fullname);

    if (file_exists($media_file_dir_path)) {
      unlink($media_file_dir_path);

      if (!file_exists($media_file_dir_path)) {
        $handler_message = (!isset($handler_message)) ? 'Медиа-файл успешно удален.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
      } else {
        $handler_message = (!isset($handler_message)) ? 'Произошла неизвестная внутренняя ошибка. Медиа-файл не был удален.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Медиа-файл не найден.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    http_response_code(400);
    $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Наименование медиа-файла не передано.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  http_response_code(401);
  $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}


?>