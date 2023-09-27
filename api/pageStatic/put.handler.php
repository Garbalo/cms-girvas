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
use \core\PHPLibrary\SystemCore\Locale as Locale;

if ($system_core->client->is_logged(2)) {
  $page_static_name = isset($_PUT['page_static_name']) ? $_PUT['page_static_name'] : '';
  $texts = [];

  $cms_locales_names = $system_core->get_array_locales_names();
  if (count($cms_locales_names) > 0) {
    foreach ($cms_locales_names as $index => $cms_locale_name) {
      $cms_locale = new Locale($system_core, $cms_locale_name);

      $title_input_name = sprintf('page_static_title_%s', $cms_locale->get_iso_639_2());
      $description_textarea_name = sprintf('page_static_description_%s', $cms_locale->get_iso_639_2());
      $content_textarea_name = sprintf('page_static_content_%s', $cms_locale->get_iso_639_2());
      $keywords_textarea_name = sprintf('page_static_keywords_%s', $cms_locale->get_iso_639_2());

      if (array_key_exists($title_input_name, $_PUT) || array_key_exists($description_textarea_name, $_PUT) || array_key_exists($content_textarea_name, $_PUT)) {
        if (!array_key_exists($cms_locale->get_name(), $texts)) $texts[$cms_locale->get_name()] = [];

        if (array_key_exists($title_input_name, $_PUT)) $texts[$cms_locale->get_name()]['title'] = $_PUT[$title_input_name];
        if (array_key_exists($description_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['description'] = $_PUT[$description_textarea_name];
        if (array_key_exists($content_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['content'] = $_PUT[$content_textarea_name];
        if (array_key_exists($keywords_textarea_name, $_PUT)) $texts[$cms_locale->get_name()]['keywords'] = preg_split('/\h*[\,]+\h*/', $_PATCH[$keywords_textarea_name], -1, PREG_SPLIT_NO_EMPTY);
      }
    }
  }

  $client_session = $system_core->client->get_session(2, ['user_id']);
  $page_static = PageStatic::create($system_core, $page_static_name, $client_session->get_user_id(), 1, $texts);
  if (!is_null($page_static)) {
    $handler_message = 'Статическая страница успешно создана.';
    $handler_status_code = 1;

    $handler_output_data['pageStatic'] = [];
    $handler_output_data['pageStatic']['id'] = $page_static->get_id();

    $handler_output_data['href'] = sprintf('/admin/pageStatic/%d', $page_static->get_id());
  } else {
    $handler_message = 'Произошла внутренняя ошибка. Статическая страница не была создана.';
    $handler_status_code = 0;
  }
}

?>