<?php

namespace core\PHPLibrary\WebChannel {
  use \DOMDocument as DOMDocument;
  use \SimpleXMLElement as SimpleXMLElement;
  use \core\PHPLibrary\SystemCore as SystemCore;

  final class Importer {
    public string $web_channel_link = '';

    public function __construct(SystemCore $system_core, string $web_channel_link) {
      $this->set_web_channel_link($web_channel_link);
    }

    public function get() : SimpleXMLElement|bool {
      $web_channel_link = $this->get_web_channel_link();
      return @simplexml_load_file($web_channel_link);
    }

    private function set_web_channel_link(string $link) : void {
      $this->web_channel_link = $link;
    }

    public function get_web_channel_link() : string {
      return $this->web_channel_link;
    }

  }
}

?>