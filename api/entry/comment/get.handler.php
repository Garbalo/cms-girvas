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

use \core\PHPLibrary\EntryComment as EntryComment;
use \core\PHPLibrary\EntryComments as EntryComments;

if ($system_core->client->is_logged(1)) {
  $comment_id = (is_numeric($system_core->urlp->get_path(3))) ? (int)$system_core->urlp->get_path(3) : 0;

  if (EntryComment::exists_by_id($system_core, $comment_id)) {
    $comment = new EntryComment($system_core, $comment_id);
    $comment->init_data(['entry_id', 'author_id', 'content', 'metadata', 'created_unix_timestamp', 'updated_unix_timestamp']);
    
    $handler_output_data['comment'] = [];
    $handler_output_data['comment']['id'] = $comment->get_id();
    $handler_output_data['comment']['content'] = $comment->get_content();
    $handler_output_data['comment']['authorID'] = $comment->get_author_id();
    $handler_output_data['comment']['parentID'] = $comment->get_parent_id();
    $handler_output_data['comment']['answersCount'] = $comment->get_answers_count();
    $handler_output_data['comment']['isHidden'] = $comment->is_hidden();
    $handler_output_data['comment']['hiddenReason'] = $comment->get_hidden_reason();
    $handler_output_data['comment']['rating'] = $comment->get_rating();
    $handler_output_data['comment']['ratingVoters'] = $comment->get_rating_voters();
    $handler_output_data['comment']['createdUnixTimestamp'] = $comment->get_created_unix_timestamp();
    $handler_output_data['comment']['updatedUnixTimestamp'] = $comment->get_updated_unix_timestamp();

    $handler_message = 'Данные по комментарию успешно получены.';
    $handler_status_code = 1;
  } else {
    $handler_message = 'Данные по комментарию не были получены, так как его не существует.';
    $handler_status_code = 0;
  }
}

?>