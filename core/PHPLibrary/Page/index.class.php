<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageIndex implements InterfacePage {
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
      $this->system_core->template->add_style(['href' => 'styles/page/index.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'page.class.js', 'type' => 'module'], true);

      /** @var Entries $entries Объект класса Entries */
      $entries = new Entries($this->system_core);
      $entries_array_objects = $entries->get_all(['limit' => [6, 0]]);
      unset($entries);

      $entries_array_templates = [];
      foreach ($entries_array_objects as $entry_object) {
        $entry_object->init_data(['id', 'texts', 'metadata', 'name', 'created_unix_timestamp']);

        if ($entry_object->is_published()) {
          array_push($entries_array_templates, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/entriesList/item.tpl', [
            'ENTRY_ID' => $entry_object->get_id(),
            'ENTRY_TITLE' => $entry_object->get_title(),
            'ENTRY_DESCRIPTION' => $entry_object->get_description(),
            'ENTRY_URL' => $entry_object->get_url(),
            'ENTRY_PREVIEW_URL' => ($entry_object->get_preview_url() != '') ? $entry_object->get_preview_url() : Entry::get_preview_default_url($this->system_core, 512),
            'ENTRY_CREATED_DATE_TIMESTAMP' => date('d.m.Y H:i', $entry_object->get_created_unix_timestamp())
          ]));
        }

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