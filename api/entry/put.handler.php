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

use \core\PHPLibrary\Entry as Entry;
use \core\PHPLibrary\EntryCategory as EntryCategory;
use \core\PHPLibrary\SystemCore\Locale as Locale;

if ($system_core->client->is_logged(2)) {
  $client_user = $system_core->client->get_user(2);
  $client_user->init_data(['metadata']);
  $client_user_group = $client_user->get_group();
  $client_user_group->init_data(['permissions']);

  if ($system_core->urlp->get_path(2) == 'category') {
    if ($client_user_group->permission_check($client_user_group::PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT)) {
      $entries_category_name = isset($_PUT['entries_category_name']) ? $_PUT['entries_category_name'] : '';
      $entries_category_parent_id = isset($_PUT['entries_category_parent_id']) ? (int)$_PUT['entries_category_parent_id'] : 0;
      $texts = [];
      $metadata = [];
      
      $cms_locales_names = $system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new Locale($system_core, $cms_locale_name);

          $entries_category_title_input_name = sprintf('entries_category_title_%s', $cms_locale->get_iso_639_2());
          $entries_category_description_textarea_name = sprintf('entries_category_description_%s', $cms_locale->get_iso_639_2());

          if (array_key_exists($entries_category_title_input_name, $_PUT) || array_key_exists($entries_category_description_textarea_name, $_PUT)) {
            if (!array_key_exists($cms_locale->get_name(), $texts)) $texts[$cms_locale->get_name()] = [];

            if (array_key_exists($entries_category_title_input_name, $_PUT)) $texts[$cms_locale->get_name()]['title'] = htmlspecialchars($_PUT[$entries_category_title_input_name]);
            if (array_key_exists($entries_category_description_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['description'] = htmlspecialchars($_PUT[$entries_category_description_textarea_name]);
          }
        }
      }

      $entries_category = EntryCategory::create($system_core, $entries_category_name, $entries_category_parent_id, $texts, $metadata);
      if (!is_null($entries_category)) {
        $entries_category->init_data(['metadata']);

        if (isset($_PUT['entries_category_show_index'])) {
          if (!isset($entries_category_data['metadata'])) $entries_category_data['metadata'] = [];
          $entries_category_data['metadata']['isShowedOnIndexPage'] = 1;
        } else {
          if (!isset($entries_category_data['metadata'])) $entries_category_data['metadata'] = [];
          $entries_category_data['metadata']['isShowedOnIndexPage'] = 0;
        }

        $handler_output_data['entriesCategory'] = [];
        $handler_output_data['entriesCategory']['id'] = $entries_category->get_id();

        $handler_message = $system_core->locale->get_single_value_by_key('API_PUT_DATA_SUCCESS');
        $handler_status_code = 1;
      } else {
        $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN'));
        $handler_status_code = 0;
      }
    } else {
      $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_DONT_HAVE_PERMISSIONS'));
      $handler_status_code = 0;
    }
  }

  if ($system_core->urlp->get_path(2) == null) {
    if ($client_user_group->permission_check($client_user_group::PERMISSION_EDITOR_ENTRIES_EDIT)) {
      $entry_name = isset($_PUT['entry_name']) ? $_PUT['entry_name'] : '';
      $entry_category_id = isset($_PUT['entry_category_id']) ? $_PUT['entry_category_id'] : 1;
      $texts = [];

      $cms_locales_names = $system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new Locale($system_core, $cms_locale_name);

          $entry_title_input_name = sprintf('entry_title_%s', $cms_locale->get_iso_639_2());
          $entry_description_textarea_name = sprintf('entry_description_%s', $cms_locale->get_iso_639_2());
          $entry_content_textarea_name = sprintf('entry_content_%s', $cms_locale->get_iso_639_2());
          $entry_keywords_textarea_name = sprintf('entry_keywords_%s', $cms_locale->get_iso_639_2());

          if (array_key_exists($entry_title_input_name, $_PUT) || array_key_exists($entry_description_textarea_name, $_PUT) || array_key_exists($entry_content_textarea_name, $_PUT)) {
            if (!array_key_exists($cms_locale->get_name(), $texts)) $texts[$cms_locale->get_name()] = [];

            if (array_key_exists($entry_title_input_name, $_PUT)) $texts[$cms_locale->get_name()]['title'] = htmlspecialchars($_PUT[$entry_title_input_name]);
            if (array_key_exists($entry_description_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['description'] = htmlspecialchars($_PUT[$entry_description_textarea_name]);
            if (array_key_exists($entry_content_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['content'] = htmlspecialchars($_PUT[$entry_content_textarea_name]);
            if (array_key_exists($entry_keywords_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['keywords'] = preg_split('/\h*[\,]+\h*/', htmlspecialchars($_PUT[$entry_keywords_textarea_name]), -1, PREG_SPLIT_NO_EMPTY);
          }
        }
      }

      $client_session = $system_core->client->get_session(2, ['user_id']);
      $entry = Entry::create($system_core, $entry_name, $client_session->get_user_id(), 1, $texts);
      if (!is_null($entry)) {
        $entry->init_data(['texts']);

        $sc_report = \core\PHPLibrary\SystemCore\Report::create($system_core, \core\PHPLibrary\SystemCore\Report::REPORT_TYPE_ID_AP_ENTRY_CREATED, [
          'clientIP' => $system_core->client->get_ip_address(),
          'entryTitle' => $entry->get_title(),
          'date' => date('Y/m/d H:i:s', time())
        ]);
        
        $handler_message = $system_core->locale->get_single_value_by_key('API_PUT_DATA_SUCCESS');
        $handler_status_code = 1;

        $handler_output_data['entry'] = [];
        $handler_output_data['entry']['id'] = $entry->get_id();

        $handler_output_data['href'] = sprintf('/admin/entry/%d', $entry->get_id());
      } else {
        $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN'));
        $handler_status_code = 0;
      }
    } else {
      $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_DONT_HAVE_PERMISSIONS'));
      $handler_status_code = 0;
    }
  }
} else {
  $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION')) : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>