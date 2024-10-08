<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Template {

  final class Debugger {
    private \core\PHPLibrary\Template $template;

    public function __construct(\core\PHPLibrary\Template $template) {
      $this->template = $template;
    }
  }
}

?>