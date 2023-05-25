<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementSelect {
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect\InterfaceClause as InterfaceClause;
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect as StatementSelect;
  use \core\PHPLibrary\Database\QueryBuilder\StatementSelect\ClauseFrom\Table as Table;

  final class ClauseFrom implements InterfaceClause {
    private StatementSelect $statement;
    public array $tables;
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
     * add_table
     *
     * @param  mixed $table_name
     * @return void
     */
    public function add_table(string $table_name) : void {
      $this->tables[$table_name] = new Table($table_name);
    }
    
    /**
     * assembly
     *
     * @return void
     */
    public function assembly() {
      $query_array = [];

      foreach ($this->tables as $table) {
        array_push($query_array, $table->get_name());
      }

      if (count($this->tables) > 0) {
        $this->assembled = sprintf('FROM %s', implode(', ', $query_array));
      } else {
        $this->assembled =  '';
      }
    }

  }
}

?>