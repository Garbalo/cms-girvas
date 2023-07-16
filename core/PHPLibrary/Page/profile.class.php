<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageProfile implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';
    
    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @param  Page $page
     * @return void
     */
    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }
    
    /**
     * Сборка шаблона страницы
     *
     * @return void
     */
    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/page/profile.css', 'rel' => 'stylesheet']);
      
      if ($this->system_core->client->is_logged(1)) {
        $profile_user_login = (!is_null($this->system_core->urlp->get_path(1))) ? $this->system_core->urlp->get_path(1) : null;
        $profile_user = null;
        if (User::exists_by_login($this->system_core, $profile_user_login)) {
          $profile_user = User::get_by_login($this->system_core, $profile_user_login);
          $profile_user->init_data(['login', 'email', 'metadata_json']);
        }
        
        if (!is_null($profile_user)) {
          $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
            'PAGE_NAME' => 'profile',
            'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/profile.tpl', [
              'USER_LOGIN' => $profile_user->get_login(),
              'USER_AVATAR_URL' => $profile_user->get_avatar_url(96),
              'USER_EMAIL' => $profile_user->get_email(),
              'USER_NAME' => $profile_user->get_name(),
              'USER_SURNAME' => $profile_user->get_surname(),
              'USER_PATRONYMIC' => $profile_user->get_patronymic(),
              'USER_BIRTHDATE' => date('d.m.Y', $profile_user->get_birthdate_unix_timestamp()),
            ])
          ]);
        } else {
          http_response_code(404);

          $page_error = new PageError($this->system_core, $this->page, 404);
          $page_error->assembly();
          $this->assembled = $page_error->assembled;
        }
      } else {
        http_response_code(503);

        $page_error = new PageError($this->system_core, $this->page, 503);
        $page_error->assembly();
        $this->assembled = $page_error->assembled;
      }
    }

  }

}

?>