<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  #[\AllowDynamicProperties]
  class EntryComment {
    private readonly SystemCore $system_core;
    private int $id;
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @param  int $id
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
     * Назначить идентификатор комментарию
     *
     * @param  mixed $value
     * @return void
     */
    private function set_id(int $value) : void {
      $this->id = $value;
    }
    
    /**
     * Получить идентификатор комментария
     *
     * @return int
     */
    public function get_id() : int {
      return $this->id;
    }
    
    /**
     * Получить идентификатор записи, к которой написан комментарий
     *
     * @return int
     */
    public function get_entry_id() : int {
      return (property_exists($this, 'entry_id')) ? $this->entry_id : '{ERROR:ENTRY_COMMENT_DATA_IS_NOT_EXISTS=entry_id}';
    }
    
    /**
     * Получить объект записи, к которой написан комментарий
     *
     * @return Entry|null
     */
    public function get_entry() : Entry|null {
      return (property_exists($this, 'entry_id')) ? new User($this->system_core, $this->entry_id) : null;
    }
    
    /**
     * Получить идентификатор автора комментария
     *
     * @return int
     */
    public function get_author_id() : int {
      return (property_exists($this, 'author_id')) ? $this->author_id : '{ERROR:ENTRY_COMMENT_DATA_IS_NOT_EXISTS=author_id}';
    }
    
    /**
     * Получить объект автора комментария
     *
     * @return User|null
     */
    public function get_author() : User|null {
      return (property_exists($this, 'author_id')) ? new User($this->system_core, $this->author_id) : null;
    }
    
    /**
     * Получить идентификатор записи, к которой написан комментарий
     *
     * @return string
     */
    public function get_content() : string {
      return (property_exists($this, 'content')) ? $this->content : '{ERROR:ENTRY_COMMENT_DATA_IS_NOT_EXISTS=content}';
    }
    
    /**
     * Получить дату создания в UNIX-формате
     *
     * @return int
     */
    public function get_created_unix_timestamp() : int|string {
      return (property_exists($this, 'created_unix_timestamp')) ? $this->created_unix_timestamp : '{ERROR:ENTRY_COMMENT_DATA_IS_NOT_EXISTS=created_unix_timestamp}';
    }
    
    /**
     * Получить дату обновления в UNIX-формате
     *
     * @return int
     */
    public function get_updated_unix_timestamp() : int|string {
      return (property_exists($this, 'updated_unix_timestamp')) ? $this->updated_unix_timestamp : '{ERROR:ENTRY_COMMENT_DATA_IS_NOT_EXISTS=updated_unix_timestamp}';
    }
    
    /**
     * Получить данные колонок комментария в базе данных
     *
     * @param  mixed $columns
     * @return void
     */
    private function get_database_columns_data(array $columns = ['*']) : array|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections($columns);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
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
     * Удаление существующего комментария
     *
     * @return bool
     */
    public function delete() : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_delete();
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }
        
    /**
     * Создание нового комментария
     *
     * @param  SystemCore $system_core
     * @param  int $entry_id
     * @param  int $author_id
     * @param  string $content
     * @return EntryComment|null
     */
    public static function create(SystemCore $system_core, int $entry_id, int $author_id, string $content) : EntryComment|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_insert();
      $query_builder->statement->set_table('entries_comments');
      $query_builder->statement->add_column('author_id');
      $query_builder->statement->add_column('entry_id');
      $query_builder->statement->add_column('content');
      $query_builder->statement->add_column('created_unix_timestamp');
      $query_builder->statement->add_column('updated_unix_timestamp');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $entry_comment_created_unix_timestamp = time();
      $entry_comment_updated_unix_timestamp = $entry_comment_created_unix_timestamp;

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':author_id', $author_id, \PDO::PARAM_INT);
      $database_query->bindParam(':entry_id', $entry_id, \PDO::PARAM_INT);
      $database_query->bindParam(':content', $content, \PDO::PARAM_STR);
      $database_query->bindParam(':created_unix_timestamp', $entry_comment_created_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $entry_comment_updated_unix_timestamp, \PDO::PARAM_INT);
      $execute = $database_query->execute();

      if ($execute) {
        $result = $database_query->fetch(\PDO::FETCH_ASSOC);
        return ($result) ? new EntryComment($system_core, $result['id']) : null;
      }

      return null;
    }

    /**
     * Обновление существующего комментария
     *
     * @param  array $data Массив данных
     * @return bool
     */
    public function update(array $data) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_update();
      $query_builder->statement->set_table('entries_comments');
      $query_builder->statement->set_clause_set();

      foreach ($data as $data_name => $data_value) {
        if (!in_array($data_name, ['id', 'created_unix_timestamp', 'updated_unix_timestamp'])) {
          $query_builder->statement->clause_set->add_column($data_name);
        }
      }

      $query_builder->statement->clause_set->add_column('updated_unix_timestamp');
      $query_builder->statement->clause_set->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      /** @var int $entry_updated_unix_timestamp Текущее время в UNIX-формате */
      $entry_comment_updated_unix_timestamp = time();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      error_log($query_builder->statement->assembled);
      foreach ($data as $data_name => $data_value) {
        if (!in_array($data_name, ['id', 'created_unix_timestamp', 'updated_unix_timestamp'])) {
          switch (gettype($data_value)) {
            case 'boolean': $data_value_type = \PDO::PARAM_BOOL; break;
            case 'integer': $data_value_type = \PDO::PARAM_INT; break;
            case 'string': $data_value_type = \PDO::PARAM_STR; break;
            case 'null': $data_value_type = \PDO::PARAM_NULL; break;
          }

          $database_query->bindParam(':' . $data_name, $data[$data_name], $data_value_type);
        }
      }

      $database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $entry_comment_updated_unix_timestamp, \PDO::PARAM_INT);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }
  }
}

?>