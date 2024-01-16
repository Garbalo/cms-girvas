<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary {  
  /**
   * Templates
   * 
   * Класс для работы с несколькими шаблонами CMS
   * 
   * @author Andrey Shestakov <drelagas.new@gmail.com>
   * @version 0.0.1
   */
  class Templates {
    const RELATIVE_TEMPLATES_PATH = 'templates';

    public static function get_absolute_templates_path() : string {
      return sprintf('%s/%s', CMS_ROOT_DIRECTORY, self::RELATIVE_TEMPLATES_PATH);
    }

    public static function get_installed_templates_array() : array {
      return array_diff(scandir(self::get_absolute_templates_path()), ['.', '..']);
    }
  }
}

?>