<?php

namespace core\PHPLibrary\Database {
  
  enum DatabaseManagementSystem : string {
    case MySQL = 'mysql';
    case PostgreSQL = 'pgsql';
  }

}

?>