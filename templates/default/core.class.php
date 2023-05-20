<?php

namespace templates\default {

  class Core implements \core\PHPLibrary\Template\InterfaceCore {
    private \core\PHPLibrary\Template $template_base;

    public function __construct(\core\PHPLibrary\Template $template_base) {
      $this->template_base = $template_base;
    }

    public function assembly_header() : string {

    }

    public function assembly_main() : string {
      
    }

    public function assembly_footer() : string {
      
    }

    public function assembly_document() : string {
      
    }

    public function assembly() : string {
      
    }

  }

}

?>