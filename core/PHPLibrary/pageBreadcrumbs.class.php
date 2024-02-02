<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary {
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  final class PageBreadcrumbs {
    private SystemCore $system_core;
    private array $array = [];
    public string $assembled = '';

    public function __construct(SystemCore $system_core) {
      $this->system_core = $system_core;
    }

    public function add(string $title, string $url) : bool {
      $array_items_count_start = count($this->array);
      $array_items_count = array_push($this->array, [
        'title' => $title,
        'url' => $url
      ]);

      if ($array_items_count > $array_items_count_start) {
        return true;
      }

      return false;
    }

    public function get_array() : array {
      return $this->array;
    }

    public function assembly() : void {
      $breadcrumbs_items_transformed = [];
      if (count($this->get_array()) > 0) {
        foreach ($this->get_array() as $breadcrumb_index => $breadcrumb_data) {
          array_push($breadcrumbs_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/breadcrumps/listItem.tpl', [
            'BREADCRUMP_URL' => $breadcrumb_data['url'],
            'BREADCRUMP_TITLE' => $breadcrumb_data['title'],
            'BREADCRUMP_META_POSITION' => $breadcrumb_index + 1
          ]));
        }
      }

      $breadcrumbs_list_transformed = '';
      if (count($breadcrumbs_items_transformed) > 0) {
        $breadcrumbs_list_transformed = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/breadcrumps/list.tpl', [
          'BREADCRUMPS_ITEMS' => implode($breadcrumbs_items_transformed)
        ]);
      }

      $this->assembled = $breadcrumbs_list_transformed;
    }

  }

}

?>