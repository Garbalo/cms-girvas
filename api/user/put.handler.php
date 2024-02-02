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
use \core\PHPLibrary\UserGroup as UserGroup;

if ($system_core->client->is_logged(2)) {
  $user_creation_allowed = true;

  $user_login = isset($_PUT['user_login']) ? $_PUT['user_login'] : '';
  $user_email = isset($_PUT['user_email']) ? $_PUT['user_email'] : '';
  $user_group_id = isset($_PUT['user_group_id']) ? (int)$_PUT['user_group_id'] : 4;
  $user_password = isset($_PUT['user_password']) ? $_PUT['user_password'] : '';
  $user_password_repeat = isset($_PUT['user_password_repeat']) ? $_PUT['user_password_repeat'] : '';

  if (User::exists_by_login($system_core, $user_login)) {
    $handler_message = (!isset($handler_message)) ? 'Пользователь не был создан, поскольку другой пользователь с таким логином уже существует.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    $user_creation_allowed = false;
  }

  if (User::exists_by_email($system_core, $user_email)) {
    $handler_message = (!isset($handler_message)) ? 'Пользователь не был создан, поскольку другой пользователь с таким e-mail уже существует.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    $user_creation_allowed = false;
  }

  if (isset($user_password) && isset($user_password_repeat)) {
    if (!empty($user_password) || !empty($user_password_repeat)) {
      if ($user_password != $user_password_repeat) {
        $handler_message = (!isset($handler_message)) ? 'Пользователь не был создан, поскольку пароли не совпадают.' : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
        $user_creation_allowed = false;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? 'Пользователь не был создан, поскольку необходимо указать для него пароль.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      $user_creation_allowed = false;
    }
  }

  if (isset($user_email)) {
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
      $handler_message = (!isset($handler_message)) ? 'Пользователь не был создан, поскольку e-mail имеет неверный формат.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      $user_creation_allowed = false;
    }
  }

  if (isset($user_group_id)) {
    if (!isset($user_data)) $user_data = [];
    if (!isset($user_data['metadata_json'])) $user_data['metadata_json'] = [];

    $user_data['metadata_json']['group_id'] = $user_group_id;
  }

  foreach ($_PUT as $key => $value) {
    if (preg_match('/^user\_additional\_field\_([a-z0-9\_]+)$/i', $key, $key_matches, PREG_OFFSET_CAPTURE) && !empty($value)) {
      if (!isset($user_data)) $user_data = [];
      if (!isset($user_data['metadata_json'])) $user_data['metadata_json'] = [];
      if (!isset($user_data['metadata_json']['additionalFields'])) $user_data['metadata_json']['additionalFields'] = [];
      
      $value_name_parts = explode('_', $key_matches[1][0]);
      foreach ($value_name_parts as $part_index => $part) {
        if ($part_index > 0) {
          $value_name_parts[$part_index] = ucfirst($part);
        }
      }

      if (is_bool($value)) $value = (int)$value;

      $user_data['metadata_json']['additionalFields'][implode($value_name_parts)] = $value;
    }
  }

  if ($user_creation_allowed) {
    $user = User::create($system_core, $user_login, $user_email, $user_password);
    
    if (!is_null($user)) {
      $user->init_data(['metadata_json']);

      if (isset($user_data)) {
        $user->update($user_data);
      }

      $handler_output_data['user'] = [];
      $handler_output_data['user']['id'] = $user->get_id();

      $handler_message = (!isset($handler_message)) ? 'Новый пользователь успешно создан.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
    } else {
      $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Новый пользователь не был создан.' : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя неизвестная ошибка.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  $handler_message = (!isset($handler_message)) ? 'Доступ запрещен. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>