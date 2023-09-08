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

      $subpage_name = (!is_null($this->system_core->urlp->get_path(2))) ? $this->system_core->urlp->get_path(2) : 'list';

      $navigations_items_transformed = [];
      $navigations_items = ['list', 'groups'];
      foreach ($navigations_items as $navigation_item) {
        $item_class_is_active = ($subpage_name == $navigation_item) ? 'navigation-item__link_is-active' : '';

        array_push($navigations_items_transformed, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/item.tpl', [
          'NAVIGATION_ITEM_TITLE' => sprintf('{LANG:TEMPLATES_PAGE_%s_TITLE}', mb_strtoupper($navigation_item)),
          'NAVIGATION_ITEM_URL' => sprintf('/admin/users/%s', $navigation_item),
          'NAVIGATION_ITEM_LINK_CLASS_IS_ACTIVE' => $item_class_is_active
        ]));
      }

      if (!empty($navigations_items_transformed)) {
        $page_navigation_transformed = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal.tpl', [
          'NAVIGATION_LIST' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/navigationHorizontal/list.tpl', [
            'NAVIGATION_ITEMS' => implode($navigations_items_transformed)
          ])
        ]);
      } else {
        $page_navigation_transformed = '';
      }

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