<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  final class Entries {
    private SystemCore $system_core;
    
    /**
     * __construct
     *
     * @param  mixed $system_core
     * @return void
     */
    public function __construct(SystemCore $system_core) {
      $this->system_core = $system_core;
    }
    
    public function get_all() : array {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      //$this->system_core->database_connector->database->bindParam(':id', $entry_id, \PDO::PARAM_INT);
			$database_query->execute();

      $entries = [];
      $results = $database_query->fetchAll(\PDO::FETCH_ASSOC);
      if ($results) {
        foreach ($results as $data) {
          array_push($entries, new Entry($this->system_core, $data['id']));
        }
      }

      return $entries;
    }

  }

}

?>