<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementDelete {
  use \core\PHPLibrary\Database\QueryBuilder\StatementDelete as StatementDelete;

  interface InterfaceClause {
    public function __construct(StatementDelete $statement);
  }

}

?>