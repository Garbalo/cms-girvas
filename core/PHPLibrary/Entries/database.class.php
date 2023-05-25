<?php

namespace core\PHPLibrary\Entries {

  final class Database {
    private \core\PHPLibrary\Database $database;
    private \core\PHPLibrary\Entries $entries;
    private mixed $data;
    private array $conditions = [];
    private int $limit = 100;
    private array $select_columns = [];
    
    /**
     * __construct
     *
     * @param  mixed $database
     * @param  mixed $entries
     * @return void
     */
    public function __construct(\core\PHPLibrary\Database $database) {
      $this->database = $database;
    }

    public function add_condition(string|array $condition) : void {
      if (is_string($condition)) {
        array_push($this->conditions, $condition);
      } else {
        $this->conditions = array_merge($this->conditions, $condition);
      }
    }

    public function add_select_column(string|array $select_columns) : void {
      if (is_string($condition)) {
        array_push($this->select_columns, $select_columns);
      } else {
        $this->select_columns = array_merge($this->select_columns, $select_columns);
      }
    }

    public function set_limit(int $limit) : void {
      $this->limit = $limit;
    }

    public function get_data_by_category_id(string|array $columns, int $category_id, int $entries_limit = 100) : array {
      /** @var string $database_query SQL-запрос */
      $database_query = '';
      /** @var EnumDatabaseManagementSystem $database_management_system */
      $database_management_system = $this->database->get_database_management_system();
      switch ($database_management_system->value) {
        case 'mysql': $database_query = $this->database->get_file_sql('Entries/get.mysql.sql'); break;
        case 'pgsql': $database_query = $this->database->get_file_sql('Entries/get.pgsql.sql'); break;
      }

      /** @var string $database_query SQL-запрос (переопределение) */
      $database_query_select_columns = (is_string($columns)) ? $columns : implode(', ', $columns);
      $database_query = sprintf($database_query, $database_query_select_columns, $entries_limit);

      $this->database->prepare($database_query);
      $this->database->bindParam(':category_id', $category_id, \PDO::PARAM_INT);
			$this->database->execute();

      $result = $database_query->fetchAll(\PDO::FETCH_ASSOC);
			return (count($result) > 0) ? $result : [];
    }
  }
}