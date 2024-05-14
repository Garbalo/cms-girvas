<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\SystemCore {

  interface InterfaceFileConnector {
    public function __construct(\core\PHPLibrary\SystemCore $system_core);
    public function set_current_directory(string $directory) : void;
    public function get_current_directory() : string;
    public function connect_file(string $file_name) : bool;
  }

}

?>