<?php

/**
 * Тестовый модуль "HelloWorld"
 * 
 * @author Drelagas (Andrey Shestakov)
 */

namespace modules\helloWorld {
  use \core\PHPLibrary\Module as Module;
  use \core\PHPLibrary\SystemCore as SystemCore;
  
  /**
   * Ядро модуля
   */
  class Core {
    public Module $module;
    public SystemCore $system_core;
    public string $name = '';

    public function __construct(SystemCore $system_core, Module $module) {
      $this->system_core = $system_core;
      $this->module = $module;
    }

    public function preparation() : void {
      
    }

    public function init() : void {
      if (!is_null($this->system_core->template)) {
        $this->system_core->template->add_global_variable('F4E267_HELLO_WORLD', 'Привет, мир!');
      }
    }

    public function install() : bool {
      return $this->module->install();
    }

    public function enable() : bool {
      return $this->module->enable();
    }
  }
}

?>