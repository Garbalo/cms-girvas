<?php

namespace core\PHPLibrary\SystemCore {

  class FileConnector implements InterfaceFileConnector {
    private mixed $system_core = null;
    private string $current_directory = '';
        
    /**
     * __construct
     *
     * @param  mixed $system_core Объект SystemCore
     * @return void
     */
    public function __construct(\core\PHPLibrary\SystemCore $system_core) {
      $this->system_core = $system_core;
    }
    
    /**
     * Назначить текущую директиву
     *
     * @param  mixed $directory Директория
     * @return void
     */
    public function set_current_directory(string $directory) : void {
      $this->current_directory = $directory;
    }
    
    /**
     * Получить текущую директиву
     *
     * @return string
     */
    public function get_current_directory() : string {
      return sprintf('%s/%s', CMS_ROOT_DIRECTORY, $this->current_directory);
    }

    
    /**
     * Подключение файла
     *
     * @param  mixed $file_name
     * @return bool
     */
    public function connect_file(string $file_name) : bool {
      /** @var string $file_path Полный путь до подключаемого файла */
      $file_path = sprintf('%s/%s', $this->get_current_directory(), $file_name);
      if (file_exists($file_path)) {
        require_once($file_path);
        return true;
      }

      return false;
    }

        
    /**
     * Рекурсивное подключение файлов
     *
     * @param  mixed $files_path
     * @param  mixed $file_name_pattern
     * @return bool
     */
    public function connect_files_recursive(string $files_path, string $file_name_pattern) : void {
      /** @var array $files_list Массив файлов */
      $files_list = array_diff(scandir($files_path), ['..', '.']);
      foreach ($files_list as $file_name) {
        if (preg_match($file_name_pattern, $file_name)) {
          /** @var string $file_path Полный путь до файла */
          $file_path = sprintf('%s/%s', $files_path, $file_name);

          if (!is_dir($file_path)) {
            // Подключаем файл
            $this->connect_file($file_name);
          } else {
            // Погружаемся во вложенную папку для последующих подключений
            $this->connect_files_recursive($file_path, $file_name_pattern);
          }
        }
      }
    }

  }

}

?>