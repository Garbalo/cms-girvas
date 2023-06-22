<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageEntries implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/entries.css', 'rel' => 'stylesheet']);
      
      $this->system_core->template->add_script(['src' => 'admin/page/entries.js'], true);

      $entries_table_items_assembled_array = [];
      $entries = new Entries($this->system_core);
      $entries_array_objects = $entries->get_all();
      unset($entries);

      $entry_number = 1;
      foreach ($entries_array_objects as $entry_object) {
        $entry_object->init_data(['id', 'texts', 'name', 'created_unix_timestamp', 'updated_unix_timestamp']);

        $entry_created_date_timestamp = date('d.m.Y H:i:s', $entry_object->get_created_unix_timestamp());
        $entry_updated_date_timestamp = date('d.m.Y H:i:s', $entry_object->get_updated_unix_timestamp());

        array_push($entries_table_items_assembled_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries/tableItem.tpl', [
          'ENTRY_ID' => $entry_object->get_id(),
          'ENTRY_INDEX' => $entry_number,
          'ENTRY_TITLE' => $entry_object->get_title(),
          'ENTRY_DESCRIPTION' => $entry_object->get_description(),
          'ENTRY_URL' => $entry_object->get_url(),
          'ENTRY_CREATED_DATE_TIMESTAMP' => $entry_created_date_timestamp,
          'ENTRY_UPDATED_DATE_TIMESTAMP' => $entry_updated_date_timestamp
        ]));

        $entry_number++;
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'entries',
        'ADMIN_PANEL_ENTRIES_TABLE' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries/table.tpl', [
          'ADMIN_PANEL_ENTRIES_TABLE_ITEMS' => implode($entries_table_items_assembled_array)
        ])
      ]);
    }

  }

}

?>