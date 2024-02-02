<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary {  
  /**
   * Modules
   * 
   * Класс для работы с несколькими модулями CMS
   * 
   * @author Andrey Shestakov <drelagas.new@gmail.com>
   * @version 0.0.1
   */
  class Modules {
    const RELATIVE_MODULES_PATH = 'modules';

    public static function get_absolute_modules_path() : string {
      return sprintf('%s/%s', CMS_ROOT_DIRECTORY, self::RELATIVE_MODULES_PATH);
    }

    public static function get_installed_modules_array() : array {
      return array_diff(scandir(self::get_absolute_modules_path()), ['.', '..']);
    }
  }
}

?>