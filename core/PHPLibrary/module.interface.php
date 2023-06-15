<?php

namespace core\PHPLibrary {
  interface InterfaceModule {
    public function __construct(SystemCore $system_core);
    public function get_status() : bool;
  }
}

?>