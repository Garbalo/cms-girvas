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

use \core\PHPLibrary\User as User;

if (isset($_DELETE['user_id'])) {
  $user_id = (is_numeric($_DELETE['user_id'])) ? (int)$_DELETE['user_id'] : 0;

  if (User::exists_by_id($system_core, $user_id)) {
    $user = new User($system_core, $user_id);

    $user_is_deleted = $user->delete();
    if ($user_is_deleted) {
      $handler_message = (!isset($handler_message)) ? 'Пользователь успешно удален.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
    } else {
      $handler_message = (!isset($handler_message)) ? 'Пользователь не был удален, поскольку произошел неизвестный сбой.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? 'Данные пользователя не были сохранены, поскольку его не существует.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
}

?>