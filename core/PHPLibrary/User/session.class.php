<?php

namespace core\PHPLibrary\User {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  #[\AllowDynamicProperties]
  class Session {
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
     * Проверка существования сессии по IP-адресу
     *
     * @param  mixed $user_ip
     * @return void
     */
    public static function exists_by_ip(SystemCore $system_core, string $user_ip) {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['1']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('users_sessions');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('user_ip = :user_ip');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_limit(1);
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':user_ip', $user_ip, \PDO::PARAM_STR);
			$database_query->execute();

      return ($database_query->fetchColumn()) ? true : false;
    }
    
    /**
     * Создание записи нового пользователя в базе данных
     *
     * @param  SystemCore $system_core
     * @param  array $session_data (user_id, token, user_ip, type_id)
     * @return User
     */
    public static function create(SystemCore $system_core, array $session_data = []) : User|null {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_insert();
      $query_builder->statement->set_table('users_sessions');
      $query_builder->statement->add_column('user_id');
      $query_builder->statement->add_column('token');
      $query_builder->statement->add_column('user_ip');
      $query_builder->statement->add_column('type_id');
      $query_builder->statement->set_clause_returning();
      $query_builder->statement->clause_returning->add_column('id');
      $query_builder->statement->assembly();

      $database_connection = $system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
      $database_query->bindParam(':token', $token, \PDO::PARAM_STR);
      $database_query->bindParam(':user_ip', $user_ip, \PDO::PARAM_STR);
      $database_query->bindParam(':type_id', $type_id, \PDO::PARAM_INT);
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