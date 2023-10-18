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

    public function get_email() : string {
      return (property_exists($this, 'email')) ? $this->email : '{ERROR:USER_DATA_IS_NOT_EXISTS=email}';
    }

    public function get_password_hash() : string {
      return (property_exists($this, 'password_hash')) ? $this->password_hash : '{ERROR:USER_DATA_IS_NOT_EXISTS=password_hash}';
    }

    public function get_security_hash() : string {
      return (property_exists($this, 'security_hash')) ? $this->security_hash : '{ERROR:USER_DATA_IS_NOT_EXISTS=security_hash}';
    }

    public function get_created_unix_timestamp() : int|string {
      return (property_exists($this, 'created_unix_timestamp')) ? $this->created_unix_timestamp : '{ERROR:USER_DATA_IS_NOT_EXISTS=created_unix_timestamp}';
    }

    public function get_updated_unix_timestamp() : int|string {
      return (property_exists($this, 'updated_unix_timestamp')) ? $this->updated_unix_timestamp : '{ERROR:USER_DATA_IS_NOT_EXISTS=updated_unix_timestamp}';
    }

    public static function get_avatar_default_url(SystemCore $system_core, int $size) : string {
      return sprintf('/%s/images/avatar_default_%d.png', $system_core->template->get_url(), $size);
    }
    
    /**
     * Получить имя пользователя
     *
     * @return string
     */
    public function get_name() : string {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['name'])) {
          return $metadata_array['name'];
        }
      }

      return '{ERROR:USER_DATA_IS_NOT_EXISTS=metadata_name}';
    }
    
    /**
     * Получить фамилию пользователя
     *
     * @return string
     */
    public function get_surname() : string {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['surname'])) {
          return $metadata_array['surname'];
        }
      }

      return '{ERROR:USER_DATA_IS_NOT_EXISTS=metadata_surname}';
    }
    
    /**
     * Получить отчество пользователя
     *
     * @return string
     */
    public function get_patronymic() : string {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['patronymic'])) {
          return $metadata_array['patronymic'];
        }
      }

      return '{ERROR:USER_DATA_IS_NOT_EXISTS=metadata_patronymic}';
    }
    
    /**
     * Получить ID группы пользователя
     *
     * @return int
     */
    public function get_group_id() : int {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['group_id'])) {
          return $metadata_array['group_id'];
        }
      }

      return 0;
    }
    
    /**
     * Получить объект группы пользователя
     *
     * @return UserGroup|null
     */
    public function get_group() : UserGroup|null {
      $group_id = $this->get_group_id();
      
      if (UserGroup::exists_by_id($this->system_core, $group_id)) {
        return new UserGroup($this->system_core, $group_id);
      }

      return null;
    }
    
    /**
     * Получить отчество пользователя
     *
     * @return int
     */
    public function get_birthdate_unix_timestamp() : int|string {
      if (property_exists($this, 'metadata_json')) {
        $metadata_array = json_decode($this->metadata_json, true);
        if (isset($metadata_array['birthdate_unix_timestamp'])) {
          return $metadata_array['birthdate_unix_timestamp'];
        }
      }

      return '{ERROR:USER_DATA_IS_NOT_EXISTS=metadata_birthdate_unix_timestamp}';
    }
    
    /**
     * Получить URL до аватарки пользователя
     *
     * @param  mixed $size
     * @return string
     */
    public function get_avatar_url(int $size) : string {
      $avatar_url = sprintf('%s/uploads/avatars/%d/%d.png', CMS_ROOT_DIRECTORY, $this->id, $size);
      if (file_exists($avatar_url)) {
        return $avatar_url;
      }

      return self::get_avatar_default_url($this->system_core, $size);
    }

    public function hashing(string $string) {
      $user_id = $this->get_id();
      $security_hash = $this->get_security_hash();
      $system_salt = $this->system_core->configurator->get('system_salt');
      $hash_source = sprintf('{GIRVAS:%s:%d+%s=>%s}', $security_hash, $user_id, $system_salt, $string);
      return md5($hash_source);
    }

    public static function password_hash(SystemCore $system_core, string $user_security_hash, string $password) : string {
      $system_salt = $system_core->configurator->get('system_salt');
      $password_hashing_algorithm = $system_core->configurator->get('password_hashing_algorithm');
      $crypt_source = sprintf('{GIRVAS:%s+%s=>%s}', $user_security_hash, $system_salt, $password);
      return password_hash($crypt_source, $password_hashing_algorithm);
    }

    public function password_verify(string $password) : bool {
      $system_salt = $this->system_core->configurator->get('system_salt');
      $crypt_source = sprintf('{GIRVAS:%s+%s=>%s}', $this->get_security_hash(), $system_salt, $password);
      return password_verify($crypt_source, $this->get_password_hash());
    }

    public static function generate_security_hash(SystemCore $system_core) : string {
      $system_salt = $system_core->configurator->get('system_salt');
      return md5(sprintf('{GIRVAS:%s+%d}', $system_salt, time()));
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
     * @return User
     */
    public static function get_by_login(SystemCore $system_core, string $user_login) : User|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('LOWER(login) = :login');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $user_login = strtolower($user_login);

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
     * @param  string $user_login
     * @return void
     */
    public static function exists_by_login(\core\PHPLibrary\SystemCore $system_core, string $user_login) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('LOWER(login) = :login');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $user_login = strtolower($user_login);

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
			$database_query->execute();
      
      return ($database_query->fetchColumn()) ? true : false;
    }
    
    /**
     * Проверить существование пользователя по E-Mail
     *
     * @param  mixed $system_core
     * @param  string $user_login
     * @return void
     */
    public static function exists_by_email(\core\PHPLibrary\SystemCore $system_core, string $user_email) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('LOWER(email) = :email');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $user_email = strtolower($user_email);

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':email', $user_email, \PDO::PARAM_STR);
			$database_query->execute();
      
      return ($database_query->fetchColumn()) ? true : false;
    }
    
    /**
     * Проверить существование пользователя по ID
     *
     * @param  mixed $system_core
     * @param  int $user_id
     * @return void
     */
    public static function exists_by_id(\core\PHPLibrary\SystemCore $system_core, int $user_id) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('id = :id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':id', $user_id, \PDO::PARAM_INT);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }

    /**
     * Удаление существующего пользователя
     *
     * @return bool
     */
    public function delete() : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_delete();
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users');
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

    public static function create(SystemCore $system_core, string $user_login, string $user_email, string $user_password) : User|null {
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
      $query_builder->statement->add_column('email_is_submitted');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $user_security_hash = self::generate_security_hash($system_core);
      $user_password_hash = self::password_hash($system_core, $user_security_hash, $user_password);
      $user_created_unix_timestamp = time();
      $user_updated_unix_timestamp = $user_created_unix_timestamp;
      $user_metadata_json = json_encode([], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
      $email_is_submitted = false;

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
      $database_query->bindParam(':email', $user_email, \PDO::PARAM_STR);
      $database_query->bindParam(':password_hash', $user_password_hash, \PDO::PARAM_STR);
      $database_query->bindParam(':security_hash', $user_security_hash, \PDO::PARAM_STR);
      $database_query->bindParam(':created_unix_timestamp', $user_created_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':updated_unix_timestamp', $user_updated_unix_timestamp, \PDO::PARAM_INT);
      $database_query->bindParam(':metadata_json', $user_metadata_json, \PDO::PARAM_STR);
      $database_query->bindParam(':email_is_submitted', $email_is_submitted, \PDO::PARAM_BOOL);
			$execute = $database_query->execute();

      if ($execute) {
        $result = $database_query->fetch(\PDO::FETCH_ASSOC);
        return ($result) ? new User($system_core, $result['id']) : null;
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
      $query_builder->statement->set_table('users');
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
      $user_updated_unix_timestamp = time();

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
      $database_query->bindParam(':updated_unix_timestamp', $user_updated_unix_timestamp, \PDO::PARAM_INT);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }

    public function create_registration_submit() : array|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_insert();
      $query_builder->statement->set_table('users_registration_submits');
      $query_builder->statement->add_column('user_id');
      $query_builder->statement->add_column('submit_token');
      $query_builder->statement->add_column('refusal_token');
      $query_builder->statement->add_column('created_unix_timestamp');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $request_time = time();
      $submit_token = md5(sprintf('[%d]%d => submit', $this->id, $request_time));
      $refusal_token = md5(sprintf('[%d]%d => refusal', $this->id, $request_time));
      $registration_submit_created_unix_timestamp = time();
      
      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':user_id', $this->id, \PDO::PARAM_INT);
      $database_query->bindParam(':submit_token', $submit_token, \PDO::PARAM_STR);
      $database_query->bindParam(':refusal_token', $refusal_token, \PDO::PARAM_STR);
      $database_query->bindParam(':created_unix_timestamp', $registration_submit_created_unix_timestamp, \PDO::PARAM_INT);
			$execute = $database_query->execute();

      if ($execute) {
        return [
          'submit_token' => $submit_token,
          'refusal_token' => $refusal_token
        ];
      }

      return null;
    }

    public static function get_user_id_by_registration_submit_token(\core\PHPLibrary\SystemCore $system_core, string $token) : int {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['user_id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('submit_token = :submit_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':submit_token', $token, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      
      return ($result) ? (int)$result['user_id'] : null;
    }

    public static function get_user_id_by_registration_refusal_token(\core\PHPLibrary\SystemCore $system_core, string $token) : int {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['user_id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('refusal_token = :refusal_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':refusal_token', $token, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      
      return ($result) ? (int)$result['user_id'] : null;
    }

    public static function exists_by_registration_submit_token(\core\PHPLibrary\SystemCore $system_core, string $token) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('submit_token = :submit_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':submit_token', $token, \PDO::PARAM_STR);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }

    public static function exists_by_registration_refusal_token(\core\PHPLibrary\SystemCore $system_core, string $token) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('refusal_token = :refusal_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':refusal_token', $token, \PDO::PARAM_STR);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }

    public static function delete_registration_submit_by_refusal_token(\core\PHPLibrary\SystemCore $system_core, string $token) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_delete();
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('refusal_token = :refusal_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':refusal_token', $token, \PDO::PARAM_STR);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }

    public static function delete_registration_submit_by_submit_token(\core\PHPLibrary\SystemCore $system_core, string $token) : bool {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_delete();
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_registration_submits');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('submit_token = :submit_token');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':submit_token', $token, \PDO::PARAM_STR);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }
  }
}

?>