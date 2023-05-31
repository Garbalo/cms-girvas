<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\SystemCore\FileConnector as SystemCoreFileConnector;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  final class Template {
    public SystemCore $system_core;
    private mixed $core;
    private string $path;
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
      $this->set_category($template_category);
      $this->set_path($template_path);
      
      $this->init();
    }
    
    /**
     * Инициализация шаблона
     *
     * @return mixed
     */
    private function init() : mixed {
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
     * Назначить путь до шаблона
     *
     * @param  mixed $template_path Путь до шаблона
     * @return void
     */
    public function set_path(string $template_path) : void {
      $this->path = $template_path;
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
     * Получить сборку шаблона ядра
     *
     * @return string
     */
    public function get_core_assembled() : string {
      if (isset($this->core->assembled)) {
        $site_seo_base = $this->system_core->configurator->get('seo_base');
        $template_category = $this->get_category();

        // Итоговая сборка шаблона веб-страницы
        return TemplateCollector::assembly($this->core->assembled, [
          // Стили веб-страницы в DOM-элементе HEAD
          'SITE_STYLES' => TemplateCollector::assembly_styles($this, $this->get_styles()),
          // Скрипты веб-страницы в DOM-элементе HEAD
          'SITE_SCRIPTS' => TemplateCollector::assembly_scripts($this, $this->get_scripts()),
          'SITE_TEMPLATE_URL' => ($template_category != 'default') ? sprintf('/templates/%s/%s', $template_category, $this->get_name()) : sprintf('/templates/%s', $this->get_name()),
          'SITE_TITLE' => $site_seo_base['title'],
          'SITE_DESCRIPTION' => $site_seo_base['description'],
          'SITE_KEYWORDS' => implode(', ', $site_seo_base['keywords'])
        ]);
      }

      return 'Template core don\'t have a assembled templates files.';
    }
    
    /**
     * Получить полного пути до ядра шаблона
     *
     * @return string
     */
    private function get_core_path() : string {
      /** @var string $template_name Наименование шаблона */
      $template_name = $this->get_name();
      return sprintf('%s/core.class.php', $this->get_path());
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
  }

}

?>