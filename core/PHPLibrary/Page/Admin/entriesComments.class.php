<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page\Admin {
  use \DOMDocument as DOMDocument;
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\EntryComments as EntryComments;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageEntriesComments implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/entriesComments.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/entriesComments.js', 'type' => 'module'], true);

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
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => ''
      ]));
      array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
        'NAVIGATION_ITEM_TITLE' => 'Комментарии',
        'NAVIGATION_ITEM_URL' => '/admin/entriesComments',
        'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => 'navigation-item__link_is-active'
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

      $entries_instance = new Entries($this->system_core);
      $entries_array = $entries_instance->get_all();
      
      $entries_comments_array = [];
      if (!empty($entries_array)) {
        foreach ($entries_array as $entry) {
          $entry_comments = $entry->get_comments();
          if (!empty($entry_comments)) {
            foreach ($entry_comments as $comment) {
              $comment->init_data(['content', 'created_unix_timestamp', 'updated_unix_timestamp', 'metadata']);
              array_push($entries_comments_array, $comment);
            }
          }
        }
      }

      if (!empty($entries_comments_array)) {
        usort($entries_comments_array, function ($a, $b) {
          $a_created_unix_timestamp = $a->get_created_unix_timestamp();
          $b_created_unix_timestamp = $b->get_created_unix_timestamp();

          if ($a_created_unix_timestamp != $b_created_unix_timestamp) {
            return ($a_created_unix_timestamp > $b_created_unix_timestamp) ? -1 : 1;
          }

          return 0;
        });
      }
      
      $comments_table_items_assembled = [];
      if (!empty($entries_comments_array)) {
        foreach ($entries_comments_array as $comment_index => $comment) {
          $created_date_timestamp = date('d.m.Y H:i:s', $comment->get_created_unix_timestamp());
          $updated_date_timestamp = date('d.m.Y H:i:s', $comment->get_updated_unix_timestamp());

          array_push($comments_table_items_assembled, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesComments/tableItem.tpl', [
            'COMMENT_ID' => $comment->get_id(),
            'COMMENT_IS_HIDDEN_STATUS' => ($comment->is_hidden()) ? 'true' : 'false',
            'COMMENT_HIDDEN_REASON' => $comment->get_hidden_reason(),
            'COMMENT_INDEX' => $comment_index + 1,
            'COMMENT_CONTENT' => $comment->get_content(),
            'COMMENT_CREATED_DATE_TIMESTAMP' => $created_date_timestamp,
            'COMMENT_UPDATED_DATE_TIMESTAMP' => $updated_date_timestamp
          ]));
        }
      }

      $template_comments_table = (!empty($entries_array)) ? TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesComments/table.tpl', [
        'ADMIN_PANEL_COMMENTS_TABLE_ITEMS' => implode($comments_table_items_assembled)
      ]) : 'Комментарии отсутствуют.';

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entriesComments.tpl', [
        'PAGE_NAVIGATION' => $page_navigation_transformed,
        'ADMIN_PANEL_PAGE_NAME' => 'comments',
        'ADMIN_PANEL_COMMENTS_TABLE' => $template_comments_table
      ]);
    }

  }

}

?>