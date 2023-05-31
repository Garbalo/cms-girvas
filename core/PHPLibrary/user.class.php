<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  #[\AllowDynamicProperties]
  class User {
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

    public function get_login() : string {
      return (property_exists($this, 'login')) ? $this->login : '{ERROR:USER_DATA_IS_NOT_EXISTS=login}';
    }

    public function get_password_hash() : string {
      return (property_exists($this, 'password_hash')) ? $this->password_hash : '{ERROR:USER_DATA_IS_NOT_EXISTS=password_hash}';
    }

    public function get_security_hash() : string {
      return (property_exists($this, 'security_hash')) ? $this->security_hash : '{ERROR:USER_DATA_IS_NOT_EXISTS=security_hash}';
    }

    public function hashing(string $string) {
      $user_id = $this->get_id();
      $security_hash = $this->get_security_hash();
      $system_salt = $this->system_core->configurator->get('system_salt');
      $hash_source = sprintf('{GIRVAS:%s:%d+%s=>%s}', $security_hash, $user_id, $system_salt, $string);
      return md5($hash_source);
    }

    public function password_hash(string $string) {
      $user_id = $this->get_id();
      $security_hash = $this->get_security_hash();
      $system_salt = $this->system_core->configurator->get('system_salt');
      $password_hashing_algorithm = $this->system_core->configurator->get('password_hashing_algorithm');
      $crypt_source = sprintf('{GIRVAS:%s:%d+%s=>%s}', $security_hash, $user_id, $system_salt, $string);
      return password_hash($crypt_source, $password_hashing_algorithm);
    }

    private function get_database_columns_data(array $columns = ['*']) : array|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections($columns);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();
      
      /** @var int $user_id Идентификационный номер записи */
      $user_id = $this->get_id();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $user_id, \PDO::PARAM_INT);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result : null;
    }
    
    /**
     * Получить объекта пользователя по логину
     *
     * @param  mixed $system_core
     * @param  mixed $user_login
     * @return Entry
     */
    public static function get_by_login(SystemCore $system_core, string $user_login) : User|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('login = :login');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? new User($system_core, (int)$result['id']) : null;
    }
    
    /**
     * Проверить существование пользователя по логину
     *
     * @param  mixed $system_core
     * @param  mixed $user_login
     * @return void
     */
    public static function exists_by_login(\core\PHPLibrary\SystemCore $system_core, string $user_login) {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('login = :login');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }

    public static function create(SystemCore $system_core, array $user_data = []) : User|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_insert();
      $query_builder->statement->set_table('users');
      $query_builder->statement->add_column('login');
      $query_builder->statement->add_column('email');
      $query_builder->statement->add_column('password_hash');
      $query_builder->statement->add_column('security_hash');
      $query_builder->statement->add_column('created_unix_timestamp');
      $query_builder->statement->add_column('updated_unix_timestamp');
      $query_builder->statement->add_column('metadata_json');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $user_login = $user_data['login'];
      $user_email = $user_data['email'];
      $user_password_hash = $user_data['password_hash'];
      $user_security_hash = $user_data['security_hash'];
      $user_created_unix_timestamp = time();
      $user_updated_unix_timestamp = $user_created_unix_timestamp;
      $user_metadata_json = $user_data['metadata_json'];
      
      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
      $database_query->bindParam(':email', $user_email, \PDO::PARAM_STR);
      $database_query->bindParam(':password_hash', $user_password_hash, \PDO::PARAM_STR);
      $database_query->bindParam(':security_hash', $user_security_hash, \PDO::PARAM_STR);
      $database_query->bindParam(':created_unix_timestamp', $user_created_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $user_updated_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':metadata_json', $user_metadata_json, \PDO::PARAM_STR);
			$execute = $database_query->execute();

      if ($execute) {
        $result = $database_query->fetch(\PDO::FETCH_ASSOC);
        return ($result) ? new User($system_core, $result['id']) : null;
      }

      return null;
    }
  }
}

?>