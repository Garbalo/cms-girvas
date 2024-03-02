<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Module {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Module as Module;

  final class Locale {
    public SystemCore $system_core;
    public Module $module;
    private string $name;
    private string $core_path;
    private string $data_path;

    public function __construct(Module $module, string $name) {
      $this->system_core = $module->system_core;
      $this->module = $module;
      $this->set_name($name);

      $locale_core_path = sprintf('%s/modules/%s/locales/%s', $this->system_core->get_cms_path(), $module->get_name(), $name);
      $locale_data_path = sprintf('%s/modules/%s/locales/%s', $this->system_core->get_cms_path(), $module->get_name(), $name);
      $this->set_core_path($locale_core_path);
      $this->set_data_path($locale_data_path);
    }

    public function get_icon_url() : string {
      return sprintf('/modules/%s/locales/%s/icons/16.png', $this->module->get_name(), $this->get_name());
    }
  
    private function set_name(string $value) : void {
      $this->name = $value;
    }
  
    public function get_name() : string {
      return $this->name;
    }
    
    /**
     * Назначить путь до локализации
     *
     * @param  string $path Путь до локализации
     * @return void
     */
    public function set_core_path(string $path) : void {
      $this->core_path = $path;
    }
    
    /**
     * Получить путь до локализации
     *
     * @return string
     */
    public function get_core_path() : string {
      return $this->core_path;
    }
    
    /**
     * Назначить путь до данных локализации
     *
     * @param  string $path Путь до локализации
     * @return void
     */
    public function set_data_path(string $path) : void {
      $this->data_path = $path;
    }
    
    /**
     * Получить путь до данных локализации
     *
     * @return string
     */
    public function get_data_path() : string {
      return $this->data_path;
    }

    public function get_title() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['title'])) ? $metadata['title'] : '';
    }

    public function get_author_name() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['authorName'])) ? $metadata['authorName'] : '';
    }

    public function get_iso_639_1() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['iso639_1'])) ? $metadata['iso639_1'] : '';
    }

    public function get_iso_639_2() : string {
      $metadata = $this->get_metadata();
      return (isset($metadata['iso639_2'])) ? $metadata['iso639_2'] : '';
    }

    public function exists_file_data_json() : bool {
      return file_exists($this->get_file_data_json_path());
    }

    public function get_file_data_json_path() : string {
      return sprintf('%s/data.json', $this->get_data_path());
    }

    public function get_data() : array|bool|null {
      $file_path = $this->get_file_data_json_path();
      $file_content = (file_exists($file_path)) ? file_get_contents($file_path) : '{}';

      return json_decode($file_content, true);
    }

    public function get_single_value_by_key(string $key) : string {
      $locale_data = $this->get_data();
      return (isset($locale_data[$key])) ? $locale_data[$key] : '[ ??? ]';
    }

    public function exists_file_metadata_json() : bool {
      return file_exists($this->get_file_metadata_json_path());
    }

    public function get_file_metadata_json_path() : string {
      return sprintf('%s/metadata.json', $this->get_core_path());
    }

    public function get_metadata() : array|null {
      $file_path = $this->get_file_metadata_json_path();
      $file_content = file_get_contents($file_path);

      return json_decode($file_content, true);
    }
  }
}

?>