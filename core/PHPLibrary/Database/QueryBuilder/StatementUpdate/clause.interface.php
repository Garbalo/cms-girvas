<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementUpdate {
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate as StatementUpdate;

  interface InterfaceClause {
    public function __construct(StatementUpdate $statement);
  }

}

?>