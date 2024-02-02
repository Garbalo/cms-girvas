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

if ($system_core->client->is_logged(2)) {
  if (isset($_DELETE['module_name'])) {
    $module_name = $_DELETE['module_name'];
    $module = new Module($system_core, $module_name);

    if ($module->exists_core_file()) {
      $system_core::recursive_files_remove($module->get_path());

      http_response_code(200);
      $handler_message = 'Модуль успешно удален.';
      $handler_status_code = 1;
    } else {
      if (file_exists($module->get_path())) {
        $system_core::recursive_files_remove($module->get_path());

        http_response_code(200);
        $handler_message = 'Ядро модуля не было обнаружено, однако его папка осталась - она удалена.';
        $handler_status_code = 1;
      } else {
        http_response_code(500);
        $handler_message = 'Ошибка сервера: модуль невозможно удалить, так как он не обнаружен.';
        $handler_status_code = 0;
      }
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