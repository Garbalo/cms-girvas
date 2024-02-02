<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page\Admin {
  use \DOMDocument as DOMDocument;
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\EntriesCategories as EntriesCategories;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageEntriesCategories implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/entriesCategories.css', 'rel' => 'stylesheet']);
      
      $navigations_items_transformed = [];
      array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
        'NAVIGATION_ITEM_TITLE' => '< Главная',
        'NAVIGATION_ITEM_URL' => '/admin',
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => ''
      ]));
      array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
        'NAVIGATION_ITEM_TITLE' => 'Записи',
        'NAVIGATION_ITEM_URL' => '/admin/entries',
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => ''
      ]));
      array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
        'NAVIGATION_ITEM_TITLE' => 'Категории',
        'NAVIGATION_ITEM_URL' => '/admin/entriesCategories',
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => 'navigation-item__link_is-active'
      ]));
      array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
        'NAVIGATION_ITEM_TITLE' => 'Комментарии',
        'NAVIGATION_ITEM_URL' => '/admin/entriesComments',
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => ''
      ]));

      if (!empty($navigations_items_transformed)) {
        $page_navigation_transformed = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal.tpl', [
          'NAVIGATION_LIST' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/list.tpl', [
            'NAVIGATION_ITEMS' => implode($navigations_items_transformed)
          ])
        ]);
      } else {
        $page_navigation_transformed = '';
      }

      $entries_categories_table_items_assembled = [];
      $entries_categories = new EntriesCategories($this->system_core);
      $entries_categories_array_objects = $entries_categories->get_all();
      unset($entries_categories);

      foreach ($entries_categories_array_objects as $entries_category_index => $entries_category_object) {
        $entries_category_object->init_data(['id', 'texts', 'name', 'created_unix_timestamp', 'updated_unix_timestamp', 'parent_id']);

        $created_date_timestamp = date('d.m.Y H:i:s', $entries_category_object->get_created_unix_timestamp());
        $updated_date_timestamp = date('d.m.Y H:i:s', $entries_category_object->get_updated_unix_timestamp());

        array_push($entries_categories_table_items_assembled, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesCategories/tableItem.tpl', [
          'ENTRIES_CATEGORY_ID' => $entries_category_object->get_id(),
          'ENTRIES_CATEGORY_INDEX' => $entries_category_index + 1,
          'ENTRIES_CATEGORY_TITLE' => $entries_category_object->get_title(),
          'ENTRIES_CATEGORY_URL' => $entries_category_object->get_url(),
          'ENTRIES_CATEGORY_CREATED_DATE_TIMESTAMP' => $created_date_timestamp,
          'ENTRIES_CATEGORY_UPDATED_DATE_TIMESTAMP' => $updated_date_timestamp
        ]));
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesCategories.tpl', [
        'PAGE_NAVIGATION' => $page_navigation_transformed,
        'ADMIN_PANEL_PAGE_NAME' => 'entries-categories',
        'ADMIN_PANEL_ENTRIES_CATEGORIES_TABLE' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesCategories/table.tpl', [
          'ADMIN_PANEL_ENTRIES_CATEGORIES_TABLE_ITEMS' => implode($entries_categories_table_items_assembled)
        ])
      ]);
    }

  }

}

?>