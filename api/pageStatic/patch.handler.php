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
use \core\PHPLibrary\SystemCore\FileConverter as FileConverter;
use \core\PHPLibrary\SystemCore\Locale as Locale;

if ($system_core->client->is_logged(2)) {
  if (isset($_PATCH['page_static_id'])) {
    $page_static_id = (is_numeric($_PATCH['page_static_id'])) ? (int)$_PATCH['page_static_id'] : 0;

    if (PageStatic::exists_by_id($system_core, $page_static_id)) {
      $page_static = new PageStatic($system_core, $page_static_id);
      $page_static_data = [];

      $cms_locales_names = $system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new Locale($system_core, $cms_locale_name);

          $title_input_name = sprintf('page_static_title_%s', $cms_locale->get_iso_639_2());
          $description_textarea_name = sprintf('page_static_description_%s', $cms_locale->get_iso_639_2());
          $content_textarea_name = sprintf('page_static_content_%s', $cms_locale->get_iso_639_2());
          $keywords_textarea_name = sprintf('page_static_keywords_%s', $cms_locale->get_iso_639_2());

          if (!array_key_exists('metadata', $page_static_data)) $page_static_data['metadata'] = [];
          if (isset($_PATCH['page_static_is_published'])) $page_static_data['metadata']['is_published'] = $_PATCH['page_static_is_published'];

          if (array_key_exists($title_input_name, $_PATCH) || array_key_exists($description_textarea_name, $_PATCH) || array_key_exists($content_textarea_name, $_PATCH)) {
            if (!array_key_exists('texts', $page_static_data)) $page_static_data['texts'] = [];
            if (!array_key_exists($cms_locale->get_name(), $page_static_data['texts'])) $page_static_data['texts'][$cms_locale->get_name()] = [];

            if (array_key_exists($title_input_name, $_PATCH)) $page_static_data['texts'][$cms_locale->get_name()]['title'] = $_PATCH[$title_input_name];
            if (array_key_exists($description_textarea_name, $_PATCH)) $page_static_data['texts'][$cms_locale->get_name()]['description'] = $_PATCH[$description_textarea_name];
            if (array_key_exists($content_textarea_name, $_PATCH)) $page_static_data['texts'][$cms_locale->get_name()]['content'] = $_PATCH[$content_textarea_name];
            if (array_key_exists($keywords_textarea_name, $_PATCH)) $page_static_data['texts'][$cms_locale->get_name()]['keywords'] = preg_split('/\h*[\,]+\h*/', $_PATCH[$keywords_textarea_name], -1, PREG_SPLIT_NO_EMPTY);
          }
        }
      }

      if (isset($_PATCH['page_static_name'])) $page_static_data['name'] = $_PATCH['page_static_name'];
      if (isset($_PATCH['page_static_preview'])) {
        $file_uploaded_folder_path = sprintf('%s/uploads/media', CMS_ROOT_DIRECTORY);
        $file_converter = new FileConverter($system_core);
        $file_converted = $file_converter->convert($_PATCH['page_static_preview'], $file_uploaded_folder_path, \core\PHPLibrary\SystemCore\FileConverter\EnumFileFormat::WEBP, true);
        
        if (is_array($file_converted)) {
          if (!array_key_exists('metadata', $page_static_data)) $page_static_data['metadata'] = [];
          $page_static_data['metadata']['preview_url'] = sprintf('/uploads/media/%s', $file_converted['file_name']);
        }
      }

      $page_static_is_updated = $page_static->update($page_static_data);

      if ($page_static_is_updated) {
        $handler_message = 'Статическая страница успешно сохранена.';
        $handler_status_code = 1;
      } else {
        $handler_message = 'Статическая страница не была сохранена, поскольку произошел неизвестный сбой.';
        $handler_status_code = 0;
      }
    } else {
      $handler_message = 'Статическая страница не обновлена, поскольку ее не существует.';
      $handler_status_code = 0;
    }
  }
}

?>