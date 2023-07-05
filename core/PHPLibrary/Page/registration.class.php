<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageRegistration implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @param  Page $page
     * @return void
     */
    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }
    
    /**
     * Сборка шаблона страницы
     *
     * @return void
     */
    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/page/registration.css', 'rel' => 'stylesheet']);
      
      if (!$this->system_core->client->is_logged(1)) {
        $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
          'PAGE_NAME' => 'registration',
          'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/registration.tpl', [])
        ]);
      } else {
        http_response_code(503);

        $page_error = new PageError($this->system_core, $this->page, 503);
        $page_error->assembly();
        $this->assembled = $page_error->assembled;
      }
    }

  }

}

?>