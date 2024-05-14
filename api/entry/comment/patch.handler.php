<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

 if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

use \core\PHPLibrary\EntryComment as EntryComment;

if ($system_core->client->is_logged(1) || $system_core->client->is_logged(2)) {
  $client_user = $system_core->client->get_user(1);
  $client_user->init_data(['metadata']);
  $client_user_group = $client_user->get_group();
  $client_user_group->init_data(['permissions']);


  if (isset($_PATCH['comment_id'])) {
    $comment_id = (is_numeric($_PATCH['comment_id'])) ? (int)$_PATCH['comment_id'] : 0;

    if (EntryComment::exists_by_id($system_core, $comment_id)) {
      $comment = new EntryComment($system_core, $comment_id);
      $comment->init_data(['metadata', 'author_id']);
      
      if ($client_user_group->permission_check($client_user_group::PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT) || $comment->get_author_id() == $client_user->get_id()) {
        $comment_data = [];
        if (isset($_PATCH['comment_content'])) $comment_data['content'] = $_PATCH['comment_content'];
        
        if (!array_key_exists('metadata', $comment_data)) $comment_data['metadata'] = [];
        
        if (isset($_PATCH['comment_is_hidden'])) {
          $comment_data['metadata']['is_hidden'] = ($_PATCH['comment_is_hidden'] == 'on') ? true : false;
        }

        if (isset($_PATCH['comment_hidden_reason'])) {
          $comment_data['metadata']['hidden_reason'] = $_PATCH['comment_hidden_reason'];
        }

        if (isset($_PATCH['comment_parent_id'])) {
          $comment_data['metadata']['parentID'] = $_PATCH['comment_parent_id'];
        }

        if (isset($_PATCH['comment_rating_vote'])) {
          $comment_rating_voters = $comment->get_rating_voters();
          
          $allow_voting = false;
          if (isset($comment_rating_voters[(string)$client_user->get_id()])) {
            if ($comment_rating_voters[(string)$client_user->get_id()] != $_PATCH['comment_rating_vote']) {
              $allow_voting = true;
            }
          } else {
            $allow_voting = true;
          }

          if ($allow_voting) $comment_data['metadata']['rating_vote'] = ['voter_id' => $client_user->get_id(), 'vote' => $_PATCH['comment_rating_vote']];
        }

        $comment_is_updated = (!empty($comment_data)) ? $comment->update($comment_data) : false;

        if ($comment_is_updated) {
          $comment = new EntryComment($system_core, $comment_id);

          $comment_init_data = ['metadata'];
          
          if (isset($_PATCH['comment_content'])) array_push($comment_init_data, 'content');

          $comment->init_data($comment_init_data);
          $handler_output_data['comment']['id'] = $comment->get_id();
          $handler_output_data['comment']['rating'] = $comment->get_rating();
          if (isset($_PATCH['comment_content'])) $handler_output_data['comment']['content'] = $comment->get_content();

          $handler_message = $system_core->locale->get_single_value_by_key('API_PATCH_DATA_SUCCESS');
          $handler_status_code = 1;
        } else {
          $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN'));
          $handler_status_code = 0;
        }
      } else {
        $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_DONT_HAVE_PERMISSIONS'));
        $handler_status_code = 0;
      }
    } else {
      $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ENTRY_COMMENT_ERROR_NOT_FOUND'));
      $handler_status_code = 0;
    }
  }
} else {
  $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION')) : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>