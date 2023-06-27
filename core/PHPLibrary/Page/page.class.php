<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\PageStatic as PageStatic;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PagePage implements InterfacePage {
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

      if (!is_null($this->system_core->urlp->get_path(1))) {
        $page_static_name = urldecode($this->system_core->urlp->get_path(1));

        if (PageStatic::exists_by_name($this->system_core, $page_static_name)) {
          http_response_code(200);

          $page_static = PageStatic::get_by_name($this->system_core, $page_static_name);
          $page_static->init_data(['id', 'texts', 'name']);

          $parsedown = new Parsedown();

          $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
            'PAGE_NAME' => 'static',
            'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/static.tpl', [
              'PAGE_TITLE' => $page_static->get_title(),
              'PAGE_CONTENT' => $parsedown->text($page_static->get_content())
            ])
          ]);
        } else {
          http_response_code(404);

          $page_error = new PageError($this->system_core, $this->page, 404);
          $page_error->assembly();
          $this->assembled = $page_error->assembled;
        }
      } else {
        http_response_code(404);

        $page_error = new PageError($this->system_core, $this->page, 404);
        $page_error->assembly();
        $this->assembled = $page_error->assembled;
      }
    }

  }

}

?>