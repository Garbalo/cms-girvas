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
  if (!empty($_POST)) {
    foreach ($_POST as $setting_name => $setting_value) {
      if (preg_match('/^setting_([a-z0-9_]+)$/', $setting_name, $matches, PREG_OFFSET_CAPTURE)) {
        $setting_name = $matches[1][0];

        if ($setting_name == 'users_additional_field_title' && isset($_POST['_users_additional_fields_locale'])) {
          if ($system_core->configurator->exists_database_entry_value($setting_name)) {
            $fields_titles = json_decode($system_core->configurator->get_database_entry_value($setting_name), true);
            $fields_titles[$_POST['_users_additional_fields_locale']] = $setting_value;
            $setting_value = $fields_titles;
          }
        }

        if ($setting_name == 'users_additional_field_description' && isset($_POST['_users_additional_fields_locale'])) {
          if ($system_core->configurator->exists_database_entry_value($setting_name)) {
            $fields_descriptions = json_decode($system_core->configurator->get_database_entry_value($setting_name), true);
            $fields_descriptions[$_POST['_users_additional_fields_locale']] = $setting_value;
            $setting_value = $fields_descriptions;
          }
        }

        if (is_array($setting_value)) $setting_value = json_encode($setting_value);

        switch ($setting_name) {
          case 'security_allowed_admin_ip': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
          case 'security_allowed_emails': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
          case 'seo_site_keywords': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
          case 'security_premoderation_words_filter_list': $setting_value = (!empty($setting_value)) ? json_encode(preg_split('/\s*\,\s*/', $setting_value)) : json_encode([]); break;
        }

        if ($system_core->configurator->exists_database_entry_value($setting_name)) {
          $system_core->configurator->update_database_entry_value($setting_name, $setting_value);
        } else {
          $system_core->configurator->insert_database_entry_value($setting_name, $setting_value);
        }
      }
    }

    $handler_message = (!isset($handler_message)) ? 'Настройки успешно сохранены.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
  } else {
    $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Настройки не были переданы серверу.' : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  http_response_code(401);
  $handler_message = (!isset($handler_message)) ? 'Произошла внутренняя ошибка. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>