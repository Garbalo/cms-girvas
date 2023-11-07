<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

namespace core\PHPLibrary\Page {
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
    private string $error_title;
    private string $error_desription;
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @param  Page $page
     * @param  int $error_code
     * @return void
     */
    public function __construct(SystemCore $system_core, Page $page, int $error_code) {
      $this->system_core = $system_core;
      $this->page = $page;
      $this->error_code = $error_code;

      switch ($error_code) {
        case 404:
          $this->error_title = 'Страница не найдена';
          $this->error_desription = sprintf('К сожалению, искомая Вами страница по адресу "%s" была удалена, либо перенесена. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.', urldecode($_SERVER['REQUEST_URI']));
          break;
        case 500:
          $this->error_title = 'Внутренняя ошибка сервера';
          $this->error_desription = 'К сожалению, на стороне сервера возникла ошибка, которая не позволяет обработать Ваш запрос. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.';
          break;
        case 503:
          $this->error_title = 'Доступ запрещен';
          $this->error_desription = 'К сожалению, доступ к данной странице ограничен. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.';
          break;
        default:
          $this->error_title = 'Неизвестная ошибка';
          $this->error_desription = 'К сожалению, что-то пошло не так. Рекомендуем вернуться на <a href="/" title="{SITE_TITLE}">главную страницу</a>.';
      }

    }
    
    /**
     * Сборка шаблона страницы
     *
     * @return void
     */
    public function assembly() : void {
      http_response_code($this->error_code);

      $this->system_core->template->add_style(['href' => 'styles/page/error.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'page.class.js', 'type' => 'module'], true);

      $this->system_core->configurator->set_meta_title($this->error_title);

      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
        'PAGE_NAME' => sprintf('error error_%d', $this->error_code),
        'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/error.tpl', [
          'ERROR_TITLE' => $this->error_title,
          'ERROR_DESCRIPTION' => $this->error_desription
        ])
      ]);
    }

  }

}

?>