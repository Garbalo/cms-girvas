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
  use \core\PHPLibrary\Module as Module;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageModule implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/module.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/module.js', 'type' => 'module'], true);
      
      $module_name = ($this->system_core->urlp->get_path(2) == 'repository') ? $this->system_core->urlp->get_path(3) : $this->system_core->urlp->get_path(2);
      $module = new Module($this->system_core, $module_name);

      $module_exists = false;
      if ($this->system_core->urlp->get_path(2) == 'repository') {
        $module_repository_url = sprintf('https://repository.cms-girvas.ru/modules/%s', $module_name);
        $ch = curl_init($module_repository_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_exucute_result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $module_data = $curl_exucute_result['outputData'];
        if (isset($module_data['metadata'])) {
          $module_exists = true;
        }

        if ($module_exists) {
          $parsedown = new Parsedown();

          $module_metadata = $module_data['metadata'];
          $module_title = $module_metadata['title'];
          $module_description = file_get_contents($module_data['readme_url']);
          $module_description = $parsedown->text($module_description);
        }
      } else {
        if ($module->exists_core_file()) {
          if ($module->exists_file_metadata_json()) {
            $module_exists = true;
          }
        }

        if ($module_exists) {
          $parsedown = new Parsedown();

          $module_metadata = $module->get_metadata();
          $module_title = $module->get_title();
          $module_description = $module->get_content_file_readme_md();
          $module_description = $parsedown->text($module_description);
        }
      }

      if ($module_exists) {
        $allowed_metadata = ['authorName'];

        $module_is_enabled = ($module->exists_core_file()) ? $module->is_enabled() : false;
        $module_is_installed = ($module->exists_core_file()) ? $module->is_installed() : false;

        $document = new \DOMDocument();
        $element_ul = $document->createElement('ul');
        $element_ul->setAttribute('class', 'metadata-list list-reset');
        foreach ($module_metadata as $metadata_name => $metadata_value) {
          if (in_array($metadata_name, $allowed_metadata)) {
            $metadata_title = $metadata_name;
            switch ($metadata_name) {
              case 'authorName': $metadata_title = 'Автор'; break;
            }
            $element_li = $document->createElement('li', TemplateCollector::assembly('{METADATA_TITLE}: {METADATA_VALUE}', [
              'METADATA_TITLE' => $metadata_title,
              'METADATA_VALUE' => $metadata_value
            ]));
            $element_li->setAttribute('class', 'list__item');
            $element_ul->appendChild($element_li);
          }
        }
        $document->appendChild($element_ul);
        $document->formatOutput = true;
        $metadata_list_transformed = $document->saveHTML();
        unset($document);

        $parsedown = new Parsedown();

        $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/module.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'module',
          'MODULE_NAME' => $module_name,
          'MODULE_TITLE' => $module_title,
          'MODULE_DESCRIPTION' => $parsedown->text($module_description),
          'MODULE_METADATA_LIST' => $metadata_list_transformed,
          'MODULE_ENABLED_STATUS' => ($module_is_enabled) ? 'enabled' : 'disabled',
          'MODULE_INSTALLED_STATUS' => ($module_is_installed) ? 'installed' : 'not-installed'
        ]);
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