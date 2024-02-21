<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Template as Template;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageTemplate implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/template.css', 'rel' => 'stylesheet']);

      $locale_data = $this->system_core->locale->get_data();
      $template_name = $this->system_core->urlp->get_path(2);
      $template = new Template($this->system_core, $template_name);

      if ($template->exists_file_metadata_json()) {
        $template_screenshots_list_items = [];
        $template_screenshots_files_array = $template->get_screenshots_array();
        if (count($template_screenshots_files_array) > 0) {
          $template_screenshots_url = $template->get_screenshots_url();
          foreach ($template_screenshots_files_array as $screenshot_file) {
            array_push($template_screenshots_list_items, TemplateCollector::assembly('<li class="gallery__item"><img class="gallery__item-image" src="{TEMPLATE_SCREENSHOT_URL}"></li>', [
              'TEMPLATE_SCREENSHOT_URL' => sprintf('%s/%s', $template_screenshots_url, $screenshot_file)
            ]));
          }
        }

        if (count($template_screenshots_files_array) > 0) {
          $template_gallery_list = TemplateCollector::assembly('<ul class="gallery__list list-reset">{TEMPLATE_GALLARY_LIST_ITEMS}</ul>', [
            'TEMPLATE_GALLARY_LIST_ITEMS' => implode($template_screenshots_list_items)
          ]);
        } else {
          $template_gallery_list = '';
        }

        $allowed_template_metadata = ['authorName'];
        $template_metadata = $template->get_metadata();
        $template_metadata_items_transformed = [];
        foreach ($template_metadata as $metadata_name => $metadata_value) {
          if (in_array($metadata_name, $allowed_template_metadata)) {
            $metadata_title = $metadata_name;
            switch ($metadata_name) {
              case 'authorName': $metadata_title = $locale_data['PAGE_TEMPLATE_AUTHOR_LABEL']; break;
            }

            array_push($template_metadata_items_transformed, TemplateCollector::assembly('<li class="template__metadata-item"><b>{METADATA_TITLE}:</b> {METADATA_VALUE}</li>', [
              'METADATA_TITLE' => $metadata_title,
              'METADATA_VALUE' => $metadata_value
            ]));
          }
        }

        if (count($template_metadata_items_transformed) > 0) {
          $template_metadata_list_transformed = TemplateCollector::assembly('<ul class="template__metadata-list list-reset">{METADATA_LIST}</ul>', [
            'METADATA_LIST' => implode($template_metadata_items_transformed)
          ]);
        } else {
          $template_metadata_list_transformed = $locale_data['PAGE_TEMPLATE_METADATA_BLOCK_METADATA_NOT_FOUND_TITLE'];
        }

        $parsedown = new Parsedown();

        $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/template.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'template',
          'TEMPLATE_TITLE' => $template->get_title(),
          'TEMPLATE_DESCRIPTION' => $parsedown->text($template->get_description()),
          'TEMPLATE_GALLARY_LIST' => $template_gallery_list,
          'TEMPLATE_METADATA_LIST' => $template_metadata_list_transformed
        ]);
      } else {
        $this->assembled = $locale_data['PAGE_TEMPLATE_TEMPLATE_NOT_FOUND_TITLE'];
      }
    }
  }
}

?>