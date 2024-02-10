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

use \core\PHPLibrary\WebChannel as WebChannel;

if ($system_core->client->is_logged(2)) {
  $web_channel_id = (isset($_DELETE['web_channel_id'])) ? $_DELETE['web_channel_id'] : 0;
  $web_channel_id = (is_numeric($web_channel_id)) ? (int)$web_channel_id : 0;

  if ($web_channel_id != 0) {
    if (WebChannel::exists_by_id($system_core, $web_channel_id)) {
      $web_channel = new WebChannel($system_core, $web_channel_id);

      $web_channel_is_deleted = $web_channel->delete();
      if ($web_channel_is_deleted) {
        $handler_message = (!isset($handler_message)) ? 'Веб-канал успешно удален.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
      } else {
        $handler_message = (!isset($handler_message)) ? 'Произошла неизвестная внутренняя ошибка.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Веб-канала не существует.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Передан неверный ID.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  $handler_message = (!isset($handler_message)) ? 'Доступ запрещен. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>