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

use \core\PHPLibrary\Template as Template;

if ($system_core->client->is_logged(2)) {
  $template_name = $_DELETE['template_name'];
  $template_category = $_DELETE['template_category'];
  $template = new Template($system_core, $template_name, $template_category);

  if ($template->exists_core_file()) {
    $system_core::recursive_files_remove($template->get_path());

    $handler_message = (!isset($handler_message)) ? 'Шаблон успешно удален.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
  } else {
    if (file_exists($template->get_path())) {
      $system_core::recursive_files_remove($template->get_path());

      $handler_message = (!isset($handler_message)) ? 'Ядро шаблона не было обнаружено, но осталась его директория, которая только что была удалена.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
    } else {
      $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Шаблон не найден в системе.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  }
} else {
  $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>