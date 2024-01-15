<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\SystemCore {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\SystemCore as SystemCore;

  if (!defined('IS_NOT_HACKED')) {
		die('Unauthorized access attempt detected!');
	}

  final class Reports {
  private readonly SystemCore $system_core;

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
     * Получить все объекты отчетов
     *
     * @param  array $params_array
     * @return array
     */
    public function get_all(array $params_array = []) : array {
      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('reports');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_order_by();
      $query_builder->statement->clause_order_by->set_column('created_unix_timestamp');
      $query_builder->statement->clause_order_by->set_sort_type('DESC');
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

      $result_array = [];
      $results = $database_query->fetchAll(\PDO::FETCH_ASSOC);
      if ($results) {
        foreach ($results as $data) {
          array_push($result_array, new Report($this->system_core, $data['id']));
        }
      }

      return $result_array;
    }

    /**
     * Получить объекты отчетов определенного типа
     *
     * @param  int $type_id
     * @param  array $params_array
     * @return array
     */
    public function get_by_type_ids(array $type_ids, array $params_array = []) : array {
      $condition_type_ids = [];
      foreach ($type_ids as $type_id) {
        array_push($condition_type_ids, sprintf('(metadata::jsonb->>\'typeID\')::int = %d', $type_id));
      }

      $query_builder = new DatabaseQueryBuilder();
      $query_builder->set_statement_select();
      $query_builder->statement->add_selections(['id']);
      $query_builder->statement->set_clause_from();
      $query_builder->statement->clause_from->add_table('reports');
      $query_builder->statement->clause_from->assembly();
      $query_builder->statement->set_clause_where();
      $query_builder->statement->clause_where->add_condition(implode(' OR ', $condition_type_ids));
      $query_builder->statement->clause_where->assembly();
      $query_builder->statement->set_clause_order_by();
      $query_builder->statement->clause_order_by->set_column('created_unix_timestamp');
      $query_builder->statement->clause_order_by->set_sort_type('DESC');
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
			$database_query->execute();

      $result_array = [];
      $results = $database_query->fetchAll(\PDO::FETCH_ASSOC);
      if ($results) {
        foreach ($results as $data) {
          array_push($result_array, new Report($this->system_core, $data['id']));
        }
      }

      return $result_array;
    }
  }

}

?>