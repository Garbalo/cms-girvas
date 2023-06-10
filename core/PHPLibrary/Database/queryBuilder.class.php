<?php

namespace core\PHPLibrary\Database {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert as StatementInsert;
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate as StatementUpdate;
  use \core\PHPLibrary\Database\QueryBuilder\StatementDelete as StatementDelete;

  class QueryBuilder {
    public StatementSelect|StatementInsert|StatementUpdate|StatementDelete $statement;
    
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
    
    /**
     * set_statement_delete
     *
     * @return void
     */
    public function set_statement_delete() : void {
      $this->statement = new StatementDelete($this);
    }
  }

}

?>