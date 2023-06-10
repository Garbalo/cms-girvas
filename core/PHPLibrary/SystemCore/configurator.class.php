<?php

namespace core\PHPLibrary\SystemCore {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\SystemCore as SystemCore;

  final class Configurator {
    const FILE_PATH = 'core/configuration.php';

    private array $data = [];
    private SystemCore $system_core;
    
    /**
     * __construct
     *
     * @param  mixed $system_core
     * @return void
     */
    public function __construct(SystemCore $system_core) {
      $this->system_core = $system_core;

      if (file_exists(sprintf('%s/%s', CMS_ROOT_DIRECTORY, self::FILE_PATH))) {
        $this->merge($this->get_file_data());
      } else {
        die('Configurations CMS file is not exists!');
      }
    }
    
    /**
     * Объединить данные для конфигураций CMS
     *
     * @param  mixed $data
     * @return void
     */
    private function merge(array $data) : void {
      $this->data = array_merge($this->data, $data);
    }
    /**
     * Получить данные конфигурации CMS из базы данных
     *
     * @return array
     */
    public function get_database_entry_value(string $name) : mixed {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['value']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('configurations');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('name = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $name, \PDO::PARAM_STR);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result['value'] : null;
    }

    public function update_database_entry_value(string $name, string|int $value) : mixed {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_update();
      $query_builder->statement->set_table('configurations');
      $query_builder->statement->set_clause_set();
      $query_builder->statement->clause_set->add_column('value');
      $query_builder->statement->clause_set->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('name = :name');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':name', $name, \PDO::PARAM_STR);
      $database_query->bindParam(':value', $value, \PDO::PARAM_STR);
			$execute = $database_query->execute();

      return ($execute) ? true : false;
    }
    
    /**
     * Получить данные файла-конфигурации CMS
     *
     * @return array
     */
    private function get_file_data() : array {
      require_once(sprintf('%s/%s', CMS_ROOT_DIRECTORY, self::FILE_PATH));
      return (isset($configuration)) ? $configuration : [];
    }
    
    /**
     * Назначение отдельного параметра конфигурации CMS
     *
     * @param  mixed $configuration_name
     * @param  mixed $configuration_value
     * @return void
     */
    public function set(string $configuration_name, mixed $configuration_value) : void {
      $this->data[$configuration_name] = $configuration_value;
    }

    /**
     * Получение отдельного параметра конфигураций CMS
     *
     * @param  mixed $configuration_name Наименование конфигурации
     * @return mixed
     */
    public function get(string $configuration_name) : mixed {
      return (array_key_exists($configuration_name, $this->data)) ? $this->data[$configuration_name] : null;
    }
  }

}

?>