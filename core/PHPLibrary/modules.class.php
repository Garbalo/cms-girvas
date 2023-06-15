<?php

namespace core\PHPLibrary {
  class Modules {
    public static function get_enabled() : array {
      return json_decode(self::get_file_enabled_content(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private static function get_file_enabled_content() {
      $file_path = sprintf('%s/modules/enabled.json', CMS_ROOT_DIRECTORY);
      return file_get_contents($file_path);
    }
  }
}

?>