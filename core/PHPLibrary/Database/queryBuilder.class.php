<?php

namespace core\PHPLibrary\Database {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert as StatementInsert;
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate as StatementUpdate;

  class QueryBuilder {
    public StatementSelect|StatementInsert|StatementUpdate $statement;
    
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
     * set_statement_insert
     *
     * @return void
     */
    public function set_statement_insert() : void {
      $this->statement = new StatementInsert($this);
    }
    
    /**
     * set_statement_update
     *
     * @return void
     */
    public function set_statement_update() : void {
      $this->statement = new StatementUpdate($this);
    }
  }

}

?>