<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  #[\AllowDynamicProperties]
  class UserGroup {
    // Административные права
    public const PERMISSION_ADMIN_PANEL_AUTH                    = 1 << 0;
    public const PERMISSION_ADMIN_USERS_MANAGEMENT              = 1 << 1;
    public const PERMISSION_ADMIN_USERS_GROUPS_MANAGEMENT       = 1 << 2;
    public const PERMISSION_ADMIN_MODULES_MANAGEMENT            = 1 << 3;
    public const PERMISSION_ADMIN_TEMPLATES_MANAGEMENT          = 1 << 4;
    public const PERMISSION_ADMIN_SETTINGS_MANAGEMENT           = 1 << 5;
    public const PERMISSION_ADMIN_VIEWING_LOGS                  = 1 << 6;
    // Права модерации
    public const PERMISSION_MODER_USERS_BAN                     = 1 << 7;
    public const PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT   = 1 << 8;
    public const PERMISSION_MODER_USERS_WARNS                   = 1 << 9;
    // Права редакции
    public const PERMISSION_EDITOR_MEDIA_FILES_MANAGEMENT       = 1 << 10;
    public const PERMISSION_EDITOR_ENTRIES_EDIT                 = 1 << 11;
    public const PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT      = 1 << 12;
    public const PERMISSION_EDITOR_PAGES_STATIC_EDIT            = 1 << 13;

    public const PERMISSION_BASE_ENTRY_COMMENT_CREATE           = 1 << 14;
    public const PERMISSION_BASE_ENTRY_COMMENT_CHANGE           = 1 << 15;
    public const PERMISSION_BASE_ENTRY_COMMENT_RATE             = 1 << 16;

    private readonly SystemCore $system_core;
    private int $id;
    
    /**
     * __construct
     *
     * @param  mixed $system_core
     * @param  mixed $id
     * @return void
     */
    public function __construct(SystemCore $system_core, int $id) {
      $this->system_core = $system_core;
      $this->set_id($id);
    }

    public function init_data(array $columns = ['*']) {
      $columns_data = $this->get_database_columns_data($columns);
      foreach ($columns_data as $column_name => $column_data) {
        $this->{$column_name} = $column_data;
      }
    }

    /**
     * Назначить идентификатор
     *
     * @param  mixed $value
     * @return void
     */
    private function set_id(int $value) : void {
      $this->id = $value;
    }
    
    /**
     * Получить идентификатор
     *
     * @param  mixed $value
     * @return int
     */
    public function get_id() : int {
      return $this->id;
    }

    public function get_name() : string {
      return (property_exists($this, 'name')) ? $this->name : '{ERROR:USER_GROUP_DATA_IS_NOT_EXISTS=name}';
    }

    public function get_permissions() : int {
      return (property_exists($this, 'permissions')) ? $this->permissions : 0;
    }

    public function get_users() : array {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('(metadata_json->>\'group_id\')::int = :group_id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':group_id', $this->id, \PDO::PARAM_INT);
      $execute = $database_query->execute();

      $users = [];

      if ($execute) {
        $result = $database_query->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $user_data) {
          array_push($users, new User($this->system_core, $user_data['id']));
        }
      }

      return $users;
    }

    public function get_users_count() : int {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['count(*)']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('(metadata_json->>\'group_id\')::int = :group_id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':group_id', $this->id, \PDO::PARAM_INT);
      $execute = $database_query->execute();

      if ($execute) {
        $result = $database_query->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
      }

      return 0;
    }

    public function get_created_unix_timestamp() : int|string {
      return (property_exists($this, 'created_unix_timestamp')) ? $this->created_unix_timestamp : '{ERROR:USER_GROUP_DATA_IS_NOT_EXISTS=created_unix_timestamp}';
    }

    public function get_updated_unix_timestamp() : int|string {
      return (property_exists($this, 'updated_unix_timestamp')) ? $this->updated_unix_timestamp : '{ERROR:USER_GROUP_IS_NOT_EXISTS=updated_unix_timestamp}';
    }

    public function permission_check(int $permission) : bool {
      $permissions = $this->get_permissions();
      
      if (($permissions & $permission) == $permission) {
        return true;
      }

      return false;
    }
    
    /**
     * Получить заголовок
     *
     * @return string
     */
    public function get_title($locale_name = 'ru_RU') : string {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['texts'][$locale_name]['title'])) {
          return $metadata_array['texts'][$locale_name]['title'];
        }
      }

      return '{ERROR:USER_GROUP_DATA_IS_NOT_EXISTS=metadata_title}';
    }

    private function get_database_columns_data(array $columns = ['*']) : array|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections($columns);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_groups');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();
      
      /** @var int $user_group_id Идентификационный номер записи */
      $user_group_id = $this->get_id();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $user_group_id, \PDO::PARAM_INT);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result : null;
    }
    
    /**
     * Получить объект группы пользователя по наименованию
     *
     * @param  mixed $system_core
     * @param  mixed $group_name
     * @return UserGroup
     */
    public static function get_by_name(SystemCore $system_core, string $group_name) : UserGroup|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_groups');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('LOWER(name) = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $group_name = strtolower($group_name);

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $group_name, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      
      return ($result) ? new UserGroup($system_core, (int)$result['id']) : null;
    }
    
    /**
     * Проверить существование группы пользователей по наименованию
     *
     * @param  mixed $system_core
     * @param  string $group_name
     * @return void
     */
    public static function exists_by_name(\core\PHPLibrary\SystemCore $system_core, string $group_name) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_groups');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('LOWER(name) = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $group_name = strtolower($group_name);

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $group_name, \PDO::PARAM_STR);
			$database_query->execute();
      
      return ($database_query->fetchColumn()) ? true : false;
    }
    
    /**
     * Проверить существование группы пользователей по ID
     *
     * @param  mixed $system_core
     * @param  int $group_id
     * @return void
     */
    public static function exists_by_id(\core\PHPLibrary\SystemCore $system_core, int $group_id) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_groups');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $group_id, \PDO::PARAM_INT);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }

    /**
     * Удаление существующей группы пользователей
     *
     * @return bool
     */
    public function delete() : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_delete();
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_groups');
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

    public static function create(SystemCore $system_core, string $group_name, array $permissions = []) : UserGroup|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_insert();
      $query_builder->statement->set_table('users_groups');
      $query_builder->statement->add_column('name');
      $query_builder->statement->add_column('created_unix_timestamp');
      $query_builder->statement->add_column('updated_unix_timestamp');
      $query_builder->statement->add_column('permissions');
      $query_builder->statement->add_column('metadata_json');
      $query_builder->statement->add_column('email_is_submitted');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $user_group_created_unix_timestamp = time();
      $user_group_updated_unix_timestamp = $user_group_created_unix_timestamp;
      $user_group_metadata_json = json_encode([], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $user_group_permissions = 0x0000000000000000;
      foreach ($permissions as $permission) {
        $user_group_permissions = $user_group_permissions | $permission;
      }

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $group_name, \PDO::PARAM_STR);
      $database_query->bindParam(':created_unix_timestamp', $user_created_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $user_updated_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':permissions', $user_group_permissions, \PDO::PARAM_INT);
      $database_query->bindParam(':metadata_json', $user_group_metadata_json, \PDO::PARAM_STR);
			$execute = $database_query->execute();

      if ($execute) {
        $result = $database_query->fetch(\PDO::FETCH_ASSOC);
        return ($result) ? new UserGroup($system_core, $result['id']) : null;
      }

      return null;
    }

    /**
     * Обновление существующего пользователя
     *
     * @param  array $data Массив данных
     * @return bool
     */
    public function update(array $data) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_update();
      $query_builder->statement->set_table('users_groups');
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

      /** @var int $user_updated_unix_timestamp Текущее время в UNIX-формате */
      $user_group_updated_unix_timestamp = time();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      
      foreach ($data as $data_name => $data_value) {
        if (!in_array($data_name, ['id', 'created_unix_timestamp', 'updated_unix_timestamp'])) {
          switch (gettype($data_value)) {
            case 'boolean': $data_value_type = \PDO::PARAM_INT; break;
            case 'integer': $data_value_type = \PDO::PARAM_INT; break;
            case 'string': $data_value_type = \PDO::PARAM_STR; break;
            case 'null': $data_value_type = \PDO::PARAM_NULL; break;
          }
          
          $database_query->bindParam(':' . $data_name, $data[$data_name], $data_value_type);
        }
      }
      
      $database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $user_group_updated_unix_timestamp, \PDO::PARAM_INT);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }
  }
}

?>