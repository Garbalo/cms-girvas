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

use \core\PHPLibrary\PageStatic as PageStatic;

if ($system_core->client->is_logged(2)) {
  if (isset($_DELETE['page_static_id'])) {
    $page_static_id = (is_numeric($_DELETE['page_static_id'])) ? (int)$_DELETE['page_static_id'] : 0;

    if (PageStatic::exists_by_id($system_core, $page_static_id)) {
      $page_static = new PageStatic($system_core, $page_static_id);
      $page_static_is_deleted = $page_static->delete();

      if ($page_static_is_deleted) {
        $handler_message = 'Статическая страница успешно удалена.';
        $handler_status_code = 1;
      } else {
        $handler_message = 'Статическая страница не была удалена, поскольку произошел неизвестный сбой.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Статическая страница не удалена, поскольку ее не существует.';
      $handler_status_code = 0;
    }

    $handler_output_data['modalClose'] = true;
    $handler_output_data['reload'] = true;
  }
}

?>