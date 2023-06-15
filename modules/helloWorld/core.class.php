<?php

/**
 * Тестовый модуль "HelloWorld"
 * 
 * @author Drelagas (Andrey Shestakov)
 */

namespace modules\helloWorld {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Module as Module;
  
  /**
   * Ядро модуля
   */
  class Core extends Module {
    public array $global_template_tags = [];

    public function __construct(SystemCore $system_core, string $name) {
      parent::__construct($system_core, $name);

      $this->global_template_tags['HELLO_WORLD'] = 'Hello, world!';
    }
  }
}

?>