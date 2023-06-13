<?php

namespace templates\admin\default {
  use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Entries\Database as EntriesDatabase;
  use \core\PHPLibrary\User as User;
  use \core\PHPLibrary\Users as Users;
  use \core\PHPLibrary\Client\Session as ClientSession;

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
      if ($this->template->system_core->urlp->get_path(1) == null || $this->template->system_core->urlp->get_path(1) == 'index') {
        http_response_code(200);
        
        $this->template->add_style(['href' => 'styles/page/index.css', 'rel' => 'stylesheet']);

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/index.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'index'
        ]);
      } else if ($this->template->system_core->urlp->get_path(1) == 'entry') {
        http_response_code(200);

        $this->template->add_style(['href' => 'styles/page/entry.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/nadvoTE.css', 'rel' => 'stylesheet']);

        $entry = null;
        if (!is_null($this->template->system_core->urlp->get_path(2))) {
          $entry_id = (is_numeric($this->template->system_core->urlp->get_path(2))) ? (int)$this->template->system_core->urlp->get_path(2) : 0;
          $entry = (Entry::exists_by_id($this->template->system_core, $entry_id)) ? new Entry($this->template->system_core, $entry_id) : null;
          
          if (!is_null($entry)) {
            $entry->init_data(['id', 'texts', 'name']);
          }
        }

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/entry.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'entry',
          'ENTRY_EDITOR' => TemplateCollector::assembly_file_content($this->template, 'templates/page/entry/editor.tpl', []),
          'ENTRY_ID' => (!is_null($entry)) ? $entry->get_id() : 0,
          'ENTRY_TITLE' => (!is_null($entry)) ? $entry->get_title() : '',
          'ENTRY_DESCRIPTION' => (!is_null($entry)) ? $entry->get_description() : '',
          'ENTRY_CONTENT' => (!is_null($entry)) ? $entry->get_content() : '',
          'ENTRY_NAME' => (!is_null($entry)) ? $entry->get_name() : '',
          'ENTRY_FORM_METHOD' => (!is_null($entry)) ? 'PATCH' : 'PUT'
        ]);
      } else if ($this->template->system_core->urlp->get_path(1) == 'entries') {
        http_response_code(200);
        
        $this->template->add_style(['href' => 'styles/page/entries.css', 'rel' => 'stylesheet']);
        
        $this->template->add_script(['src' => 'admin/page/entries.js'], true);

        $entries_table_items_assembled_array = [];
        $entries = new Entries($this->template->system_core);
        $entries_array_objects = $entries->get_all();
        unset($entries);

        $entry_number = 1;
        foreach ($entries_array_objects as $entry_object) {
          $entry_object->init_data(['id', 'texts', 'name', 'created_unix_timestamp', 'updated_unix_timestamp']);

          $entry_created_date_timestamp = date('d.m.Y H:i:s', $entry_object->get_created_unix_timestamp());
          $entry_updated_date_timestamp = date('d.m.Y H:i:s', $entry_object->get_updated_unix_timestamp());

          array_push($entries_table_items_assembled_array, TemplateCollector::assembly_file_content($this->template, 'templates/page/entries/tableItem.tpl', [
            'ENTRY_ID' => $entry_object->get_id(),
            'ENTRY_INDEX' => $entry_number,
            'ENTRY_TITLE' => $entry_object->get_title(),
            'ENTRY_DESCRIPTION' => $entry_object->get_description(),
            'ENTRY_URL' => $entry_object->get_url(),
            'ENTRY_CREATED_DATE_TIMESTAMP' => $entry_created_date_timestamp,
            'ENTRY_UPDATED_DATE_TIMESTAMP' => $entry_updated_date_timestamp
          ]));

          $entry_number++;
        }

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/entries.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'entries',
          'ADMIN_PANEL_ENTRIES_TABLE' => TemplateCollector::assembly_file_content($this->template, 'templates/page/entries/table.tpl', [
            'ADMIN_PANEL_ENTRIES_TABLE_ITEMS' => implode($entries_table_items_assembled_array)
          ])
        ]);
      } else if ($this->template->system_core->urlp->get_path(1) == 'users') {
        http_response_code(200);

        $this->template->add_style(['href' => 'styles/page/users.css', 'rel' => 'stylesheet']);
        $this->template->add_script(['src' => 'admin/page/users.js'], true);

        $users_table_items_assembled_array = [];
        $users = new Users($this->template->system_core);
        $users_array_objects = $users->get_all();
        unset($users);

        $user_number = 1;
        foreach ($users_array_objects as $user_object) {
          $user_object->init_data(['id', 'login', 'email', 'created_unix_timestamp', 'updated_unix_timestamp']);

          $user_created_date_timestamp = date('d.m.Y H:i:s', $user_object->get_created_unix_timestamp());
          $user_updated_date_timestamp = date('d.m.Y H:i:s', $user_object->get_updated_unix_timestamp());

          array_push($users_table_items_assembled_array, TemplateCollector::assembly_file_content($this->template, 'templates/page/users/tableItem.tpl', [
            'USER_ID' => $user_object->get_id(),
            'USER_INDEX' => $user_number,
            'USER_LOGIN' => $user_object->get_login(),
            'USER_EMAIL' => $user_object->get_email(),
            'USER_CREATED_DATE_TIMESTAMP' => $user_created_date_timestamp,
            'USER_UPDATED_DATE_TIMESTAMP' => $user_updated_date_timestamp
          ]));

          $user_number++;
        }

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/users.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'users',
          'ADMIN_PANEL_USERS_TABLE' => TemplateCollector::assembly_file_content($this->template, 'templates/page/users/table.tpl', [
            'ADMIN_PANEL_USERS_TABLE_ITEMS' => implode($users_table_items_assembled_array)
          ])
        ]);
      } else if ($this->template->system_core->urlp->get_path(1) == 'user') {
        http_response_code(200);

        $this->template->add_style(['href' => 'styles/page/user.css', 'rel' => 'stylesheet']);

        $user = null;
        if (!is_null($this->template->system_core->urlp->get_path(2))) {
          $user_id = (is_numeric($this->template->system_core->urlp->get_path(2))) ? (int)$this->template->system_core->urlp->get_path(2) : 0;
          $user = (User::exists_by_id($this->template->system_core, $user_id)) ? new User($this->template->system_core, $user_id) : null;
          
          if (!is_null($user)) {
            $user->init_data(['id', 'login', 'email']);
          }
        }

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/user.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'user',
          'USER_ID' => (!is_null($user)) ? $user->get_id() : 0,
          'USER_LOGIN' => (!is_null($user)) ? $user->get_login() : '',
          'USER_EMAIL' => (!is_null($user)) ? $user->get_email() : '',
          'USER_FORM_METHOD' => (!is_null($user)) ? 'PATCH' : 'PUT'
        ]);
      } else {
        http_response_code(404);

        $this->template->add_style(['href' => 'styles/page/error.css', 'rel' => 'stylesheet']);

        /** @var string $site_page Содержимое шаблона страницы */
        $site_page = TemplateCollector::assembly_file_content($this->template, 'templates/page/error.tpl', [
          'ADMIN_PANEL_PAGE_NAME' => 'error'
        ]);
      }
      

      $template_replaces['ADMIN_PANEL_PAGE_WRAPPER'] = TemplateCollector::assembly_file_content($this->template, 'templates/page.tpl', [
        'ADMIN_PANEL_PAGE' => $site_page,
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

      if ($this->template->system_core->client->is_logged(2)) {
        $template_content = TemplateCollector::assembly_file_content($this->template, 'templates/documentBase.tpl', $template_replaces);
      } else {
        $template_content = TemplateCollector::assembly_file_content($this->template, 'templates/documentAuth.tpl', $template_replaces);
      }

      return $template_content;
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
      $this->template->add_style(['href' => 'styles/table.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/form.css', 'rel' => 'stylesheet']);
      $this->template->add_style(['href' => 'styles/modal.css', 'rel' => 'stylesheet']);

      $this->template->add_script(['src' => 'form.class.js'], true);
      $this->template->add_script(['src' => 'modal.class.js'], true);
      $this->template->add_script(['src' => 'common.js'], true);

      /** @var string $user_ip IP-адрес пользователя */
      $user_ip = $_SERVER['REMOTE_ADDR'];

      if ($this->template->system_core->client->is_logged(2)) {
        $this->template->add_style(['href' => 'styles/header.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/main.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/footer.css', 'rel' => 'stylesheet']);
        $this->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);

        /** @var string $this->assembled Итоговый шаблон в виде строки */
        $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
          'ADMIN_PANEL_HEADER' => $this->assembly_header(),
          'ADMIN_PANEL_MAIN' => $this->assembly_main(),
          'ADMIN_PANEL_FOOTER' => $this->assembly_footer()
        ]);
      } else {
        $this->template->add_style(['href' => 'styles/page/auth.css', 'rel' => 'stylesheet']);

        $this->assembled = TemplateCollector::assembly($this->assembly_document(), [
          'ADMIN_PANEL_HEADER' => '',
          'ADMIN_PANEL_MAIN' => $this->assembly_auth_admin_page(),
          'ADMIN_PANEL_FOOTER' => ''
        ]);
      }
    }

  }

}

?>