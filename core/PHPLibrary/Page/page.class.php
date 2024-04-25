<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\PageStatic as PageStatic;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\SystemCore\Locale as SystemCoreLocale;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PagePage implements InterfacePage {
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

      $cms_base_locale_setted_name = $this->system_core->configurator->get_database_entry_value('base_locale');
      $url_base_locale_setted_name = $this->system_core->urlp->get_param('locale');
      $cookie_base_locale_setted_name = (isset($_COOKIE['locale'])) ? $_COOKIE['locale'] : null;
      
      $cms_base_locale_name = (!is_null($url_base_locale_setted_name)) ? $url_base_locale_setted_name : $cookie_base_locale_setted_name;
      $cms_base_locale_name = (!is_null($cms_base_locale_name)) ? $cms_base_locale_name : $cms_base_locale_setted_name;
      $cms_base_locale = new SystemCoreLocale($this->system_core, $cms_base_locale_name);
      if (!$cms_base_locale->exists_file_data_json()) {
        $cms_base_locale = new SystemCoreLocale($this->system_core, $cms_base_locale_setted_name);
        $cms_base_locale_name = $cms_base_locale_setted_name;
      }

      $this->system_core->locale = $cms_base_locale;
      $locale_data = $this->system_core->locale->get_data();

      if (!is_null($this->system_core->urlp->get_path(1))) {
        $page_static_name = urldecode($this->system_core->urlp->get_path(1));

        if (PageStatic::exists_by_name($this->system_core, $page_static_name)) {
          http_response_code(200);

          $page_static = PageStatic::get_by_name($this->system_core, $page_static_name);
          $page_static->init_data(['id', 'texts', 'name', 'metadata']);

          $this->page->breadcrumbs->add($locale_data['PAGE_STATIC_PAGE_BREADCRUMPS_INDEX_LABEL'], '/');
          $this->page->breadcrumbs->add($page_static->get_title($this->system_core->configurator->get_database_entry_value('base_locale')), $page_static->get_name());
          $this->page->breadcrumbs->assembly();

          $this->system_core->configurator->set_meta_title($page_static->get_title($cms_base_locale_name));
          $this->system_core->configurator->set_meta_description($page_static->get_description($cms_base_locale_name));
          $this->system_core->configurator->set_meta_keywrords($page_static->get_keywords($cms_base_locale_name));

          /**
           * @var Parsedown Парсер markdown-разметки
           */
          $parsedown = new Parsedown();

          /**
           * @var string Заголовок статической страницы
           */
          $page_static_title = (!empty($page_static->get_title($cms_base_locale_name))) ? $page_static->get_title($cms_base_locale_name) : $page_static->get_title($cms_base_locale_setted_name);
          /**
           * @var string Содержание статической страницы
           */
          $page_static_content = (!empty($page_static->get_content($cms_base_locale_name))) ? $page_static->get_content($cms_base_locale_name) : $page_static->get_content($cms_base_locale_setted_name);

          $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
            'PAGE_NAME' => 'static',
            'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/static.tpl', [
              'PAGE_BREADCRUMPS' => $this->page->breadcrumbs->assembled,
              'PAGE_TITLE' => $page_static_title,
              'PAGE_CONTENT' => $parsedown->text($page_static_content),
              'PAGE_PREVIEW_URL' => ($page_static->get_preview_url() != '') ? $page_static->get_preview_url() : PageStatic::get_preview_default_url($this->system_core, 1024),
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