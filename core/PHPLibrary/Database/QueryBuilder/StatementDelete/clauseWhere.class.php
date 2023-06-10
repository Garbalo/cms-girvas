<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementDelete {
  use \core\PHPLibrary\Database\QueryBuilder\StatementDelete\InterfaceClause as InterfaceClause;
  use \core\PHPLibrary\Database\QueryBuilder\StatementDelete as StatementDelete;

  final class ClauseWhere implements InterfaceClause {
    private StatementDelete $statement;
    public string $condition = '';
    public string $assembled = '';
    
    /**
     * __construct
     *
     * @param  mixed $statement
     * @return void
     */
    public function __construct(StatementDelete $statement) {
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