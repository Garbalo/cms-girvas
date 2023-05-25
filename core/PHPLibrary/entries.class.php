<?php

namespace core\PHPLibrary {

  final class Entries {
    private SystemCore $system_core;
    private array $array = [];
    public Entries\Database $database;
    
    /**
     * __construct
     *
     * @param  mixed $system_core
     * @return void
     */
    public function __construct(SystemCore $system_core) {
      $this->system_core = $system_core;
      $this->database = new Entries\Database($this->system_core->database_connector->database, $this);
    }

    
    
    /**
     * Установить значение для массива записей
     *
     * @param  mixed $array
     * @return void
     */
    public function set_array(array $array) : void {
      $this->array = $array;
    }
    
    /**
     * Получить список записей
     *
     * @return array
     */
    public function get_array() : array {
      return $this->array;
    }

  }

}

?>