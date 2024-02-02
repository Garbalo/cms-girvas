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
  if (isset($_PATCH['module_name'])) {
    $module_name = $_PATCH['module_name'];
    $module = new Module($system_core, $module_name);

    if (isset($_PATCH['module_event'])) {
      $module_event = $_PATCH['module_event'];

      if ($module_event == 'enable') {
        if (!$module->is_enabled()) {
          $module->enable();

          if ($module->is_enabled()) {
            http_response_code(200);
            $handler_message = 'Модуль успешно включен.';
            $handler_status_code = 1;
          } else {
            http_response_code(500);
            $handler_message = 'Ошибка сервера: модуль не был включен.';
            $handler_status_code = 0;
          }
        } else {
          http_response_code(500);
          $handler_message = 'Ошибка сервера: модуль уже включен.';
          $handler_status_code = 0;
        }
      }

      if ($module_event == 'disable') {
        if ($module->is_enabled()) {
          $module->disable();

          if (!$module->is_enabled()) {
            http_response_code(200);
            $handler_message = 'Модуль успешно отключен.';
            $handler_status_code = 1;
          } else {
            http_response_code(500);
            $handler_message = 'Ошибка сервера: модуль не был отключен.';
            $handler_status_code = 0;
          }
        } else {
          http_response_code(500);
          $handler_message = 'Ошибка сервера: модуль уже отключен.';
          $handler_status_code = 0;
        }
      }
    }
  }
}

?>