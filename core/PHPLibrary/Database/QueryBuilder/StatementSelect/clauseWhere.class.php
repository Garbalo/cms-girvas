<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementSelect {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect\InterfaceClause as InterfaceClause;
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;

  final class ClauseWhere implements InterfaceClause {
    private StatementSelect $statement;
    public string $condition = '';
    public string $assembled = '';
    
    /**
     * __construct
     *
     * @param  mixed $statement
     * @return void
     */
    public function __construct(StatementSelect $statement) {
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