<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

 if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

use \core\PHPLibrary\User as User;
use \core\PHPLibrary\UserGroup as UserGroup;
use \core\PHPLibrary\SystemCore\Locale as Locale;

if ($system_core->client->is_logged(2)) {
  if (isset($_PATCH['user_group_id'])) {
    $users_group_id = (is_numeric($_PATCH['user_group_id'])) ? (int)$_PATCH['user_group_id'] : 0;

    if (UserGroup::exists_by_id($system_core, $users_group_id)) {
      $users_group = new UserGroup($system_core, $users_group_id);
      $users_group_data = [];

      $cms_locales_names = $system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new Locale($system_core, $cms_locale_name);

          $users_groups_title_input_name = sprintf('user_group_title_%s', $cms_locale->get_iso_639_2());

          if (!array_key_exists('metadata', $users_group_data)) $users_group_data['metadata'] = [];

          if (array_key_exists($users_groups_title_input_name, $_PATCH)) {
            if (!array_key_exists('texts', $users_group_data)) $users_group_data['texts'] = [];
            if (!array_key_exists($cms_locale->get_name(), $users_group_data['texts'])) $users_group_data['texts'][$cms_locale->get_name()] = [];

            if (array_key_exists($users_groups_title_input_name, $_PATCH)) $users_group_data['texts'][$cms_locale->get_name()]['title'] = $_PATCH[$users_groups_title_input_name];
          }
        }
      }

      if (isset($_PATCH['user_group_name'])) $users_group_data['name'] = $_PATCH['user_group_name'];

      $users_group_is_updated = $users_group->update($users_group_data);

      if ($users_group_is_updated) {
        $handler_message = 'Группа пользователей успешно сохранена.';
        $handler_status_code = 1;
      } else {
        $handler_message = 'Группа пользователей не была сохранена, поскольку произошел неизвестный сбой.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Группа пользователей не обновлена, поскольку ее не существует.';
      $handler_status_code = 0;
    }
  }
}

?>