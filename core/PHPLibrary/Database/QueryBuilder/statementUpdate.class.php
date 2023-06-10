<?php

namespace core\PHPLibrary\Database\QueryBuilder {
  use \core\PHPLibrary\Database\QueryBuilder as QueryBuilder;
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate\ClauseSet as ClauseSet;
  use \core\PHPLibrary\Database\QueryBuilder\StatementUpdate\ClauseWhere as ClauseWhere;
  use \core\PHPLibrary\Database\QueryBuilder\InterfaceStatement as InterfaceStatement;

  final class StatementUpdate implements InterfaceStatement {
    private QueryBuilder $query_builder;
    private array $columns = [];
    public ClauseSet|null $clause_set = null;
    public ClauseWhere|null $clause_where = null;
    public string $table_name = '';
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
    
    /**
     * Установить предложение SET
     *
     * @return void
     */
    public function set_clause_set() : void {
      $this->clause_set = new ClauseSet($this);
    }
    
    /**
     * Установить предложение WHERE
     *
     * @return void
     */
    public function set_clause_where() : void {
      $this->clause_where = new ClauseWhere($this);
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
     * Получить имя таблицы
     *
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
      $query_array = [];

      if (!is_null($this->clause_set)) {
        $this->clause_set->assembly();
        array_push($query_array, $this->clause_set->assembled);
      }

      if (!is_null($this->clause_where)) {
        $this->clause_where->assembly();
        array_push($query_array, $this->clause_where->assembled);
      }

      $this->assembled = sprintf('UPDATE %s %s;', $this->get_table(), implode(' ', $query_array));
    }
  }

}

?>