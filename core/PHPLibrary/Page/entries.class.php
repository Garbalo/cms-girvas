<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\SystemCore\Locale as SystemCoreLocale;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Pagination as Pagination;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\EntryCategory as EntryCategory;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageEntries implements InterfacePage {
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
      $this->system_core->template->add_style(['href' => 'styles/page/entries.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/pagination.css', 'rel' => 'stylesheet']);

      $entries_category_name = (!is_null($this->system_core->urlp->get_path(1))) ? urldecode($this->system_core->urlp->get_path(1)) : 'all';
      
      if (EntryCategory::exists_by_name($this->system_core, $entries_category_name) || $entries_category_name == 'all') {
        http_response_code(200);

        $entries_count_on_page = 6;
        $pagination_item_current = (!is_null($this->system_core->urlp->get_param('pageNumber'))) ? (int)$this->system_core->urlp->get_param('pageNumber') : 0;
        
        $cms_base_locale_setted_name = $this->system_core->configurator->get_database_entry_value('base_locale');
        $cms_base_locale_name = (!is_null($this->system_core->urlp->get_param('locale'))) ? $this->system_core->urlp->get_param('locale') : $cms_base_locale_setted_name;
        $cms_base_locale = new SystemCoreLocale($this->system_core, $cms_base_locale_name);
        if (!$cms_base_locale->exists_file_data_json()) {
          $cms_base_locale_name = $cms_base_locale_setted_name;
        }

        $this->page->breadcrumbs->add('Все записи', '/entries');

        if ($entries_category_name != 'all') {
          $entries_category = EntryCategory::get_by_name($this->system_core, $entries_category_name);
          $entries_category->init_data(['name', 'texts']);
          $entries_category_id = $entries_category->get_id();

          $this->system_core->configurator->set_meta_title(sprintf('%s | %s', $entries_category->get_title($cms_base_locale_name), $this->system_core->configurator->get_site_title()));

          $this->page->breadcrumbs->add($entries_category->get_title($this->system_core->configurator->get_database_entry_value('base_locale')), sprintf('/entries/%s', $entries_category->get_name()));
          $this->page->breadcrumbs->assembly();

          /** @var Entries $entries Объект класса Entries */
          $entries = new Entries($this->system_core);
          $entries_array_objects = $entries->get_by_category_id($entries_category_id, [
            'limit' => [$entries_count_on_page, $pagination_item_current * $entries_count_on_page]
          ]);
          $entries_count = $entries->get_count_by_category_id($entries_category_id);
        } else {
          $this->page->breadcrumbs->assembly();

          $this->system_core->configurator->set_meta_title(sprintf('Все записи | %s', $this->system_core->configurator->get_site_title()));

          /** @var Entries $entries Объект класса Entries */
          $entries = new Entries($this->system_core);
          $entries_array_objects = $entries->get_all([
            'limit' => [$entries_count_on_page, $pagination_item_current * $entries_count_on_page]
          ]);
          $entries_count = $entries->get_count_total();
        }

        unset($entries);

        $entries_array_templates = [];
        foreach ($entries_array_objects as $entry_object) {
          $entry_object->init_data(['id', 'texts', 'name']);

          array_push($entries_array_templates, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries/entriesList/item.tpl', [
            'ENTRY_ID' => $entry_object->get_id(),
            'ENTRY_TITLE' => $entry_object->get_title(),
            'ENTRY_DESCRIPTION' => $entry_object->get_description(),
            'ENTRY_URL' => $entry_object->get_url(),
          ]));

          unset($entry_data);
        }

        unset($entries_array_objects);

        $pagination = new Pagination($this->system_core, $entries_count, $entries_count_on_page, $pagination_item_current);
        $pagination->assembly();

        $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
          'PAGE_NAME' => 'entries',
          'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries.tpl', [
            'PAGE_BREADCRUMPS' => $this->page->breadcrumbs->assembled,
            'ENTRIES_CATEGORY_TITLE' => ($entries_category_name == 'all') ? 'Все записи' : $entries_category->get_title(),
            'ENTRIES_LIST' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entries/entriesList/list.tpl', [
              'ENTRIES_LIST_ITEMS' => implode($entries_array_templates)
            ]),
            'ENTRIES_PAGINATION' => $pagination->assembled
          ])
        ]);
  
        unset($entries_array_templates);
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