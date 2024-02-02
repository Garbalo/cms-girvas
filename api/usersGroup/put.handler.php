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
use \core\PHPLibrary\SystemCore\Locale as Locale;

if (is_null($system_core->urlp->get_path(2))) {
  $user_group_name = isset($_PUT['user_group_name']) ? $_PUT['user_group_name'] : '';

  if (!empty($user_group_name)) {
    if (!UserGroup::exists_by_name($system_core, $user_group_name)) {
      if (preg_match('/[a-z\_]+/i', $user_group_name)) {
        $user_group_permissions = 0x0000000000000000;

        $user_group_permissions_array = isset($_PUT['user_group_permissions']) ? $_PUT['user_group_permissions'] : [];
        error_log(print_r($user_group_permissions_array, true));
        if (!empty($user_group_permissions_array)) {
          foreach ($user_group_permissions_array as $user_group_permission) {
            switch ($user_group_permission) {
              case 'admin_panel_auth': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_PANEL_AUTH; break;
              case 'admin_users_management': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_USERS_MANAGEMENT; break;
              case 'admin_users_groups_management': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_USERS_GROUPS_MANAGEMENT; break;
              case 'admin_templates_management': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_TEMPLATES_MANAGEMENT; break;
              case 'admin_settings_management': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_SETTINGS_MANAGEMENT; break;
              case 'admin_viewing_logs': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_ADMIN_VIEWING_LOGS; break;
              case 'moder_entries_comments_management': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT; break;
              case 'moder_users_warns': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_MODER_USERS_WARNS; break;
              case 'editor_entries_edit': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_EDITOR_ENTRIES_EDIT; break;
              case 'editor_entries_categories_edit': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT; break;
              case 'editor_pages_static_edit': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_EDITOR_PAGES_STATIC_EDIT; break;
              case 'base_entry_comment_rate': $user_group_permissions = $user_group_permissions | UserGroup::PERMISSION_BASE_ENTRY_COMMENT_RATE; break;
            }
          }
        }

        $texts = [];

        $cms_locales_names = $system_core->get_array_locales_names();
        if (count($cms_locales_names) > 0) {
          foreach ($cms_locales_names as $index => $cms_locale_name) {
            $cms_locale = new Locale($system_core, $cms_locale_name);
    
            $user_group_title_input_name = sprintf('user_group_title_%s', $cms_locale->get_iso_639_2());
    
            if (array_key_exists($user_group_title_input_name, $_PUT)) {
              if (!array_key_exists($cms_locale->get_name(), $texts)) $texts[$cms_locale->get_name()] = [];
    
              if (array_key_exists($user_group_title_input_name, $_PUT)) $texts[$cms_locale->get_name()]['title'] = $_PUT[$user_group_title_input_name];
            }
          }
        }

        $user_group = UserGroup::create($system_core, $user_group_name, $texts, $user_group_permissions);
        if (!is_null($user_group)) {
          $handler_output_data['usersGroup'] = [];
          $handler_output_data['usersGroup']['id'] = $user_group->get_id();

          $handler_message = 'Группа пользователей успешно создана.';
          $handler_status_code = 1;
        } else {
          $handler_message = 'Произошла внутренняя ошибка. Группа пользователей не была создана.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Данные группы пользователей не были сохранены, поскольку наименование имеет неверный формат.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Данные группы пользователей не были сохранены, поскольку указанное наименование уже используется.';
      $handler_status_code = 0;
    }
  } else {
    $handler_message = 'Данные группы пользователей не были сохранены, поскольку наименование не может быть пустым.';
    $handler_status_code = 0;
  }
} else {
  $handler_message = (!isset($handler_message)) ? 'Доступ запрещен. Ошибка авторизации.' : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>