<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementSelect {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;

  interface InterfaceClause {
    public function __construct(StatementSelect $statement);
  }

}

?>