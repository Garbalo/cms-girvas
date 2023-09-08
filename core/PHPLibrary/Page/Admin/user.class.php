<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */


namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageUser implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/user.css', 'rel' => 'stylesheet']);

      $user = null;
      if (!is_null($this->system_core->urlp->get_path(2))) {
        $user_id = (is_numeric($this->system_core->urlp->get_path(2))) ? (int)$this->system_core->urlp->get_path(2) : 0;
        $user = (User::exists_by_id($this->system_core, $user_id)) ? new User($this->system_core, $user_id) : null;
        
        if (!is_null($user)) {
          $user->init_data(['id', 'login', 'email']);
        }
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/user.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'user',
        'USER_ID' => (!is_null($user)) ? $user->get_id() : 0,
        'USER_LOGIN' => (!is_null($user)) ? $user->get_login() : '',
        'USER_EMAIL' => (!is_null($user)) ? $user->get_email() : '',
        'USER_FORM_METHOD' => (!is_null($user)) ? 'PATCH' : 'PUT'
      ]);
    }

  }

}

?>