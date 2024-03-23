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

use \core\PHPLibrary\WebChannel as WebChannel;

if ($system_core->client->is_logged(2)) {
  $client_user = $system_core->client->get_user(2);
  $client_user->init_data(['metadata']);
  $client_user_group = $client_user->get_group();
  $client_user_group->init_data(['permissions']);

  if ($client_user_group->permission_check($client_user_group::PERMISSION_ADMIN_FEEDS_MANAGEMENT)) {
    $web_channel_id = (isset($_PATCH['web_channel_id'])) ? $_PATCH['web_channel_id'] : 0;
    $web_channel_id = (is_numeric($web_channel_id)) ? (int)$web_channel_id : 0;

    if (WebChannel::exists_by_id($system_core, $web_channel_id)) {
      $web_channel = new WebChannel($system_core, $web_channel_id);
      $web_channel_data = [];

      if (array_key_exists('web_channel_title_rus', $_PATCH) || array_key_exists('web_channel_description_rus', $_PATCH)) {
        if (!array_key_exists('texts', $web_channel_data)) $web_channel_data['texts'] = [];
        if (!array_key_exists('ru_RU', $web_channel_data['texts'])) $web_channel_data['texts']['ru_RU'] = [];

        if (array_key_exists('web_channel_title_rus', $_PATCH)) $web_channel_data['texts']['ru_RU']['title'] = $_PATCH['web_channel_title_rus'];
        if (array_key_exists('web_channel_description_rus', $_PATCH)) $web_channel_data['texts']['ru_RU']['description'] = $_PATCH['web_channel_description_rus'];
      }

      if (array_key_exists('web_channel_title_eng', $_PATCH) || array_key_exists('web_channel_description_eng', $_PATCH)) {
        if (!array_key_exists('texts', $web_channel_data)) $web_channel_data['texts'] = [];
        if (!array_key_exists('en_US', $web_channel_data['texts'])) $web_channel_data['texts']['en_US'] = [];

        if (array_key_exists('web_channel_title_eng', $_PATCH)) $web_channel_data['texts']['en_US']['title'] = $_PATCH['web_channel_title_eng'];
        if (array_key_exists('web_channel_description_eng', $_PATCH)) $web_channel_data['texts']['en_US']['description'] = $_PATCH['web_channel_description_eng'];
      }

      if (isset($_PATCH['web_channel_name'])) $web_channel_data['name'] = $_PATCH['web_channel_name'];
      if (isset($_PATCH['web_channel_type_id'])) $web_channel_data['type_id'] = $_PATCH['web_channel_type_id'];
      if (isset($_PATCH['web_channel_entries_category_id'])) $web_channel_data['entries_category_id'] = $_PATCH['web_channel_entries_category_id'];

      $web_channel_is_updated = $web_channel->update($web_channel_data);

      if ($web_channel_is_updated) {
        $handler_message = (!isset($handler_message)) ? $system_core->locale->get_single_value_by_key('API_PATCH_DATA_SUCCESS') : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
      } else {
        $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN')) : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_FEED_ERROR_NOT_FOUND')) : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_DONT_HAVE_PERMISSIONS'));
    $handler_status_code = 0;
  }
} else {
  $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION')) : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>