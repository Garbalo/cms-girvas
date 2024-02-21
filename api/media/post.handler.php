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

use \core\PHPLibrary\SystemCore\FileConverter as FileConverter;
use \core\PHPLibrary\SystemCore\FileConverter\EnumFileFormat as EnumFileFormat;

if ($system_core->client->is_logged(2)) {
  if (!empty($_FILES)) {
    $handler_output_data['debug_files'] = $_FILES;

    $uploaded_file_extention = pathinfo($_FILES['mediaFile']['name'], PATHINFO_EXTENSION);
    $file_extention_allowed = ['png', 'gif', 'jpg', 'jpeg', 'webp'];
    $uploaded_dir_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);

    if (!file_exists($uploaded_dir_path)) {
      mkdir($uploaded_dir_path, 0777);
    }

    if (file_exists($uploaded_dir_path)) {
      if (in_array($uploaded_file_extention, $file_extention_allowed)) {
        $file_uploaded_folder_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
           
        $file_converter = new FileConverter($system_core);
        $file_converted = $file_converter->convert($_FILES['mediaFile'], $file_uploaded_folder_path, EnumFileFormat::WEBP, true);
        
        if (is_array($file_converted)) {
          $handler_output_data['file'] = [];
          $handler_output_data['file']['url'] = sprintf('/uploads/media/%s', $file_converted['file_name']);
          $handler_output_data['file']['fullname'] = $file_converted['file_name'];

          $handler_message = (!isset($handler_message)) ? $system_core->locale->get_single_value_by_key('API_POST_FILES_SUCCESS') : $handler_message;
          $handler_status_code = (!isset($handler_status_code)) ? 1 : $handler_status_code;
        } else {
          $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN')) : $handler_message;
          $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
        }
      } else {
        $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_FILE_ERROR_INVALID_EXTENSION')) : $handler_message;
        $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
      }
    } else {
      $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_FILE_ERROR_DIRECTORY_NOT_FOUND')) : $handler_message;
      $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
    }
  } else {
    $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN')) : $handler_message;
    $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
  }
} else {
  http_response_code(401);
  $handler_message = (!isset($handler_message)) ? sprintf('API ERROR: %s', $system_core->locale->get_single_value_by_key('API_ERROR_AUTHORIZATION')) : $handler_message;
  $handler_status_code = (!isset($handler_status_code)) ? 0 : $handler_status_code;
}

?>