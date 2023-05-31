<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementInsert {
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert as StatementInsert;

  interface InterfaceClause {
    public function __construct(StatementInsert $statement);
  }

}

?>