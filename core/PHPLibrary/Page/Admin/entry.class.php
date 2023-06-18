<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageEntry implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/entry.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/nadvoTE.css', 'rel' => 'stylesheet']);

      $entry = null;
      if (!is_null($this->system_core->urlp->get_path(2))) {
        $entry_id = (is_numeric($this->system_core->urlp->get_path(2))) ? (int)$this->system_core->urlp->get_path(2) : 0;
        $entry = (Entry::exists_by_id($this->system_core, $entry_id)) ? new Entry($this->system_core, $entry_id) : null;
        
        if (!is_null($entry)) {
          $entry->init_data(['id', 'texts', 'name']);
        }
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'entry',
        'ENTRY_EDITOR' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry/editor.tpl', []),
        'ENTRY_ID' => (!is_null($entry)) ? $entry->get_id() : 0,
        'ENTRY_TITLE' => (!is_null($entry)) ? $entry->get_title() : '',
        'ENTRY_DESCRIPTION' => (!is_null($entry)) ? $entry->get_description() : '',
        'ENTRY_CONTENT' => (!is_null($entry)) ? $entry->get_content() : '',
        'ENTRY_NAME' => (!is_null($entry)) ? $entry->get_name() : '',
        'ENTRY_FORM_METHOD' => (!is_null($entry)) ? 'PATCH' : 'PUT'
      ]);
    }

  }

}

?>