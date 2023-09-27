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

if ($system_core->client->is_logged(2)) {
  if ($system_core->urlp->get_path(2) == 'category') {
    $entries_category_id = (is_numeric($_DELETE['entries_category_id'])) ? (int)$_DELETE['entries_category_id'] : 0;

    if (EntryCategory::exists_by_id($system_core, $entries_category_id)) {
      $entries_category = new EntryCategory($system_core, $entries_category_id);
      $entries_category_is_deleted = $entries_category->delete();

      if ($entries_category_is_deleted) {
        $handler_message = 'Категория записей успешно удалена.';
        $handler_status_code = 1;
      } else {
        $handler_message = 'Категория записей не была удалена, поскольку произошел неизвестный сбой.';
        $handler_status_code = 0;
      }
    }
  } else {
    if (isset($_DELETE['entry_id'])) {
      $entry_id = (is_numeric($_DELETE['entry_id'])) ? (int)$_DELETE['entry_id'] : 0;

      if (Entry::exists_by_id($system_core, $entry_id)) {
        $entry = new Entry($system_core, $entry_id);
        $entry_is_deleted = $entry->delete();

        if ($entry_is_deleted) {
          $handler_message = 'Запись успешно удалена.';
          $handler_status_code = 1;
        } else {
          $handler_message = 'Запись не была удалена, поскольку произошел неизвестный сбой.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Запись не удалена, поскольку ее не существует.';
        $handler_status_code = 0;
      }

      $handler_output_data['modalClose'] = true;
      $handler_output_data['reload'] = true;
    }
  }
}

?>