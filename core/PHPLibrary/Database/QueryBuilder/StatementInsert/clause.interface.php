<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Database\QueryBuilder\StatementInsert {
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert as StatementInsert;

  interface InterfaceClause {
    public function __construct(StatementInsert $statement);
  }

}

?>