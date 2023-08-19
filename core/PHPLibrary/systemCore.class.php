<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\SystemCore\Configurator as SystemCoreConfigurator;
  use \core\PHPLibrary\SystemCore\Locale as SystemCoreLocale;
  use \core\PHPLibrary\SystemCore\DatabaseConnector as SystemCoreDatabaseConnector;
  use \core\PHPLibrary\SystemCore\FileConnector as SystemCoreFileConnector;
  use \core\PHPLibrary\Client as Client;
  
  /**
   * Class SystemCore
   * @package core\PHPLibrary
   * 
   * @property-read string CMS_CORE_PATH Полный путь до ядра CMS
   * @property-read string CMS_CORE_PHP_LIBRARY_PATH Полный путь до PHP-библиотеки CMS
   * @property-read string CMS_CORE_JS_LIBRARY_PATH Полный путь до JavaScript-библиотеки CMS
   * @property-read string CMS_CORE_TS_LIBRARY_PATH Полный путь до TypeScript-библиотеки CMS
   * @property      array $configuration Массив с конфигурациями CMS
   * @property      URLParser $urlp Объект класса URLParser
   */
  final class SystemCore {
    public const CMS_CORE_PATH = 'core';
    public const CMS_CORE_PHP_LIBRARY_PATH = 'core/PHPLibrary';
    public const CMS_CORE_JS_LIBRARY_PATH = 'core/JSLibrary';
    public const CMS_CORE_TS_LIBRARY_PATH = 'core/TSLibrary';
    public const CMS_MODULES_PATH = 'modules';
    public const CMS_TITLE = 'CMS GIRVAS';
    public const CMS_VERSION = '0.0.39 Pre-alpha';
    public SystemCoreConfigurator $configurator;
    public SystemCoreDatabaseConnector $database_connector;
    public SystemCoreLocale $locale;
    public URLParser $urlp;
    public Client $client;
    public Template $template;
    public array $modules = [];
    public array $page_dir_array = [];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
      $this->init();
    }

    public function get_cms_title() : string {
      return self::CMS_TITLE;
    }

    public function get_cms_version() : string {
      return self::CMS_VERSION;
    }

    public function set_template(Template $template) : void {
      $this->template = $template;
    }

    public function get_template() : Template {
      return $this->template;
    }

    public function get_inited_page() : InterfacePage {
      return $this->page_dir_array[array_key_last($this->page_dir_array)];
    }

    public function init_page(string $dir) : bool {
      $dir = ($dir == '') ? 'index' : $dir;
      $this->page_dir_array = explode('/', $dir);
      
      if ($this->page_dir_array[0] == $this->template->get_category()) {
        $this->page_dir_array[0] = ucfirst($this->page_dir_array[0]);
        array_push($this->page_dir_array, 'index');
      }
      
      $current_dir_final_array = [];
      for ($index_a = 0; $index_a < count($this->page_dir_array); $index_a++) {
        $current_dir_array = [];
        for ($index_b = 0; $index_b < $index_a + 1; $index_b++) {
          array_push($current_dir_array, $this->page_dir_array[$index_b]);
        }

        $current_dir = implode('/', $current_dir_array);
        $class_path = sprintf('%s/core/PHPLibrary/Page/%s.class.php', CMS_ROOT_DIRECTORY, $current_dir);
        
        if (file_exists($class_path)) {
          $current_dir_array[array_key_last($current_dir_array)] = 'Page' . ucfirst($current_dir_array[array_key_last($current_dir_array)]);
          $current_dir = implode('/', $current_dir_array);
          $current_dir = str_replace('/', '\\', $current_dir);
          
          $class = sprintf('\\core\\PHPLibrary\\Page\\%s', $current_dir);
          $page_object = new $class($this, new Page($this, $current_dir_array));
          
          if ($current_dir_array[0] == $this->template->get_category()) unset($current_dir_array[0]);
          $current_dir_array[array_key_last($current_dir_array)] = &$page_object;
          $current_dir_final_array = $current_dir_array;
          break;
        }
      }

      if (empty($current_dir_final_array)) {
        $current_dir_final_array['oh_shit'] = 'karelia_forever';
      }

      if (gettype($current_dir_final_array[array_key_last($current_dir_final_array)]) == 'string') {
        $this->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
        
        $class = sprintf('\\core\\PHPLibrary\\Page\\PageError', $current_dir);
        $current_dir_final_array[array_key_last($current_dir_final_array)] = new $class($this, new Page($this, $current_dir_final_array), 404);
      }

      $this->page_dir_array = $current_dir_final_array;

      return true;
    }
    
    /**
     * Инициализация ядра CMS
     *
     * @return void
     */
    private function init() {
      require_once(sprintf('%s/%s/SystemCore/fileConnector.interface.php', CMS_ROOT_DIRECTORY, self::CMS_CORE_PHP_LIBRARY_PATH));
      require_once(sprintf('%s/%s/SystemCore/fileConnector.class.php', CMS_ROOT_DIRECTORY, self::CMS_CORE_PHP_LIBRARY_PATH));

      $file_connector = new SystemCoreFileConnector($this);
      $file_connector->set_start_directory(self::CMS_CORE_PHP_LIBRARY_PATH);
      $file_connector->set_current_directory(self::CMS_CORE_PHP_LIBRARY_PATH);
      // Подключение файлов с перечислениями
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.enum\.php$/');
      $file_connector->reset_current_directory();
      // Подключение файлов с интерфейсами
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.interface\.php$/');
      $file_connector->reset_current_directory();
      // Подключение файлов с классами
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.class\.php$/');
      $file_connector->reset_current_directory();

      $modules_enabled = Modules::get_enabled();
      if (!empty($modules_enabled)) {
        foreach ($modules_enabled as $module_name => $module_data) {
          $module_path = sprintf('%s/modules/%s', CMS_ROOT_DIRECTORY, $module_name);
          if (file_exists($module_path)) {
            $file_connector->set_start_directory($module_path);
            $file_connector->set_current_directory($module_path);

            // Подключение файлов с перечислениями
            $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.enum\.php$/');
            $file_connector->reset_current_directory();
            // Подключение файлов с интерфейсами
            $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.interface\.php$/');
            $file_connector->reset_current_directory();
            // Подключение файлов с классами
            $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.class\.php$/');
            $file_connector->reset_current_directory();

            Module::connect_core($this, $module_name);
          }
        }
      }

      $this->configurator = new SystemCoreConfigurator($this);
      $this->configurator->set('cms_language_default', 'ru_RU');

      $this->database_connector = new SystemCoreDatabaseConnector($this, $this->configurator);
      $this->database_connector->database->connect();

      $this->client = new Client($this);
      $this->init_url_parser();

      if ($this->urlp->get_path(0) == 'install') {
        $install_locale = (!is_null($this->urlp->get_param('locale'))) ? $this->urlp->get_param('locale') : 'en_US';
      }

      if ($this->urlp->get_path(0) != 'handler') {
        $template_base_name = ($this->configurator->exists_database_entry_value('base_template')) ? $this->configurator->get_database_entry_value('base_template') : 'default';

        $cms_base_locale_name = ($this->configurator->exists_database_entry_value('base_locale')) ? $this->configurator->get_database_entry_value('base_locale') : 'en_US';
        $cms_admin_locale_name = ($this->configurator->exists_database_entry_value('base_admin_locale')) ? $this->configurator->get_database_entry_value('base_admin_locale') : 'en_US';

        switch ($this->urlp->get_path(0)) {
          case 'install': $this->set_template(new Template($this, 'default', 'install')); $this->locale = new SystemCoreLocale($this, $install_locale, 'install'); break;
          case 'admin': $this->set_template(new Template($this, 'default', 'admin')); $this->locale = new SystemCoreLocale($this, $cms_admin_locale_name, 'admin'); break;
          default: $this->set_template(new Template($this, $template_base_name)); $this->locale = new SystemCoreLocale($this, $cms_base_locale_name, 'base'); break;
        }

        $template = $this->get_template();
        $template->init();
      }
    }
    
    /**
     * Получить путь до корня CMS
     *
     * @return string
     */
    public function get_cms_path() : string {
      return $_SERVER['DOCUMENT_ROOT'];
    }
    
    /**
     * Получить массив имен загруженных шаблонов
     *
     * @return array
     */
    public function get_array_uploaded_templates_names() : array {
      $templates_path = sprintf('%s/templates', $this->get_cms_path());
      return array_diff(scandir(sprintf($templates_path)), ['..', '.']);
    }
    
    /**
     * Получить массив имен локализаций
     *
     * @return array
     */
    public function get_array_locales_names() : array {
      $locales_path = sprintf('%s/locales', $this->get_cms_path());
      return array_diff(scandir(sprintf($locales_path)), ['..', '.']);
    }
    
    /**
     * Получить внешнюю ссылку до сайта
     *
     * @return string
     */
    public function get_site_url() : string {
      return ($this->configurator->get('ssl_is_enabled')) ? sprintf('https://%s', $this->configurator->get('domain')) : sprintf('http://%s', $this->configurator->get('domain'));
    }
    
    /**
     * Инициализация URL-парсера
     *
     * @return void
     */
    private function init_url_parser() {
      $this->urlp = new URLParser();
    }

    public static function recursive_files_remove(string $path) : bool {
      $files_array_on_path = array_diff(scandir($path), ['..', '.']);

      if (count($files_array_on_path) > 0) {
        foreach ($files_array_on_path as $file) {
          $file_path = sprintf('%s/%s', $path, $file);
          
          if (is_dir($file_path)) {
            self::recursive_files_remove($file_path);
          } else {
            unlink($file_path);
          }
        }

        rmdir($path);
        return true;
      } else {
        rmdir($path);
        return true;
      }

      return false;
    }
    
    /**
     * Парсинг HTTP-запроса
     *
     * @param  mixed $input_string
     * @return void
     */
    public static function parse_raw_http_request(string $input_string, string $content_type) {
      // grab multipart boundary from content type header
      preg_match('/boundary=(.*)$/', $content_type, $matches);
      $boundary = $matches[1];
      
      // split content by boundary and get rid of last -- element
      $array_blocks = preg_split("/-+$boundary/", $input_string);
      array_pop($array_blocks);
      
      $data_array = [];
      // loop data blocks
      foreach ($array_blocks as $index => $block) {
        if (empty($block)) continue;

        // parse uploaded files
        if (strpos($block, 'application/octet-stream') !== false) {
          // match "name", then everything after "stream" (optional) except for prepending newlines 
          preg_match('/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s', $block, $matches);
        } else {
          // match "name" and optional value in between newline sequences
          preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
        }

        if (isset($matches[2])) {
          if (preg_match('/(.*)\[\]$/', $matches[1], $matches_name)) {
            $data_array[$matches_name[1]][] = $matches[2];
          } else {
            $data_array[$matches[1]] = $matches[2];
          }
        }
      }   
      
      return $data_array;
    }

  }

}

?>