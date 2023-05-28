<?php

namespace templates\admin\default {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Entries\Database as EntriesDatabase;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\User\Session as UserSession;

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
      if ($this->template->system_core->urlp->get_path(0) == null || $this->template->system_core->urlp->get_path(0) == 'index') {
        http_response_code(200);
        
        $this->template->add_style(['href' => 'styles/page/index.css', 'rel' => 'stylesheet']);

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page.tpl', [
          'PAGE_NAME' => 'index',
          'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->template, 'templates/page/index.tpl', [
            'ENTRIES_LIST' => TemplateCollector::assembly_file_content($this->template, 'templates/page/index/entriesList/list.tpl', [
              'ENTRIES_LIST_ITEMS' => implode($entries_array_templates)
            ])
          ])
        ]);
      } else {
        http_response_code(404);

        $this->template->add_style(['href' => 'styles/page/error.css', 'rel' => 'stylesheet']);

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page.tpl', [
          'PAGE_NAME' => 'error',
          'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->template, 'templates/page/error.tpl', [
            'ERROR_TITLE' => 'Страница не найдена',
            'ERROR_DESCRIPTION' => sprintf('К сожалению, искомая Вами страница по адресу "%s" была удалена, либо перенесена. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.', $_SERVER['REQUEST_URI'])
          ])
        ]);
      }
      

      $template_replaces['SITE_PAGE_WRAPPER'] = TemplateCollector::assembly_file_content($this->template, 'templates/pageWrapper.tpl', [
        'SITE_PAGE' => $site_page,
      ]);

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

    public function assembly_auth_admin_page(array $template_replaces = []) : string {
      return TemplateCollector::assembly_file_content($this->template, 'templates/page/auth.tpl', $template_replaces);
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

      /** @var string $user_ip IP-адрес пользователя */
      $user_ip = $_SERVER['REMOTE_ADDR'];

      if (UserSession::exists_by_ip($this->template->system_core, $user_ip)) {
        $this->template->add_style(['href' => 'styles/header.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/main.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/footer.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);

        /** @var string $this->assembled Итоговый шаблон в виде строки */
        $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
          'SITE_HEADER' => $this->assembly_header(),
          'SITE_MAIN' => $this->assembly_main(),
          'SITE_FOOTER' => $this->assembly_footer()
        ]);
      } else {
        $this->template->add_style(['href' => 'styles/page/auth.css', 'rel' => 'stylesheet']);

        $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
          'SITE_HEADER' => '',
          'SITE_MAIN' => $this->assembly_auth_admin_page(),
          'SITE_FOOTER' => ''
        ]);
      }
    }

  }

}

?>