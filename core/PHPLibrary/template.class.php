<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\SystemCore\FileConnector as SystemCoreFileConnector;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  final class Template {
    public SystemCore $system_core;
    public mixed $core;
    private string $path;
    private string $url;
    private string $name;
    private string $category;
    
    private array $styles = [];
    private array $scripts = [];

    private array $important_files = [
      'templates/html.tpl',
      'templates/header.tpl',
      'templates/main.tpl',
      'templates/footer.tpl',
      'templates/page.tpl',
      //'templates/page/entry.tpl',
      'templates/page/error.tpl',
      'templates/page/index.tpl',
      'metadata.json'
    ];

    private array $global_variables = [];
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core Объект SystemCore
     * @param  mixed $template_name Наименование шаблона
     * @param  mixed $template_category Категория шаблона
     * @return void
     */
    public function __construct(SystemCore $system_core, string $template_name = 'default', string $template_category = 'default') {
      /** @var SystemCore $this->system_core Объект класса SystemCore */
      $this->system_core = $system_core;
      $this->set_name($template_name);

      $template_path = ($template_category != 'default') ? sprintf('%s/templates/%s/%s', CMS_ROOT_DIRECTORY, $template_category, $template_name) : sprintf('%s/templates/%s', CMS_ROOT_DIRECTORY, $template_name);
      $template_url = ($template_category != 'default') ? sprintf('templates/%s/%s', $template_category, $template_name) : sprintf('templates/%s', $template_name);
      $this->set_category($template_category);
      $this->set_path($template_path);
      $this->set_url($template_url);
    }
    
    /**
     * Инициализация шаблона
     *
     * @return mixed
     */
    public function init() : mixed {
      /** @var string $core_path Путь до файла ядра шаблона */
      $core_path = $this->get_core_path();
      /** @var string $core_class Класс ядра шаблона */
      $core_class = $this->get_core_class();
      if (file_exists($core_path)) {
        require_once($core_path);

        /** @var InterfaceCore $core Объект класса, имплементированного от InterfaceCore */
        $core = $this->get_core_object($core_class);

        if (!is_null($core)) {
          /** @var InterfaceCore $core Объект класса, имплементированного от InterfaceCore */
          $this->core = $core;
          $this->core->assembly();
          return true;
        }
      }

      // Если ядро не было найдено - завершаем работу с ошибкой
      die(sprintf('Template core "%s" is not exists!', $core_class));
    }
    
    /**
     * Получить наименование шаблона
     *
     * @return string
     */
    public function get_name() : string {
      return $this->name;
    }

    public function get_title() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['title'])) ? $metadata['title'] : '';
    }

    public function get_description() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['description'])) ? $metadata['description'] : '';
    }

    public function get_author_name() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['authorName'])) ? $metadata['authorName'] : '';
    }

    public function get_category_name() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['categoryName'])) ? $metadata['categoryName'] : 'default';
    }
    
    /**
     * Назначить наименование категории шаблона
     *
     * @param  mixed $template_name Наименование шаблона
     * @return void
     */
    public function set_category(string $template_category) : void {
      $this->category = $template_category;
    }
    
    /**
     * Получить наименование категории шаблона
     *
     * @return string
     */
    public function get_category() : string {
      return $this->category;
    }
    
    /**
     * Назначить наименование шаблона
     *
     * @param  mixed $template_name Наименование шаблона
     * @return void
     */
    public function set_name(string $template_name) : void {
      $this->name = $template_name;
    }
    
    /**
     * Получить путь до шаблона
     *
     * @return string
     */
    public function get_path() : string {
      return $this->path;
    }
    
    /**
     * Получить URL до шаблона
     *
     * @return string
     */
    public function get_url() : string {
      return $this->url;
    }
    
    /**
     * Назначить путь до шаблона
     *
     * @param  string $template_path Путь до шаблона
     * @return void
     */
    public function set_path(string $template_path) : void {
      $this->path = $template_path;
    }
    
    /**
     * Назначить URL до шаблона
     *
     * @param  string $template_url Путь до шаблона
     * @return void
     */
    public function set_url(string $template_url) : void {
      $this->url = $template_url;
    }

    public function get_preview_url() : string {
      return sprintf('/%s/preview.png', $this->get_url());
    }

    public function get_screenshots_path() : string {
      return sprintf('%s/screenshots', $this->get_path());
    }

    public function get_screenshots_url() : string {
      return sprintf('/%s/screenshots', $this->get_url());
    }

    public function get_screenshots_array() : array {
      $screenshots_path = $this->get_screenshots_path();
      return array_diff(scandir($screenshots_path), ['.', '..']);
    }
    
    /**
     * Получить массив стилей
     *
     * @return array
     */
    private function get_styles() : array {
      return $this->styles;
    }
    
    /**
     * Получить массив скриптов
     *
     * @return array
     */
    private function get_scripts() : array {
      return $this->scripts;
    }
    
    /**
     * Добавить стиль в массив стилей
     *
     * @param  mixed $style_data
     * @return void
     */
    public function add_style(array $style_data) : void {
      array_push($this->styles, $style_data);
    }
    
    /**
     * Добавить скрипт в массив стилей
     *
     * @param  mixed $script_data
     * @return void
     */
    public function add_script(array $script_data, bool $is_cms_core = false) : void {
      $script_data['is_cms_core'] = $is_cms_core;
      array_push($this->scripts, $script_data);
    }
    
    /**
     * Получить массив наименований обязательных файлов
     *
     * @return array
     */
    private function get_important_files() : array {
      return $this->important_files;
    }
    
    /**
     * Проверка наличия обязательных файлов у шаблона
     *
     * @return bool
     */
    public function important_files_exists() : bool {
      $template_path = $this->get_path();
      $important_files = $this->get_important_files();
      foreach ($important_files as $important_file) {
        $file_path = sprintf('%s/%s', $template_path, $important_file);
        if (!file_exists($file_path)) {
          return false;
        }
      }

      return true;
    }
    
    /**
     * Добавить глобальную переменную
     *
     * @param  string $name
     * @param  string|int $value
     * @return void
     */
    public function add_global_variable(string $name, string|int $value) : void {
      $this->global_variables[$name] = $value;
    }

    public function assembly_global_variables() : void {
      if (!empty($this->global_variables)) {
        $this->core->assembled = TemplateCollector::assembly($this->core->assembled, $this->global_variables);
      }
    }

    /**
     * Получить сборку шаблона ядра
     *
     * @return string
     */
    public function get_core_assembled() : string {
      if (isset($this->core->assembled)) {
        $template_category = $this->get_category();
        $template_tags_array = [
          // Стили веб-страницы в DOM-элементе HEAD
          'SITE_STYLES' => TemplateCollector::assembly_styles($this, $this->get_styles()),
          // Скрипты веб-страницы в DOM-элементе HEAD
          'SITE_SCRIPTS' => TemplateCollector::assembly_scripts($this, $this->get_scripts()),
          'SITE_TEMPLATE_URL' => ($template_category != 'default') ? sprintf('/templates/%s/%s', $template_category, $this->get_name()) : sprintf('/templates/%s', $this->get_name()),
          'SITE_TITLE' => (empty($this->system_core->configurator->get_meta_title())) ? $this->system_core->configurator->get_site_title() : $this->system_core->configurator->get_meta_title(),
          'SITE_DESCRIPTION' => (empty($this->system_core->configurator->get_meta_description())) ? $this->system_core->configurator->get_site_description() : $this->system_core->configurator->get_meta_description(),
          'SITE_KEYWORDS' => (empty($this->system_core->configurator->get_meta_keywords())) ? $this->system_core->configurator->get_site_keywords() : $this->system_core->configurator->get_meta_keywords_imploded(),
          'SITE_CHARSET' => $this->system_core->configurator->get_site_charset(),
        ];

        $this->core->assembled = TemplateCollector::assembly_locale($this->core->assembled, $this->system_core->locale);

        // Итоговая сборка шаблона веб-страницы
        return TemplateCollector::assembly($this->core->assembled, $template_tags_array);
      }

      return 'Template core don\'t have a assembled templates files.';
    }
    
    /**
     * Получить полного пути до ядра шаблона
     *
     * @return string
     */
    private function get_core_path() : string {
      return sprintf('%s/core.class.php', $this->get_path());
    }

    public function get_core_created_unix_timestamp() : int {
      $path = $this->get_core_path();
      return filectime($path);
    }
    
    /**
     * Получить класс ядра шаблона
     *
     * @return string
     */
    private function get_core_class() : string {
      /** @var string $template_name Наименование шаблона */
      $template_name = $this->get_name();
      $template_category = $this->get_category();
      return ($template_category != 'default') ? sprintf('\\templates\\%s\\%s\\Core', $template_category, $template_name) :  sprintf('\\templates\\%s\\Core', $template_name);
    }
    
    /**
     * Получить объект ядра шаблона
     *
     * @param  mixed $template_class
     * @return mixed
     */
    public function get_core_object(string $template_class) : mixed {
      if (class_exists($template_class)) {
        return new $template_class($this);
      }

      return null;
    }
    
    /**
     * Проверка наличия файла ядра шаблона
     *
     * @return bool
     */
    public function exists_core_file() : bool {
      $file_path = ($this->get_category() == 'default') ? sprintf('%s/core.class.php', $this->get_path()) : sprintf('%s/%s/core.class.php', $this->get_path(), $this->get_category());
      return file_exists($file_path);
    }

    public function exists_file_metadata_json() : bool {
      return file_exists($this->get_file_metadata_json_path());
    }

    public function get_file_metadata_json_path() : string {
      return sprintf('%s/metadata.json', $this->get_path());
    }

    public function get_metadata() : array|null {
      $file_path = $this->get_file_metadata_json_path();
      $file_content = file_get_contents($file_path);

      return json_decode($file_content, true);
    }
  }

}

?>