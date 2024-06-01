<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\WebChannel {
  enum EnumSpecification {
    case RSS1_0;
    case RSS2_0;
    case Atom;
    case YandexTurbo;
  }
}

?>