<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementUpdate {
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate\InterfaceClause as InterfaceClause;
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate as StatementUpdate;

  final class ClauseWhere implements InterfaceClause {
    private StatementUpdate $statement;
    public string $condition = '';
    public string $assembled = '';
    
    /**
     * __construct
     *
     * @param  mixed $statement
     * @return void
     */
    public function __construct(StatementUpdate $statement) {
      $this->statement = $statement;
    }
    
    /**
     * set_condition
     *
     * @param  mixed $condition
     * @return void
     */
    public function add_condition(string $condition) : void {
      $this->condition = $condition;
    }
    
    /**
     * assembly
     *
     * @return void
     */
    public function assembly() {
      if ($this->condition != '') {
        $this->assembled = sprintf('WHERE %s', $this->condition);
      } else {
        $this->assembled = '';
      }
    }

  }
}

?>