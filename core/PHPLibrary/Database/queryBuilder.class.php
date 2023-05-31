<?php

namespace core\PHPLibrary\Database {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert as StatementInsert;

  class QueryBuilder {
    public StatementSelect|StatementInsert $statement;
    
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
    
    /**
     * set_statement_select
     *
     * @return void
     */
    public function set_statement_insert() : void {
      $this->statement = new StatementInsert($this);
    }
  }

}

?>