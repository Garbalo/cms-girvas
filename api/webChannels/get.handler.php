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

use \core\PHPLibrary\WebChannel as WebChannel;

if ($system_core->urlp->get_path(2) == 'types') {
  $handler_output_data['webChannelsTypes'] = [
    ['id' => 1, 'name' => 'rss1-0', 'title' => 'RSS 1.0'],
    ['id' => 2, 'name' => 'rss2-0', 'title' => 'RSS 2.0'],
    ['id' => 3, 'name' => 'atom', 'title' => 'Atom']
  ];
}

?>