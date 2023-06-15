<?php

namespace core\PHPLibrary {
  class Module {
    private readonly SystemCore $system_core;
    private string $name;

    public function __construct(SystemCore $system_core, string $name) {
      $this->system_core = $system_core;
      $this->set_name($name);
    }

    public function get_name() : string {
      return $this->name;
    }

    protected function set_name(string $value) : void {
      $this->name = $value;
    }

    public static function connect_core(SystemCore $system_core, string $name) : bool {
      $core_path = self::get_core_path($name);
      $core_class = self::get_core_class($name);

      if (file_exists($core_path)) {
        require_once($core_path);
        $system_core->modules[$name] = new $core_class($system_core, $name);

        return true;
      }

      return false;
    }

    private static function get_core_path($name) : string {
      return sprintf('%s/modules/%s/core.class.php', CMS_ROOT_DIRECTORY, $name);
    }

    private static function get_core_class($name) : string {
      return sprintf('\\modules\\%s\\Core', $name);
    }
  }
}

?>