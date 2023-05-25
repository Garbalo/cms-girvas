<?php

namespace core\PHPLibrary\Database {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;

  class QueryBuilder {
    public StatementSelect $statement;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
      // ...
    }
    
    /**
     * set_statement_select
     *
     * @return void
     */
    public function set_statement_select() : void {
      $this->statement = new StatementSelect($this);
    }
  }

}

?>