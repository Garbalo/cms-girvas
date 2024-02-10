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

define('API_HANDLERS_ABSOLUTE_PATH', sprintf('%s/api/webChannel', CMS_ROOT_DIRECTORY));

if (isset($system_core)) {
  // Определение абсолютного пути до обработчика текущего API
  switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': $handler_path = sprintf('%s/post.handler.php', API_HANDLERS_ABSOLUTE_PATH); break;
    case 'GET': $handler_path = sprintf('%s/get.handler.php', API_HANDLERS_ABSOLUTE_PATH); break;
    case 'PATCH': $handler_path = sprintf('%s/patch.handler.php', API_HANDLERS_ABSOLUTE_PATH); break;
    case 'DELETE': $handler_path = sprintf('%s/delete.handler.php', API_HANDLERS_ABSOLUTE_PATH); break;
    case 'PUT': $handler_path = sprintf('%s/put.handler.php', API_HANDLERS_ABSOLUTE_PATH); break;
  }

  // Если абсолютный путь не был инициализирован, то запрещаем дальше работать с API
  if (!isset($handler_path)) {
    http_response_code(500);
    die('The handler associated with the request method was not found in the system.');
  }

  // Подключаем файл необходимого обработчика
  if (file_exists($handler_path)) {
    include_once($handler_path);
  }
}

?>