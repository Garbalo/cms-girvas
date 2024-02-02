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

use \core\PHPLibrary\EntryComment as EntryComment;

if ($system_core->client->is_logged(2)) {
  if (isset($_DELETE['comment_id'])) {
    $comment_id = (is_numeric($_DELETE['comment_id'])) ? (int)$_DELETE['comment_id'] : 0;

    if (EntryComment::exists_by_id($system_core, $comment_id)) {
      $comment = new EntryComment($system_core, $comment_id);
      $comment_is_deleted = $comment->delete();

      if ($comment_is_deleted) {
        $handler_message = 'Комментарий успешно удален.';
        $handler_status_code = 1;
      } else {
        $handler_message = 'Комментарий не был удален, поскольку произошел неизвестный сбой.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Комментарий не удален, поскольку его не существует.';
      $handler_status_code = 0;
    }

    $handler_output_data['modalClose'] = true;
    $handler_output_data['reload'] = true;
  }
}

?>