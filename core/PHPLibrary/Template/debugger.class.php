<?php

namespace core\PHPLibrary\Template {

  final class Debugger {
    private \core\PHPLibrary\Template $template;

    public function __construct(\core\PHPLibrary\Template $template) {
      $this->template = $template;
    }
  }
}

?>