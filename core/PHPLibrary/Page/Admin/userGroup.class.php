<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\UserGroup as UserGroup;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageUserGroup implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/userGroup.css', 'rel' => 'stylesheet']);

      $user_group = null;
      if (!is_null($this->system_core->urlp->get_path(2))) {
        $user_group_id = (is_numeric($this->system_core->urlp->get_path(2))) ? (int)$this->system_core->urlp->get_path(2) : 0;
        $user_group = (UserGroup::exists_by_id($this->system_core, $user_group_id)) ? new UserGroup($this->system_core, $user_group_id) : null;
        
        if (!is_null($user_group)) {
          $user_group->init_data(['id', 'name', 'permissions']);
        }
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/userGroup.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'user-group',
        'USER_GROUP_ID' => (!is_null($user_group)) ? $user_group->get_id() : 0,
        'USER_GROUP_NAME' => (!is_null($user_group)) ? $user_group->get_name() : '',
        'USER_GROUP_FORM_METHOD' => (!is_null($user_group)) ? 'PATCH' : 'PUT',
        'USER_GROUP_PERMISSION_ADMIN_PANEL_AUTH_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_PANEL_AUTH)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_USERS_EDIT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_USERS_EDIT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_USERS_GROUPS_EDIT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_USERS_GROUPS_EDIT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_MODULES_MANAGEMENT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_MODULES_MANAGEMENT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_TEMPLATES_MANAGEMENT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_TEMPLATES_MANAGEMENT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_SETTINGS_MANAGEMENT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_SETTINGS_MANAGEMENT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_ADMIN_VIEWING_LOGS_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_ADMIN_VIEWING_LOGS)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_MODER_USERS_BAN_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_MODER_USERS_BAN)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_MODER_USERS_WARNS_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_MODER_USERS_WARNS)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_EDITOR_MEDIA_FILES_MANAGEMENT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_EDITOR_MEDIA_FILES_MANAGEMENT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_EDITOR_ENTRIES_EDIT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_EDITOR_ENTRIES_EDIT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT)) ? 'checked' : '',
        'USER_GROUP_PERMISSION_EDITOR_PAGES_STATIC_EDIT_VALUE' => ($user_group->permission_check(UserGroup::PERMISSION_EDITOR_PAGES_STATIC_EDIT)) ? 'checked' : '',
      ]);
    }

  }

}

?>