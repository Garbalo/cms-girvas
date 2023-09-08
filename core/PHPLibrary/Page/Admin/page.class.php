<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */


namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\PageStatic as PageStatic;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PagePage implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/pageStatic.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/nadvoTE.css', 'rel' => 'stylesheet']);

      $page_static = null;
      if (!is_null($this->system_core->urlp->get_path(2))) {
        $page_static_id = (is_numeric($this->system_core->urlp->get_path(2))) ? (int)$this->system_core->urlp->get_path(2) : 0;
        $page_static = (PageStatic::exists_by_id($this->system_core, $page_static_id)) ? new PageStatic($this->system_core, $page_static_id) : null;
        
        if (!is_null($page_static)) {
          $page_static->init_data(['id', 'texts', 'name']);
        }
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/pageStatic.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'page-static',
        'PAGE_STATIC_EDITOR' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/pageStatic/editor.tpl', []),
        'PAGE_STATIC_ID' => (!is_null($page_static)) ? $page_static->get_id() : 0,
        'PAGE_STATIC_TITLE' => (!is_null($page_static)) ? $page_static->get_title() : '',
        'PAGE_STATIC_DESCRIPTION' => (!is_null($page_static)) ? $page_static->get_description() : '',
        'PAGE_STATIC_CONTENT' => (!is_null($page_static)) ? $page_static->get_content() : '',
        'PAGE_STATIC_NAME' => (!is_null($page_static)) ? $page_static->get_name() : '',
        'PAGE_STATIC_FORM_METHOD' => (!is_null($page_static)) ? 'PATCH' : 'PUT'
      ]);
    }

  }

}

?>