<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\UsersGroups as UsersGroups;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageUsersGroups implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/usersGroups.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/usersGroups.js'], true);

      $users_groups_table_items_assembled_array = [];
      $users_groups = new UsersGroups($this->system_core);
      $users_groups_array_objects = $users_groups->get_all();
      unset($users_groups);

      $user_group_number = 1;
      foreach ($users_groups_array_objects as $user_group_object) {
        $user_group_object->init_data(['id', 'name', 'metadata_json', 'created_unix_timestamp', 'updated_unix_timestamp']);

        $user_group_created_date_timestamp = date('d.m.Y H:i:s', $user_group_object->get_created_unix_timestamp());
        $user_group_updated_date_timestamp = date('d.m.Y H:i:s', $user_group_object->get_updated_unix_timestamp());

        array_push($users_groups_table_items_assembled_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/usersGroups/tableItem.tpl', [
          'USER_GROUP_ID' => $user_group_object->get_id(),
          'USER_GROUP_INDEX' => $user_group_number,
          'USER_GROUP_NAME' => $user_group_object->get_name(),
          'USER_GROUP_TITLE' => $user_group_object->get_title(),
          'USER_GROUP_USERS_COUNT' => $user_group_object->get_users_count(),
          'USER_GROUP_CREATED_DATE_TIMESTAMP' => $user_group_created_date_timestamp,
          'USER_GROUP_UPDATED_DATE_TIMESTAMP' => $user_group_updated_date_timestamp
        ]));

        $user_group_number++;
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/usersGroups.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'users-groups',
        'ADMIN_PANEL_USERS_GROUPS_TABLE' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/usersGroups/table.tpl', [
          'ADMIN_PANEL_USERS_GROUPS_TABLE_ITEMS' => implode($users_groups_table_items_assembled_array)
        ])
      ]);
    }

  }

}

?>