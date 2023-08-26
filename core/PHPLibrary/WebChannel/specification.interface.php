<?php

namespace core\PHPLibrary\WebChannel {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\WebChannel\Builder as WebChannelBuilder;

  interface InterfaceSpecification {
    public function __construct(SystemCore $system_core, WebChannelBuilder $web_channel_builder);
    public function set_title(string $value) : void;
    public function set_description(string $value) : void;
    public function set_language(string $value) : void;
    public function set_link(string $value) : void;
    public function add_item(array $item) : void;
    public function get_title() : string;
    public function get_description() : string;
    public function get_language() : string;
    public function get_link() : string;
    public function get_items() : array;
  }
}

?>