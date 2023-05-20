<?php

namespace core\PHPLibrary\SystemCore {

  interface InterfaceFileConnector {
    public function __construct(\core\PHPLibrary\SystemCore $system_core);
    public function set_current_directory(string $directory) : void;
    public function get_current_directory() : string;
    public function connect_file(string $file_name) : bool;
  }

}

?>