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

    public function assembly_page_index(array $template_replaces = []) : string {
      return TemplateCollector::assembly_file_content($this->template, 'templates/page/index.tpl', [
        'PAGE_NAME' => 'index',
        'ENTRIES_LIST' => ''
      ]);
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
      $this->template->system_core->init_page($this->template->system_core->urlp->get_path_string());
      $site_page = $this->template->system_core->get_inited_page();
      $site_page->assembly();
      
      $template_replaces['SITE_PAGE'] = TemplateCollector::assembly($site_page->assembled, []);

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
      $this->template->add_style(['href' => 'styles/colors.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/common.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/header.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/main.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/footer.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
      
      $this->template->add_script(['src' => 'popupNotification.class.js'], true);
      $this->template->add_script(['src' => 'form.class.js'], true);
      $this->template->add_script(['src' => 'common.js'], true);
      $this->template->add_script(['src' => 'core.class.js', 'type' => 'module'], true);

      $profile_link = ($this->template->system_core->client->is_logged(1)) ? '<a class="header__nav-link display-block" href="/profile"><span class="header__nav-span">Профиль</span></a>' : '<a id="SYSTEM_GE_IMC_00000001" class="header__nav-link display-block" href="#"><span class="header__nav-span">Войти</span></a>';

      /** @var string $this->assembled Итоговый шаблон в виде строки */
      $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
        'SITE_HEADER' => $this->assembly_header([
          'NAVIGATION_PROFILE_LINK' => $profile_link
        ]),
        'SITE_MAIN' => $this->assembly_main(),
        'SITE_FOOTER' => $this->assembly_footer()
      ]);
    }

  }

}

?>