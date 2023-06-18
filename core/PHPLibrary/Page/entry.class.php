<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Page\Entry\PageError as PageError;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageEntry implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      if (!is_null($this->system_core->urlp->get_path(1))) {
        $entry_name = $this->system_core->urlp->get_path(1);

        if (Entry::exists_by_name($this->system_core, $entry_name)) {
          http_response_code(200);

          $this->system_core->template->add_style(['href' => 'styles/page/entry.css', 'rel' => 'stylesheet']);
          
          $entry = Entry::get_by_name($this->system_core, $entry_name);
          $entry->init_data(['id', 'category_id', 'texts', 'name']);

          $parsedown = new Parsedown();

          $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
            'PAGE_NAME' => 'entry',
            'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry.tpl', [
              'ENTRY_TITLE' => $entry->get_title(),
              'ENTRY_CONTENT' => $parsedown->text($entry->get_content())
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