<?php

namespace core\PHPLibrary\Database\QueryBuilder {
  use \core\PHPLibrary\Database\QueryBuilder as QueryBuilder;
  
  interface InterfaceStatement {
    public function __construct(QueryBuilder $query_builder);
    public function assembly() : void;
  }

}

?>