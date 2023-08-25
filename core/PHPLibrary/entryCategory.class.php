<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  #[\AllowDynamicProperties]
  class EntryCategory {
    private readonly SystemCore $system_core;
    private int $id;

    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @return void
     */
    public function __construct(SystemCore $system_core, int $id) {
      $this->system_core = $system_core;
      $this->set_id($id);
    }
    
    /**
     * Инициализация данных из БД
     *
     * @param  mixed $columns
     * @return void
     */
    public function init_data(array $columns = ['*']) {
      $columns_data = $this->get_database_columns_data($columns);
      foreach ($columns_data as $column_name => $column_data) {
        $this->{$column_name} = $column_data;
      }
    }
    
    /**
     * Назначить идентификатор записи
     *
     * @param  mixed $value
     * @return void
     */
    private function set_id(int $value) : void {
      $this->id = $value;
    }
    
    /**
     * Получить идентификатор записи
     *
     * @param  mixed $value
     * @return int
     */
    public function get_id() : int {
      return $this->id;
    }
    
    /**
     * Получить дату создания (в UNIX-формате)
     *
     * @return int
     */
    public function get_created_unix_timestamp() : int|string {
      return (property_exists($this, 'created_unix_timestamp')) ? $this->created_unix_timestamp : '{ERROR:ENTRY_DATA_IS_NOT_EXISTS=created_unix_timestamp}';
    }
    
    /**
     * Получить дату последнего обновления (в UNIX-формате)
     *
     * @return int
     */
    public function get_updated_unix_timestamp() : int|string {
      return (property_exists($this, 'updated_unix_timestamp')) ? $this->updated_unix_timestamp : '{ERROR:ENTRY_DATA_IS_NOT_EXISTS=updated_unix_timestamp}';
    }
    
    /**
     * Получить ID родительской категории
     *
     * @return int
     */
    public function get_parent_id() : int {
      return (property_exists($this, 'parent_id')) ? $this->parent_id : 0;
    }
    
    /**
     * Получить заголовок записи
     *
     * @param  mixed $locale_name Наименование локализации
     * @return string
     */
    public function get_title($locale_name = 'ru_RU') : string {
      if (property_exists($this, 'texts')) {
        $texts_array = json_decode($this->texts, true);
        if (isset($texts_array[$locale_name]['title'])) {
          return $texts_array[$locale_name]['title'];
        }
      }

      return '{ERROR:ENTRY_CATEGORY_DATA_IS_NOT_EXISTS=texts_title}';
    }

    /**
     * Получить описание записи
     *
     * @param  mixed $locale_name Наименование локализации
     * @return string
     */
    public function get_description($locale_name = 'ru_RU') : string {
      if (property_exists($this, 'texts')) {
        $texts_array = json_decode($this->texts, true);
        if (isset($texts_array[$locale_name]['description'])) {
          return $texts_array[$locale_name]['description'];
        }
      }

      return '{ERROR:ENTRY_CATEGORY_DATA_IS_NOT_EXISTS=texts_description}';
    }
    
    /**
     * Получить имя
     *
     * @return void
     */
    public function get_name() {
      return (property_exists($this, 'name')) ? $this->name : '{ERROR:ENTRY_CATEGORY_DATA_IS_NOT_EXISTS=name}';
    }
    
    /**
     * Получить URL до категории с записями
     *
     * @return void
     */
    public function get_url() {
      return sprintf('/entries/%s', $this->get_name());
    }
    
    /**
     * Получить массив объектов записей
     *
     * @return array
     */
    public function get_entries() : array {
      return Entries::get_by_category_id($this->id);
    }
    
    /**
     * Получить данные колонок записи в базе данных
     *
     * @param  array $columns
     * @return void
     */
    private function get_database_columns_data(array $columns = ['*']) : array|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections($columns);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_categories');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();
      
      /** @var int $entry_id Идентификационный номер записи */
      $entry_id = $this->get_id();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $entry_id, \PDO::PARAM_INT);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result : null;
    }

    /**
     * Проверка наличия категории записи по идентификационному номеру
     *
     * @param  SystemCore $system_core
     * @param  int $category_id
     * @return bool
     */
    public static function exists_by_id(SystemCore $system_core, int $category_id) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_categories');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $category_id, \PDO::PARAM_INT);
			$database_query->execute();
      
      return ($database_query->fetchColumn()) ? true : false;
    }

    /**
     * Проверка наличия категории записи по имени
     *
     * @param  SystemCore $system_core
     * @param  string $category_name
     * @return bool
     */
    public static function exists_by_name(SystemCore $system_core, string $category_name) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_categories');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('name = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();
      
      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $category_name, \PDO::PARAM_STR);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }
    
    /**
     * Получить объект категории записи по имени
     *
     * @param  SystemCore $system_core
     * @param  string $category_name
     * @return EntryCategory
     */
    public static function get_by_name(SystemCore $system_core, string $category_name) : EntryCategory|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_categories');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('name = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $category_name, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? new EntryCategory($system_core, (int)$result['id']) : null;
    }
  }
}