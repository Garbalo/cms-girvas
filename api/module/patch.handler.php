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
  $client_user = $system_core->client->get_user(2);
  $client_user->init_data(['metadata_json']);
  $client_user_group = $client_user->get_group();
  $client_user_group->init_data(['permissions']);

  if ($client_user_group->permission_check($client_user_group::PERMISSION_ADMIN_MODULES_MANAGEMENT)) {
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
              $handler_message = $system_core->locale->get_single_value_by_key('API_MODULE_ENABLED');
              $handler_status_code = 1;
            } else {
              http_response_code(500);
              $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN'));
              $handler_status_code = 0;
            }
          } else {
            http_response_code(500);
            $handler_message = $system_core->locale->get_single_value_by_key('API_MODULE_ALREADY_ENABLED');
            $handler_status_code = 0;
          }
        }

        if ($module_event == 'disable') {
          if ($module->is_enabled()) {
            $module->disable();

            if (!$module->is_enabled()) {
              http_response_code(200);
              $handler_message = $system_core->locale->get_single_value_by_key('API_MODULE_DISABLED');
              $handler_status_code = 1;
            } else {
              http_response_code(500);
              $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN'));
              $handler_status_code = 0;
            }
          } else {
            http_response_code(500);
            $handler_message = $system_core->locale->get_single_value_by_key('API_MODULE_ALREADY_DISABLED');
            $handler_status_code = 0;
          }
        }
      }
    }
  } else {
    $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_DONT_HAVE_PERMISSIONS'));
    $handler_status_code = 0;
  }
} else {
  http_response_code(401);
  $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION'));
  $handler_status_code = 0;
}

?>