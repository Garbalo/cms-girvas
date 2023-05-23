<?php

namespace core\PHPLibrary\SystemCore {

  final class DatabaseConnector {
    private mixed $system_core = null;
    public \core\PHPLibrary\Database $database;
    
    /**
     * __construct
     *
     * @param  mixed $system_core
     * @return void
     */
    public function __construct(\core\PHPLibrary\SystemCore $system_core, Configurator $configurator) {
      $this->system_core = $system_core;

      $database_configurations = $configurator->get('database');
      $this->database = new \core\PHPLibrary\Database(\core\PHPLibrary\Database\DatabaseManagementSystem::PostgreSQL);
      $this->database->set_database_name($database_configurations['name']);
      $this->database->set_database_user($database_configurations['user']);
      $this->database->set_database_host($database_configurations['host']);
      $this->database->set_database_password($database_configurations['password']);
      $this->database->connect();
    }

  }

}

?>