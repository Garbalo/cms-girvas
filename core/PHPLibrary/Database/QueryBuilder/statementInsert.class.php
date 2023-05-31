<?php

namespace core\PHPLibrary\Database\QueryBuilder {
  use \core\PHPLibrary\Database\QueryBuilder as QueryBuilder;
  use \core\PHPLibrary\Database\QueryBuilder\StatementInsert\ClauseReturning as ClauseReturning;
  use \core\PHPLibrary\Database\QueryBuilder\InterfaceStatement as InterfaceStatement;

  final class StatementInsert implements InterfaceStatement {
    private QueryBuilder $query_builder;
    private array $columns = [];
    public string $table_name = '';
    public ClauseReturning|null $clause_returning = null;
    public string $assembled = '';

    /**
     * __construct
     *
     * @param  mixed $query_builder
     * @return void
     */
    public function __construct(QueryBuilder $query_builder) {
      $this->query_builder = $query_builder;
    }

    public function set_clause_returning() : void {
      $this->clause_returning = new ClauseReturning($this);
    }
    
    /**
     * Добавить значение столбца
     *
     * @param  mixed $column_name
     * @param  mixed $value
     * @return void
     */
    public function add_column(string $column_name) : void {
      array_push($this->columns, $column_name);
    }
    
    /**
     * Назначить имя таблицы
     *
     * @param  mixed $table_name
     * @return void
     */
    public function set_table(string $table_name) : void {
      $this->table_name = $table_name;
    }
    
    /**
     * Получить наименование таблицы
     *
     * @param  mixed $table_name
     * @return string
     */
    public function get_table() : string {
      return $this->table_name;
    }

    /**
     * Сборка SQL-запроса
     *
     * @return void
     */
    public function assembly() : void {
      $columns_values = [];
      foreach ($this->columns as $column_name) {
        array_push($columns_values, ':' . $column_name);
      }

      $this->assembled = sprintf('INSERT INTO %s (%s) VALUES (%s);', $this->get_table(), implode(', ', $this->columns), implode(', ', $columns_values));
    }
  }

}

?>