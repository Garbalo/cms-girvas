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

if (!file_exists(sprintf('%s/INSTALLED', CMS_ROOT_DIRECTORY))) {
  if ($system_core->urlp->get_path(2) == 'set-locales-and-timezone') {
    $dom_document = new \DOMDocument();
    $tip_block = $dom_document->createElement('div');
    
    $database_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator);

    if ($system_core->configurator->exists_database_entry_value('base_locale')) {
      $system_core->configurator->update_database_entry_value('base_locale', $_POST['setting_base_locale']);
    } else {
      $system_core->configurator->insert_database_entry_value('base_locale', $_POST['setting_base_locale']);
    }

    if ($system_core->configurator->exists_database_entry_value('base_admin_locale')) {
      $system_core->configurator->update_database_entry_value('base_admin_locale', $_POST['setting_admin_locale']);
    } else {
      $system_core->configurator->insert_database_entry_value('base_admin_locale', $_POST['setting_admin_locale']);
    }

    if ($system_core->configurator->exists_database_entry_value('base_timezone')) {
      $system_core->configurator->update_database_entry_value('base_timezone', $_POST['setting_base_timezone']);
    } else {
      $system_core->configurator->insert_database_entry_value('base_timezone', $_POST['setting_base_timezone']);
    }

    $tip_block->setAttribute('class', 'tip tip_green');
    $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_POST_DATA_SUCCESS');

    $dom_document->appendChild($tip_block);

    $handler_output_data['html'] = $dom_document->saveHTML();
    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }

  if ($system_core->urlp->get_path(2) == 'set-metadata') {
    $dom_document = new \DOMDocument();
    $tip_block = $dom_document->createElement('div');
    
    $database_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator);

    if ($system_core->configurator->exists_database_entry_value('base_title')) {
      $system_core->configurator->update_database_entry_value('base_title', $_POST['site_title']);
    } else {
      $system_core->configurator->insert_database_entry_value('base_title', $_POST['site_title']);
    }

    if ($system_core->configurator->exists_database_entry_value('seo_site_description')) {
      $system_core->configurator->update_database_entry_value('seo_site_description', $_POST['site_description']);
    } else {
      $system_core->configurator->insert_database_entry_value('seo_site_description', $_POST['site_description']);
    }

    if ($system_core->configurator->exists_database_entry_value('seo_site_keywords')) {
      $system_core->configurator->update_database_entry_value('seo_site_keywords', json_encode(preg_split('/\s*\,\s*/', $_POST['site_keywords'])));
    } else {
      $system_core->configurator->insert_database_entry_value('seo_site_keywords', json_encode(preg_split('/\s*\,\s*/', $_POST['site_keywords'])));
    }

    $tip_block->setAttribute('class', 'tip tip_green');
    $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_POST_DATA_SUCCESS');

    $dom_document->appendChild($tip_block);

    $handler_output_data['html'] = $dom_document->saveHTML();
    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }

  if ($system_core->urlp->get_path(2) == 'create-admin') {
    $dom_document = new \DOMDocument();
    $tip_block = $dom_document->createElement('div');
    
    $database_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator);

    $admin_login = $_POST['admin_login'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];
    $admin_password_repeat = $_POST['admin_password_repeat'];

    if (preg_match('/^[a-z0-9\_]{4,}$/i', $admin_login)) {
      if (filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        if (preg_match('/^[a-z0-9\_\$\%\&\#\@\?]{8,}$/i', $admin_password)) {
          if ($admin_password == $admin_password_repeat) {
            if (!\core\PHPLibrary\User::exists_by_login($system_core, $admin_login)) {
              if (!\core\PHPLibrary\User::exists_by_email($system_core, $admin_email)) {
                $admin = \core\PHPLibrary\User::create($system_core, $admin_login, $admin_email, $admin_password);

                if (!is_null($admin)) {
                  $admin->update(['email_is_submitted' => true, 'metadata_json' => json_encode(['group_id' => 1])]);

                  $tip_block->setAttribute('class', 'tip tip_green');
                  $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_POST_DATA_SUCCESS');

                  $handler_message = $system_core->locale->get_single_value_by_key('API_POST_DATA_SUCCESS');
                  $handler_status_code = 1;
                } else {
                  $tip_block->setAttribute('class', 'tip tip_red');
                  $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_ERROR_UNKNOWN');
                }
              } else {
                $tip_block->setAttribute('class', 'tip tip_red');
                $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_EMAIL_ALREADY_EXISTS');
              }
            } else {
              $tip_block->setAttribute('class', 'tip tip_red');
              $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_LOGIN_ALREADY_EXISTS');
            }
          } else {
            $tip_block->setAttribute('class', 'tip tip_red');
            $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_INVALID_REPEAT_PASSWORD');
          }
        } else {
          $tip_block->setAttribute('class', 'tip tip_red');
          $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_INVALID_PASSWORD');
        }
      } else {
        $tip_block->setAttribute('class', 'tip tip_red');
        $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_INVALID_EMAIL');
      }
    } else {
      $tip_block->setAttribute('class', 'tip tip_red');
      $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_ADMIN_ACCOUNT_CREATED_ERROR_INVALID_LOGIN');
    }

    $dom_document->appendChild($tip_block);
    $handler_output_data['html'] = $dom_document->saveHTML();
  }

  if ($system_core->urlp->get_path(2) == 'generate-secret-key') {
    $dom_document = new \DOMDocument();
    $tip_block = $dom_document->createElement('div');

    $chars = strtoupper('qwertyuiopasdfghjklzxcvbnm123456789');
    $codes_open = [];

    for ($code_index = 0; $code_index < 4; $code_index++) {
      $code_chars_array = [];

      for ($char_index = 0; $char_index < 4; $char_index++) {
        array_push($code_chars_array, $chars[rand(0, strlen($chars) - 1)]);
      }

      switch ($code_index) {
        case 0: $code_char = 'a'; break;
        case 1: $code_char = 'b'; break;
        case 2: $code_char = 'c'; break;
        case 3: $code_char = 'd'; break;
      }

      $complete_code = implode($code_chars_array);
      array_push($codes_open, $complete_code);

      if ($system_core->configurator->exists_database_entry_value(sprintf('security_admin_code_%s', $code_char))) {
        $system_core->configurator->update_database_entry_value(sprintf('security_admin_code_%s', $code_char), password_hash($complete_code, PASSWORD_ARGON2ID));
      } else {
        $system_core->configurator->insert_database_entry_value(sprintf('security_admin_code_%s', $code_char), password_hash($complete_code, PASSWORD_ARGON2ID));
      }

      unset($code_chars_array);
    }

    $tip_block->setAttribute('class', 'tip tip_green');
    $tip_block->nodeValue = sprintf('%s: ' . implode('-', $codes_open), $system_core->locale->get_single_value_by_key('API_INSTALLATION_SECRET_KEY_LABEL'));

    $dom_document->appendChild($tip_block);
    $handler_output_data['html'] = $dom_document->saveHTML();
  }

  if ($system_core->urlp->get_path(2) == 'finish') {
    $installed_empty_file_path = sprintf('%s/INSTALLED', CMS_ROOT_DIRECTORY);
    if (!file_exists($installed_empty_file_path)) {
      $file = fopen($installed_empty_file_path, 'w');
      fclose($file);
    }
  }
}

?>