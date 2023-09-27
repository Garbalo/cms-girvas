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
use \core\PHPLibrary\EntriesCategories as EntriesCategories;
use \core\PHPLibrary\EntryCategory as EntryCategory;

if ($system_core->client->is_logged(2)) {
  if ($system_core->urlp->get_path(2) == 'category') {
    $entries_category_id = (is_numeric($system_core->urlp->get_path(3))) ? (int)$system_core->urlp->get_path(3) : 0;
    $entries_category = (EntryCategory::exists_by_id($system_core, $entries_category_id)) ? new EntryCategory($system_core, $entries_category_id) : null;

    if (!is_null($entries_category)) {
      $entries_category->init_data(['id', 'texts', 'metadata', 'name', 'parent_id', 'created_unix_timestamp', 'updated_unix_timestamp']);
      $entries_category_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $system_core->configurator->get_database_entry_value('base_locale');

      $handler_output_data['entryCategory'] = [];
      $handler_output_data['entryCategory']['id'] = $entries_category->get_id();
      $handler_output_data['entryCategory']['name'] = $entries_category->get_name();
      $handler_output_data['entryCategory']['title'] = $entries_category->get_title($entries_category_locale);
      $handler_output_data['entryCategory']['description'] = $entries_category->get_description($entries_category_locale);
      $handler_output_data['entryCategory']['parentID'] = $entries_category->get_parent_id();
      $handler_output_data['entryCategory']['createdUnixTimestamp'] = $entries_category->get_created_unix_timestamp();
      $handler_output_data['entryCategory']['updatedUnixTimestamp'] = $entries_category->get_updated_unix_timestamp();

      $handler_message = 'Данные по категории записей успешно получены.';
      $handler_status_code = 1;
    } else {
      $handler_message = 'Данные по категории записей не были получены, так как ее не существует.';
      $handler_status_code = 0;
    }
  } else if ($system_core->urlp->get_path(2) == 'categories') {
    $entries_categories = (new EntriesCategories($system_core))->get_all();
    $entries_categories_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $system_core->configurator->get_database_entry_value('base_locale');

    $handler_output_data['entriesCategories'] = [];
    if (count($entries_categories) > 0) {
      foreach ($entries_categories as $entries_category) {
        $entries_category->init_data(['id', 'texts', 'metadata', 'name', 'parent_id', 'created_unix_timestamp', 'updated_unix_timestamp']);
        array_push($handler_output_data['entriesCategories'], [
          'id' => $entries_category->get_id(),
          'name' => $entries_category->get_name(),
          'title' => $entries_category->get_title($entries_categories_locale),
          'description' => $entries_category->get_description($entries_categories_locale),
          'parentID' => $entries_category->get_parent_id(),
          'createdUnixTimestamp' => $entries_category->get_created_unix_timestamp(),
          'updatedUnixTimestamp' => $entries_category->get_updated_unix_timestamp()
        ]);
      }

      $handler_message = 'Данные по категориям записей успешно получены.';
      $handler_status_code = 1;
    } else {
      $handler_message = 'Данные по категориям записей не были получены, так как их не существует.';
      $handler_status_code = 0;
    }
  } else {
    $entry_id = (is_numeric($system_core->urlp->get_path(2))) ? (int)$system_core->urlp->get_path(2) : 0;

    if (Entry::exists_by_id($system_core, $entry_id)) {
      $entry = new Entry($system_core, $entry_id);
      $entry->init_data(['name', 'author_id', 'category_id', 'texts', 'metadata', 'created_unix_timestamp', 'updated_unix_timestamp']);
      $entry_locale = (!is_null($system_core->urlp->get_param('locale'))) ? $system_core->urlp->get_param('locale') : $system_core->configurator->get_database_entry_value('base_locale');

      $handler_output_data['entry'] = [];
      $handler_output_data['entry']['id'] = $entry->get_id();
      $handler_output_data['entry']['name'] = $entry->get_name();
      $handler_output_data['entry']['title'] = $entry->get_title($entry_locale);
      $handler_output_data['entry']['description'] = $entry->get_description($entry_locale);
      $handler_output_data['entry']['content'] = $entry->get_content($entry_locale);
      $handler_output_data['entry']['keywords'] = $entry->get_keywords($entry_locale);
      $handler_output_data['entry']['authorID'] = $entry->get_author_id();
      $handler_output_data['entry']['categoryID'] = $entry->get_category_id();
      $handler_output_data['entry']['previewURL'] = $entry->get_preview_url();
      $handler_output_data['entry']['isPublished'] = $entry->is_published();
      $handler_output_data['entry']['createdUnixTimestamp'] = $entry->get_created_unix_timestamp();
      $handler_output_data['entry']['updatedUnixTimestamp'] = $entry->get_updated_unix_timestamp();

      $handler_message = 'Данные по записи успешно получены.';
      $handler_status_code = 1;
    } else {
      $handler_message = 'Данные по записи не были получены, так как ее не существует.';
      $handler_status_code = 0;
    }
  }
}

?>