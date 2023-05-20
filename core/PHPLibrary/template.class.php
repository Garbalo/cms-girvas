<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\SystemCore\FileConnector as SystemCoreFileConnector;

  final class Template {
    private const TEMPLATE_TAG_PATTERN = '/\{([a-zA-Z0-9_]+)\}/';

    private SystemCore $system_core;
    private mixed $core;
    private string $name;
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core Объект SystemCore
     * @param  mixed $template_name Наименование шаблона
     * @return void
     */
    public function __construct(SystemCore $system_core, string $template_name = 'default') {
      $this->system_core = $system_core;
      $this->set_name($template_name);

      $this->init();
    }

    private function init() {
      $core_path = $this->get_core_path();
      $core_class = $this->get_core_class();
      if (file_exists($core_path)) {
        require_once($core_path);

        $core = $this->get_core_object($core_class);

        if (!is_null($core)) {
          return $core;
        }
      }

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
     * Назначить наименование шаблона
     *
     * @param  mixed $template_name Наименование шаблона
     * @return void
     */
    public function set_name(string $template_name) : void {
      $this->name = $template_name;
    }
    
    /**
     * Получить полного пути до ядра шаблона
     *
     * @return string
     */
    private function get_core_path() : string {
      /** @var string $template_name Наименование шаблона */
      $template_name = $this->get_name();
      return sprintf('%s/templates/%s/core.class.php', CMS_ROOT_DIRECTORY, $template_name);
    }
    
    /**
     * Получить класс ядра шаблона
     *
     * @return string
     */
    private function get_core_class() : string {
      /** @var string $template_name Наименование шаблона */
      $template_name = $this->get_name();
      return sprintf('\\templates\\%s\\Core', $template_name);
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