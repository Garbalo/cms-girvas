<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Template as Template;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageTemplates implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/templates.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/templates.js'], true);

      $parsedown = new Parsedown();

      if ($this->system_core->urlp->get_path(2) == 'search') {

        $ch = curl_init('https://repository.cms-girvas.ru/templates');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_exucute_result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (isset($curl_exucute_result['outputData'])) {
          $templates_list_items_transformed_array = [];

          if (count($curl_exucute_result['outputData']) > 0) {
            foreach ($curl_exucute_result['outputData'] as $template_name => $template_data) {
              array_push($templates_list_items_transformed_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/templates/search/listItem.tpl', [
                'TEMPLATE_NAME' => $template_name,
                'TEMPLATE_TITLE' => $template_data['metadata']['title'],
                'TEMPLATE_DESCRIPTION' => $parsedown->text($template_data['metadata']['description']),
                'TEMPLATE_CREATED_TIMESTAMP' => date('d.m.Y', $template_data['metadata']['createdUnixTimestamp']),
                'TEMPLATE_AUTHOR' => $template_data['metadata']['authorName'],
                'TEMPLATE_PREVIEW_URL' => $template_data['previews'][0]
              ]));
            }
          }
        } else {
          $templates_list_items_transformed_array = [];
        }

      } else {

        $templates_list_items_transformed_array = [];
        $uploaded_templates_names = $this->system_core->get_array_uploaded_templates_names();
        if (count($uploaded_templates_names) > 0) {
          foreach ($uploaded_templates_names as $template_name) {
            $template = new Template($this->system_core, $template_name);
            
            if ($template->exists_file_metadata_json()) {
              array_push($templates_list_items_transformed_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/templates/listItem.tpl', [
                'TEMPLATE_NAME' => $template->get_name(),
                'TEMPLATE_TITLE' => $template->get_title(),
                'TEMPLATE_DESCRIPTION' => $parsedown->text($template->get_description()),
                'TEMPLATE_CREATED_TIMESTAMP' => date('d.m.Y', $template->get_core_created_unix_timestamp()),
                'TEMPLATE_AUTHOR' => $template->get_author_name(),
                'TEMPLATE_PREVIEW_URL' => $template->get_preview_url(),
                'TEMPLATE_PAGE_URL' => sprintf('/admin/template/%s', $template->get_name())
              ]));
            }

            unset($template);
          }
        }

      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/templates.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'templates',
        'TEMPLATES_LIST' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/templates/list.tpl', [
          'TEMPLATES_LIST_ITEMS' => implode($templates_list_items_transformed_array)
        ])
      ]);
    }
  }
}

?>