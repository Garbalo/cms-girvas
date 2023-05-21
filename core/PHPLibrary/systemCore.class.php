<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\SystemCore\FileConnector as SystemCoreFileConnector;
  
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
    private array $configuration = [];
    public URLParser $urlp;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
      // Стандартные назначения конфигураций
      $this->add_configuration('default_system_language', 'ru_RU');

      $this->init();
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
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.interface\.php$/');
      $file_connector->reset_current_directory();
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.class\.php$/');
      $file_connector->reset_current_directory();

      $this->init_url_parser();
    }
    
    /**
     * Инициализация URL-парсера
     *
     * @return void
     */
    private function init_url_parser() {
      $this->urlp = new URLParser();
    }

    /**
     * Добавление новой конфигурации в CMS
     *
     * @param  mixed $configuration_name Наименование конфигурации
     * @param  mixed $configuration_value Значение конфигурации
     * @return void
     */
    private function add_configuration(string $configuration_name, mixed $configuration_value) : void {
      array_push($this->configuration, [$configuration_name => $configuration_value]);
    }
    
    /**
     * Назначение значения конфигурации CMS
     *
     * @param  mixed $configuration_name Наименование конфигурации
     * @param  mixed $configuration_value Значение конфигурации
     * @return void
     */
    public function set_configuration(string $configuration_name, mixed $configuration_value) : void {
      if (array_key_exists(array_key_exists($configuration_name, $configuration))) {
        $this->configurations[$configuration_name] = $configuration_value;
      }
    }

    /**
     * Получение отдельного параметра конфигураций CMS
     *
     * @param  mixed $configuration_name Наименование конфигурации
     * @return mixed
     */
    public function get_configuration(string $configuration_name) : mixed {
      /** @var array Массив с кофигурациями CMS */
      $configuration = $this->configuration();
      return (array_key_exists($configuration_name, $configuration)) ? $configuration[$configuration_name] : null;
    }
    
    /**
     * Получение массива конфигураций CMS
     *
     * @return array
     */
    public function get_configurations_array() : array {
      return $this->configurations;
    }

  }

}

?>