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

use \core\PHPLibrary\UserGroup as UserGroup;

if ($system_core->client->is_logged(2)) {
  if (isset($_DELETE['user_group_id'])) {
    $user_group_id = (is_numeric($_DELETE['user_group_id'])) ? (int)$_DELETE['user_group_id'] : 0;

    if (UserGroup::exists_by_id($system_core, $user_group_id)) {
      $user_group = new UserGroup($system_core, $user_group_id);

      $user_group_is_deleted = $user_group->delete();
      if ($user_group_is_deleted) {
        $handler_message = (!isset($handler_message)) ? 'Группа пользователей успешно удалена.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
      } else {
        $handler_message = (!isset($handler_message)) ? 'Группа пользователей не был удалена, поскольку произошел неизвестный сбой.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? 'Группа пользователей не был удалена, поскольку ее не существует.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  }
} else {
  $handler_message = (!isset($handler_message)) ? 'Доступ запрещен. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>