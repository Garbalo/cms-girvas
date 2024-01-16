<?php

namespace core\PHPLibrary\Database\QueryBuilder {
  use \core\PHPLibrary\Database\QueryBuilder as QueryBuilder;
  use \core\PHPLibrary\Database\QueryBuilder\InterfaceStatement as InterfaceStatement;

  final class StatementCreateTable implements InterfaceStatement {
    public QueryBuilder $query_builder;
    private string $table_name = '';
    private bool $check_exists = false;
    private array $columns = [];
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
     * Установить имя создаваемой таблицы
     *
     * @param  string $name
     * @return void
     */
    public function set_table_name(string $name) : void {
      $this->table_name = $name;
    }

    /**
     * Получить имя создаваемой таблицы
     *
     * @return string
     */
    public function get_table_name() : string {
      $database_configurations = $this->query_builder->system_core->configurator->get('database');
      
      $table_fullname = '';
      if ($database_configurations['scheme'] != '') {
        $table_fullname .= sprintf('%s.', $database_configurations['scheme']);
      }
      if ($database_configurations['prefix'] != '') {
        $table_fullname .= sprintf('%s_', $database_configurations['prefix']);
      }

      $table_fullname .= $this->table_name;
      return $table_fullname;
    }

    public function set_check_exists(bool $value) : void {
      $this->check_exists = $value;
    }
    
    /**
     * Установить выборку для SELECT
     *
     * @param  mixed $selection
     * @return void
     */
    public function add_column(string $column_name, string $column_type, string $column_constraint = '') : void {
      $array = [];
      array_push($array, $column_name);
      array_push($array, $column_type);
      array_push($array, $column_constraint);

      array_push($this->columns, implode(' ', $array));

      unset($array);
    }
    
    /**
     * Сборка SQL-запроса
     *
     * @return void
     */
    public function assembly() : void {
      $if_not_exists = ($this->check_exists) ? 'IF NOT EXISTS' : '';
      $this->assembled = sprintf('CREATE TABLE %s %s (%s);', $if_not_exists, $this->get_table_name(), implode(', ', $this->columns));
    }

  }

}

?>