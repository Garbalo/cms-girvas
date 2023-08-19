<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;

  final class EntryComments {
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
        
    /**
     * Получить все объекты комментариев
     *
     * @param  array $params_array
     * @return array
     */
    public function get_all(array $params_array = []) : array {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
      $query_builder->statement->clause_from->assembly();
      if (array_key_exists('limit', $params_array)) {
        if (is_array($params_array['limit'])) {
          $limit = (is_integer($params_array['limit'][0])) ? $params_array['limit'][0] : 0;
          $offset = (is_integer($params_array['limit'][1])) ? $params_array['limit'][1] : 0;
          $query_builder->statement->set_clause_limit($limit, $offset);
        }
      }
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      //$this->system_core->database_connector->database->bindParam(':id', $entry_id, \PDO::PARAM_INT);
			$database_query->execute();

      $entries_comments = [];
      $results = $database_query->fetchAll(\PDO::FETCH_ASSOC);
      if ($results) {
        foreach ($results as $data) {
          array_push($entries_comments, new EntryComment($this->system_core, $data['id']));
        }
      }

      return $entries_comments;
    }
        
    /**
     * Получить объекты комментариев для определенной записи
     *
     * @param  int $entry_id
     * @param  array $params_array
     * @return array
     */
    public function get_by_entry_id(int $entry_id, array $params_array = []) : array {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('entry_id = :entry_id');
      $query_builder->statement->clause_where->assembly();
      if (array_key_exists('limit', $params_array)) {
        if (is_array($params_array['limit'])) {
          $limit = (is_integer($params_array['limit'][0])) ? $params_array['limit'][0] : 0;
          $offset = (is_integer($params_array['limit'][1])) ? $params_array['limit'][1] : 0;
          $query_builder->statement->set_clause_limit($limit, $offset);
        }
      }
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':entry_id', $entry_id, \PDO::PARAM_INT);
			$database_query->execute();

      $entries_comments = [];
      $results = $database_query->fetchAll(\PDO::FETCH_ASSOC);
      if ($results) {
        foreach ($results as $data) {
          array_push($entries_comments, new EntryComment($this->system_core, $data['id']));
        }
      }

      return $entries_comments;
    }
        
    /**
     * Получить количество комментариев для определенной записи
     *
     * @param  int $entry_id
     * @return int
     */
    public function get_count_by_entry_id(int $entry_id) : int {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['count(*)']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition('entry_id = :entry_id');
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
      $database_query->bindParam(':entry_id', $entry_id, \PDO::PARAM_INT);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result['count'] : 0;
    }
        
    /**
     * Получить общее количество комментариев
     *
     * @return int
     */
    public function get_count_total() : int {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['count(*)']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('entries_comments');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->assembly();

      $database_connection = $this->system_core->database_connector->database->connection;
      $database_query = $database_connection->prepare($query_builder->statement->assembled);
			$database_query->execute();

      $result = $database_query->fetch(\PDO::FETCH_ASSOC);
      return ($result) ? $result['count'] : 0;
    }


  }
}

?>