<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\SystemCore\Configurator as SystemCoreConfigurator;
  use \core\PHPLibrary\SystemCore\DatabaseConnector as SystemCoreDatabaseConnector;
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
    public SystemCoreConfigurator $configurator;
    public SystemCoreDatabaseConnector $database_connector;
    public URLParser $urlp;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
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
      // Подключение файлов с перечислениями
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.enum\.php$/');
      $file_connector->reset_current_directory();
      // Подключение файлов с интерфейсами
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.interface\.php$/');
      $file_connector->reset_current_directory();
      // Подключение файлов с классами
      $file_connector->connect_files_recursive('/^([a-zA-Z_]+)\.class\.php$/');
      $file_connector->reset_current_directory();

      $this->configurator = new SystemCoreConfigurator($this);
      $this->configurator->set('cms_language_default', 'ru_RU');

      $this->database_connector = new SystemCoreDatabaseConnector($this, $this->configurator);
      $this->database_connector->database->connect();

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

  }

}

?>