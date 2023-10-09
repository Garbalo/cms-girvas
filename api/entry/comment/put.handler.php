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
use \core\PHPLibrary\EntryComment as EntryComment;

if ($system_core->client->is_logged(1)) {
  $comment_entry_id = isset($_PUT['comment_entry_id']) ? (int)$_PUT['comment_entry_id'] : 0;
  $comment_content = isset($_PUT['comment_content']) ? $_PUT['comment_content'] : '';

  if (!empty($_PUT['comment_content'])) {
    if (strlen($_PUT['comment_content']) >= 16) {
      if (Entry::exists_by_id($system_core, $comment_entry_id)) {
        $client_session = $system_core->client->get_session(1, ['user_id']);
        $comment = EntryComment::create($system_core, $comment_entry_id, $client_session->get_user_id(), $comment_content);
        
        if (!is_null($comment)) {
          $handler_message = 'Комментарий успешно создан.';
          $handler_status_code = 1;

          $handler_output_data['comment'] = [];
          $handler_output_data['comment']['id'] = $comment->get_id();

          $handler_output_data['reload'] = true;
        } else {
          $handler_message = 'Произошла внутренняя ошибка. Комментарий не был создан.';
          $handler_status_code = 0;
        }
      } else {
        $handler_message = 'Невозможно отправить комментарий к несуществующей записи.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Комментарий должен содержать не менее 16 символов.';
      $handler_status_code = 0;
    }
  } else {
    $handler_message = 'Невозможно отправить пустой комментарий.';
    $handler_status_code = 0;
  }
}

?>