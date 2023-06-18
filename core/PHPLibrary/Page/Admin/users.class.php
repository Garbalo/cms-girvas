<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Users as Users;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageUsers implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/users.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/users.js'], true);

      $users_table_items_assembled_array = [];
      $users = new Users($this->system_core);
      $users_array_objects = $users->get_all();
      unset($users);

      $user_number = 1;
      foreach ($users_array_objects as $user_object) {
        $user_object->init_data(['id', 'login', 'email', 'created_unix_timestamp', 'updated_unix_timestamp']);

        $user_created_date_timestamp = date('d.m.Y H:i:s', $user_object->get_created_unix_timestamp());
        $user_updated_date_timestamp = date('d.m.Y H:i:s', $user_object->get_updated_unix_timestamp());

        array_push($users_table_items_assembled_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/users/tableItem.tpl', [
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
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/users.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'users',
        'ADMIN_PANEL_USERS_TABLE' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/users/table.tpl', [
          'ADMIN_PANEL_USERS_TABLE_ITEMS' => implode($users_table_items_assembled_array)
        ])
      ]);
    }

  }

}

?>