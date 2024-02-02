<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace templates\install\default {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  final class Core implements \core\PHPLibrary\Template\InterfaceCore {
    private \core\PHPLibrary\Template $template;
    public string $assembled;
    
    /**
     * __construct
     *
     * @param  mixed $template
     * @return void
     */
    public function __construct(\core\PHPLibrary\Template $template) {
      $this->template = $template;
    }
    
    /**
     * Сборка шапки сайта
     *
     * @param  mixed $template_replaces Массив тегами шаблона и их значениями
     * @return string
     */
    public function assembly_header(array $template_replaces = []) : string {
      return TemplateCollector::assembly_file_content($this->template, 'templates/header.tpl', $template_replaces);
    }
    
    /**
     * Сборка главной секции сайта
     *
     * @param  mixed $template_replaces Массив тегами шаблона и их значениями
     * @return string
     */
    public function assembly_main(array $template_replaces = []) : string {
      $database_configurations = $this->template->system_core->configurator->get('database');

      $template_replaces['CONFIGURATION_DATABASE_SCHEME'] = $database_configurations['scheme'];
      $template_replaces['CONFIGURATION_DATABASE_PREFIX'] = $database_configurations['prefix'];
      $template_replaces['CONFIGURATION_DATABASE_HOST'] = $database_configurations['host'];
      $template_replaces['CONFIGURATION_DATABASE_PASSWORD'] = $database_configurations['password'];
      $template_replaces['CONFIGURATION_DATABASE_NAME'] = $database_configurations['name'];
      $template_replaces['CONFIGURATION_DATABASE_USER'] = $database_configurations['user'];

      $template_replaces['SITE_TITLE_VALUE'] = ($this->template->system_core->configurator->exists_database_entry_value('base_title')) ? $this->template->system_core->configurator->get_database_entry_value('base_title') : '';
      $template_replaces['SITE_DESCRIPTION_VALUE'] = ($this->template->system_core->configurator->exists_database_entry_value('seo_site_description')) ? $this->template->system_core->configurator->get_database_entry_value('seo_site_description') : '';
      $template_replaces['SITE_KEYWORDS_VALUE'] = ($this->template->system_core->configurator->exists_database_entry_value('seo_site_keywords')) ? implode(', ', json_decode($this->template->system_core->configurator->get_database_entry_value('seo_site_keywords'), true)) : '';

      return TemplateCollector::assembly_file_content($this->template, 'templates/main.tpl', $template_replaces);
    }
    
    /**
     * Сборка подвала сайта
     *
     * @param  mixed $template_replaces Массив тегами шаблона и их значениями
     * @return string
     */
    public function assembly_footer(array $template_replaces = []) : string {
      return TemplateCollector::assembly_file_content($this->template, 'templates/footer.tpl', $template_replaces);
    }
    
    /**
     * Сборка основной части документа
     *
     * @param  mixed $template_replaces Массив тегами шаблона и их значениями
     * @return string
     */
    public function assembly_document(array $template_replaces = []) : string {
      return TemplateCollector::assembly_file_content($this->template, 'templates/document.tpl', $template_replaces);
    }
    
    /**
     * Итоговая сборка шаблона
     *
     * @return void
     */
    public function assembly() : void {
      $this->template->add_style(['href' => 'styles/normalize.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/fonts.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/colors.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/common.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/table.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/form.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/modal.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/interactive.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/notification.css', 'rel' => 'stylesheet']);
      
      $this->template->add_script(['src' => 'interactive.class.js', 'type' => 'module'], true);
      $this->template->add_script(['src' => 'install/common.js', 'type' => 'module'], true);

      $this->template->add_style(['href' => 'styles/header.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/main.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/footer.css', 'rel' => 'stylesheet']);

      /** @var string $this->assembled Итоговый шаблон в виде строки */
      $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
        'PAGE_HEADER' => $this->assembly_header(),
        'PAGE_MAIN' => $this->assembly_main(),
        'PAGE_FOOTER' => $this->assembly_footer()
      ]);
    }

  }

}

?>