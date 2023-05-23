<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\DatabaseManagementSystem as EnumDatabaseManagementSystem;
  use \PDO as PDO;

  final class Database {
    private string $database_name;
    private string $database_user;
    private string $database_password;
    private string $database_host;
    private EnumDatabaseManagementSystem $database_management_system;
    private PDO $connection;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(EnumDatabaseManagementSystem $database_management_system) {
      $this->set_database_management_system($database_management_system);
    }
    
    /**
     * Назначение наименования расширения SQL для работы с базой данных
     *
     * @param  mixed $system
     * @return void
     */
    private function set_database_management_system(EnumDatabaseManagementSystem $system) {
      $this->database_management_system = $system;
    }
    
    /**
     * Получение наименования расширения SQL для работы с базой данных
     *
     * @return EnumDatabaseManagementSystem
     */
    private function get_database_management_system() : EnumDatabaseManagementSystem {
      return $this->database_management_system;
    }
    
    /**
     * Назначить имя базы данных
     *
     * @param  mixed $value
     * @return void
     */
    public function set_database_name(string $value) : void {
      $this->database_name = $value;
    }
    
    /**
     * Назначить имя пользователя базы данных
     *
     * @param  mixed $value
     * @return void
     */
    public function set_database_user(string $value) : void {
      $this->database_user = $value;
    }
    
    /**
     * Назначить пароль доступа к базе данных
     *
     * @param  mixed $value
     * @return void
     */
    public function set_database_password(string $value) : void {
      $this->database_password = $value;
    }
    
    /**
     * Назначить хост базы данных
     *
     * @param  mixed $value
     * @return void
     */
    public function set_database_host(string $value) : void {
      $this->database_host = $value;
    }
    
    /**
     * Получить имя базы данных
     *
     * @return string
     */
    private function get_database_name() : string {
      return $this->database_name;
    }
    
    /**
     * Получить имя пользователя базы данных
     *
     * @return string
     */
    private function get_database_user() : string {
      return $this->database_user;
    }
    
    /**
     * Получить пароль базы данных
     *
     * @return string
     */
    private function get_database_password() : string {
      return $this->database_password;
    }
    
    /**
     * Получить хост базы данных
     *
     * @return string
     */
    private function get_database_host() : string {
      return $this->database_host;
    }

    public function connect() {
      /** @var string $database_name Наименование базы данных */
      $database_name = $this->get_database_name();
      /** @var string $database_user Пользователь базы данных */
      $database_user = $this->get_database_user();
      /** @var string $database_password Пароль базы данных */
      $database_password = $this->get_database_password();
      /** @var string $database_host Хост базы данных */
      $database_host = $this->get_database_host();
      
      /** @var EnumDatabaseManagementSystem $database_management_system */
      $database_management_system = $this->get_database_management_system();
      switch ($database_management_system->value) {
        case 'mysql': $database_connection_query = 'mysql:host=%s;dbname=%s'; break;
        case 'pgsql': $database_connection_query = 'pgsql:host=%s;dbname=%s'; break;
      }

      $database_connection_query_modified = sprintf($database_connection_query, $database_host, $database_name);

      try {
        $this->connection = new PDO($database_connection_query_modified, $database_user, $database_password);
      } catch (PDOException $exception) {
        echo $exception->getMessage();
      }
    }
  }

}

?>