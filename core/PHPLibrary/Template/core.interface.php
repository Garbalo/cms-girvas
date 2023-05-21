<?php

namespace core\PHPLibrary\Template {

  interface InterfaceCore {
    public function __construct(\core\PHPLibrary\Template $template_base);
    public function assembly() : void;
    public function assembly_document() : string;
    public function assembly_header() : string;
    public function assembly_main() : string;
    public function assembly_footer() : string;
  }

}

?>