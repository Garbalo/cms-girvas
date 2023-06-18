<?php

namespace core\PHPLibrary\Page\Entry {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageError implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';
    public int $error_code;

    public function __construct(SystemCore $system_core, Page $page, int $error_code) {
      $this->system_core = $system_core;
      $this->page = $page;
      $this->error_code = $error_code;
    }

    public function assembly() : void {
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
        'PAGE_NAME' => sprintf('error error_%d', $this->error_code),
        'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/error.tpl', [
          'ERROR_TITLE' => 'Страница не найдена',
          'ERROR_DESCRIPTION' => sprintf('К сожалению, искомая Вами страница по адресу "%s" была удалена, либо перенесена. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.', $_SERVER['REQUEST_URI'])
        ])
      ]);
    }

  }

}

?>