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
use \core\PHPLibrary\SystemCore\FileConverter as FileConverter;
use \core\PHPLibrary\SystemCore\Locale as Locale;

if ($system_core->client->is_logged(2)) {
  if ($system_core->urlp->get_path(2) == 'category') {
    if (isset($_PATCH['entries_category_id'])) {
      $entries_category_id = (is_numeric($_PATCH['entries_category_id'])) ? (int)$_PATCH['entries_category_id'] : 0;

      if (EntryCategory::exists_by_id($system_core, $entries_category_id)) {
        $entries_category = new EntryCategory($system_core, $entries_category_id);
        $entries_category_data = [];

        $cms_locales_names = $system_core->get_array_locales_names();
        if (count($cms_locales_names) > 0) {
          foreach ($cms_locales_names as $index => $cms_locale_name) {
            $cms_locale = new Locale($system_core, $cms_locale_name);

            $entries_category_title_input_name = sprintf('entries_category_title_%s', $cms_locale->get_iso_639_2());
            $entries_category_description_textarea_name = sprintf('entries_category_description_%s', $cms_locale->get_iso_639_2());

            if (array_key_exists($entries_category_title_input_name, $_PATCH) || array_key_exists($entries_category_description_textarea_name, $_PATCH)) {
              if (!array_key_exists('texts', $entries_category_data)) $entries_category_data['texts'] = [];
              if (!array_key_exists($cms_locale->get_name(), $entries_category_data['texts'])) $entries_category_data['texts'][$cms_locale->get_name()] = [];

              if (array_key_exists($entries_category_title_input_name, $_PATCH)) $entries_category_data['texts'][$cms_locale->get_name()]['title'] = $_PATCH[$entries_category_title_input_name];
              if (array_key_exists($entries_category_description_textarea_name, $_PATCH)) $entries_category_data['texts'][$cms_locale->get_name()]['description'] = $_PATCH[$entries_category_description_textarea_name];
            }
          }
        }

        if (isset($_PATCH['entries_category_name'])) $entries_category_data['name'] = $_PATCH['entries_category_name'];
        if (isset($_PATCH['entries_category_parent_id'])) $entries_category_data['parent_id'] = $_PATCH['entries_category_parent_id'];
        $entries_category_is_updated = $entries_category->update($entries_category_data);

        if ($entries_category_is_updated) {
          $handler_message = 'Данные успешно сохранены.';
          $handler_status_code = 1;
        } else {
          $handler_message = 'Данные не сохранены, поскольку произошел неизвестный сбой.';
          $handler_status_code = 0;
        }
      }
    }
  } else {
    if (isset($_PATCH['entry_id'])) {
      $entry_id = (is_numeric($_PATCH['entry_id'])) ? (int)$_PATCH['entry_id'] : 0;

      if (Entry::exists_by_id($system_core, $entry_id)) {
        $entry = new Entry($system_core, $entry_id);
        $entry_data = [];

        $cms_locales_names = $system_core->get_array_locales_names();
        if (count($cms_locales_names) > 0) {
          foreach ($cms_locales_names as $index => $cms_locale_name) {
            $cms_locale = new Locale($system_core, $cms_locale_name);

            $entry_title_input_name = sprintf('entry_title_%s', $cms_locale->get_iso_639_2());
            $entry_description_textarea_name = sprintf('entry_description_%s', $cms_locale->get_iso_639_2());
            $entry_content_textarea_name = sprintf('entry_content_%s', $cms_locale->get_iso_639_2());
            $entry_keywords_textarea_name = sprintf('entry_keywords_%s', $cms_locale->get_iso_639_2());

            if (!array_key_exists('metadata', $entry_data)) $entry_data['metadata'] = [];
            if (isset($_PATCH['entry_is_published'])) $entry_data['metadata']['is_published'] = $_PATCH['entry_is_published'];

            if (array_key_exists($entry_title_input_name, $_PATCH) || array_key_exists($entry_description_textarea_name, $_PATCH) || array_key_exists($entry_content_textarea_name, $_PATCH)) {
              if (!array_key_exists('texts', $entry_data)) $entry_data['texts'] = [];
              if (!array_key_exists($cms_locale->get_name(), $entry_data['texts'])) $entry_data['texts'][$cms_locale->get_name()] = [];

              if (array_key_exists($entry_title_input_name, $_PATCH)) $entry_data['texts'][$cms_locale->get_name()]['title'] = $_PATCH[$entry_title_input_name];
              if (array_key_exists($entry_description_textarea_name, $_PATCH)) $entry_data['texts'][$cms_locale->get_name()]['description'] = $_PATCH[$entry_description_textarea_name];
              if (array_key_exists($entry_content_textarea_name, $_PATCH)) $entry_data['texts'][$cms_locale->get_name()]['content'] = $_PATCH[$entry_content_textarea_name];
              if (array_key_exists($entry_keywords_textarea_name, $_PATCH)) $entry_data['texts'][$cms_locale->get_name()]['keywords'] = preg_split('/\h*[\,]+\h*/', $_PATCH[$entry_keywords_textarea_name], -1, PREG_SPLIT_NO_EMPTY);
            }
          }
        }

        if (isset($_PATCH['entry_name'])) $entry_data['name'] = $_PATCH['entry_name'];
        if (isset($_PATCH['entry_category_id'])) $entry_data['category_id'] = $_PATCH['entry_category_id'];
        if (isset($_PATCH['entry_preview'])) {
          $file_uploaded_folder_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
          $file_converter = new FileConverter($system_core);
          $file_converted = $file_converter->convert($_PATCH['entry_preview'], $file_uploaded_folder_path, \core\PHPLibrary\SystemCore\FileConverter\EnumFileFormat::WEBP, true);
          
          if (is_array($file_converted)) {
            if (!array_key_exists('metadata', $entry_data)) $entry_data['metadata'] = [];
            $entry_data['metadata']['preview_url'] = sprintf('/uploads/media/%s', $file_converted['file_name']);
          }
        }

        $entry_is_updated = $entry->update($entry_data);

        if ($entry_is_updated) {
          $entry->init_data(['texts']);
    
          $sc_report = \core\PHPLibrary\SystemCore\Report::create($system_core, \core\PHPLibrary\SystemCore\Report::REPORT_TYPE_ID_AP_ENTRY_EDITED, [
            'clientIP' => $system_core->client->get_ip_address(),
            'entryTitle' => $entry->get_title(),
            'date' => date('Y/m/d H:i:s', time())
          ]);

          $handler_message = 'Запись успешно сохранена.';
          $handler_status_code = 1;
        } else {
          $handler_message = 'Запись не была сохранена, поскольку произошел неизвестный сбой.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Запись не обновлена, поскольку ее не существует.';
        $handler_status_code = 0;
      }
    }
  }
}

?>