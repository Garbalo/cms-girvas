<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageIndex implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/index.css', 'rel' => 'stylesheet']);

      /** @var Entries $entries Объект класса Entries */
      $entries = new Entries($this->system_core);
      $entries_array_objects = $entries->get_all(['limit' => [6, 0]]);
      unset($entries);

      $entries_array_templates = [];
      foreach ($entries_array_objects as $entry_object) {
        $entry_object->init_data(['id', 'texts', 'name']);

        array_push($entries_array_templates, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/entriesList/item.tpl', [
          'ENTRY_ID' => $entry_object->get_id(),
          'ENTRY_TITLE' => $entry_object->get_title(),
          'ENTRY_DESCRIPTION' => $entry_object->get_description(),
          'ENTRY_URL' => $entry_object->get_url(),
        ]));

        unset($entry_data);
      }

      unset($entries_array_objects);

      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
        'PAGE_NAME' => 'index',
        'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index.tpl', [
          'ENTRIES_LIST' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/entriesList/list.tpl', [
            'ENTRIES_LIST_ITEMS' => implode($entries_array_templates)
          ])
        ])
      ]);

      unset($entries_array_templates);
    }

  }

}

?>