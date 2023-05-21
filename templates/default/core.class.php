<?php

namespace templates\default {
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
      /** @var string $assembled Содержимое шаблона */
      $assembled;

      return TemplateCollector::assembly_file_content($this->template, 'templates/html.tpl', $template_replaces);
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
      $this->template->add_style(['href' => 'styles/header.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/main.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/footer.css', 'rel' => 'stylesheet']);
      
      $this->template->add_script(['src' => 'scripts/slider.class.js']);
      $this->template->add_script(['src' => 'scripts/main.loader.js']);

      /** @var string $this->assembled Итоговый шаблон в виде строки */
      $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
        'SITE_HEADER' => $this->assembly_header(),
        'SITE_MAIN' => $this->assembly_main(),
        'SITE_FOOTER' => $this->assembly_footer()
      ]);
    }

  }

}

?>