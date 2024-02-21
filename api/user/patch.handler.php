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

if ($system_core->client->is_logged(2)) {
  if (isset($_PATCH['user_id'])) {
    $user_id = (is_numeric($_PATCH['user_id'])) ? (int)$_PATCH['user_id'] : 0;

    if (User::exists_by_id($system_core, $user_id)) {
      $user = new User($system_core, $user_id);
      $user->init_data(['security_hash']);

      $user_data = [];

      if (isset($_PATCH['user_is_block'])) {
        if (!isset($user_data['metadata_json'])) $user_data['metadata_json'] = [];
        $user_data['metadata_json']['isBlocked'] = (int)$_PATCH['user_is_block'];
      }

      if (isset($_PATCH['user_login'])) $user_data['login'] = $_PATCH['user_login'];
      if (isset($_PATCH['user_email'])) $user_email = $_PATCH['user_email'];
      if (isset($_PATCH['user_group_id'])) $user_data['group_id'] = (int)$_PATCH['user_group_id'];
      if (isset($_PATCH['user_password'])) $user_password = $_PATCH['user_password'];
      if (isset($_PATCH['user_password_repeat'])) $user_password_repeat = $_PATCH['user_password_repeat'];

      if (isset($user_password) && isset($user_password_repeat)) {
        if (!empty($user_password) || !empty($user_password_repeat)) {
          if ($user_password == $user_password_repeat) {
            $user_data['password_hash'] = User::password_hash($system_core, $user->get_security_hash(), $user_password);
          } else {
            $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_USER_ERROR_INVALID_REPEAT_PASSWORD')) : $handler_message;
            $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
          }
        }
      }

      if (isset($user_email)) {
        if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
          $user_data['email'] = $user_email;
        } else {
          $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_USER_ERROR_NOT_FOUND')) : $handler_message;
          $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
        }
      }

      $user_is_updated = $user->update($user_data);
      if ($user_is_updated) {
        $handler_message = (!isset($handler_message)) ? $system_core->locale->get_single_value_by_key('API_PATCH_DATA_SUCCESS') : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
      } else {
        $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN')) : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_USER_ERROR_NOT_FOUND')) : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_INVALID_INPUT_DATA_SET')) : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION')) : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>