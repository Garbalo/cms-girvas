<?php

namespace core\PHPLibrary\Database\QueryBuilder\StatementSelect\ClauseFrom {

  class Table {
    private string $name;
    
    /**
     * __construct
     *
     * @param  mixed $table_name
     * @return void
     */
    public function __construct(string $table_name) {
      $this->set_name($table_name);
    }
    
    /**
     * set_name
     *
     * @param  mixed $value
     * @return void
     */
    private function set_name(string $value) : void {
      $this->name = $value;
    }
    
    /**
     * get_name
     *
     * @return string
     */
    public function get_name() : string {
      return $this->name;
    }

  }

}
?>