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
     * Получение массива дополнительных полей
     * 
     * @return array
     */
    private function get_additional_fields() : array {
      
    }
    
    /**
     * Сборка шаблона страницы
     *
     * @return void
     */
    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/page/profile.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'page.class.js', 'type' => 'module'], true);
      
      if ($this->system_core->client->is_logged(1)) {
        /**
         * @var string Логин пользователя
         */
        $profile_user_login = (!is_null($this->system_core->urlp->get_path(1))) ? $this->system_core->urlp->get_path(1) : null;
        
        /**
         * @var User Объект пользователя
         */
        $profile_user = null;
        if (User::exists_by_login($this->system_core, $profile_user_login)) {
          $profile_user = User::get_by_login($this->system_core, $profile_user_login);
          // Инициализация данных пользователя
          $profile_user->init_data(['login', 'email', 'metadata_json']);
        }
        
        if (!is_null($profile_user)) {
          if (!is_null($this->system_core->urlp->get_param('editor'))) {
            $fields_types = ($this->system_core->configurator->exists_database_entry_value('users_additional_field_type')) ? json_decode($this->system_core->configurator->get_database_entry_value('users_additional_field_type'), true) : [];
            $fields_titles = ($this->system_core->configurator->exists_database_entry_value('users_additional_field_title')) ? json_decode($this->system_core->configurator->get_database_entry_value('users_additional_field_title'), true) : [];
            $fields_names = ($this->system_core->configurator->exists_database_entry_value('users_additional_field_name')) ? json_decode($this->system_core->configurator->get_database_entry_value('users_additional_field_name'), true) : [];
            
            $cms_locale_setted = $this->system_core->configurator->get_database_entry_value('base_locale');

            $additional_fields_elements = [];
            foreach ($fields_types as $field_index => $field_type) {
              $field_name_exploded = explode('_', $fields_names[$field_index]);
              foreach ($field_name_exploded as $string_index => $string) {
                if ($string_index > 0) {
                  $field_name_exploded[$string_index] = ucfirst($string);
                }
              }
              $field_name_transformed = implode($field_name_exploded);

              if ($field_type == 'textarea') {
                array_push($additional_fields_elements, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/profile/editor/fieldTextarea.tpl', [
                  'FIELD_NAME' => $fields_names[$field_index],
                  'FIELD_TITLE' => $fields_titles[$cms_locale_setted][$field_index],
                  'FIELD_VALUE' => (!is_null($profile_user->get_additional_field_data($field_name_transformed))) ? $profile_user->get_additional_field_data($field_name_transformed) : ''
                ]));
              } else {
                array_push($additional_fields_elements, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/profile/editor/fieldInput.tpl', [
                  'FIELD_NAME' => $fields_names[$field_index],
                  'FIELD_TYPE' => $fields_types[$field_index],
                  'FIELD_TITLE' => $fields_titles[$cms_locale_setted][$field_index],
                  'FIELD_VALUE' => (!is_null($profile_user->get_additional_field_data($field_name_transformed))) ? $profile_user->get_additional_field_data($field_name_transformed) : ''
                ]));
              }
            }

            $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
              'PAGE_NAME' => 'profile',
              'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/profile/editor.tpl', [
                'USER_LOGIN' => $profile_user->get_login(),
                'USER_AVATAR_URL' => $profile_user->get_avatar_url(96),
                'USER_EMAIL' => $profile_user->get_email(),
                'USER_NAME' => $profile_user->get_name(),
                'USER_SURNAME' => $profile_user->get_surname(),
                'USER_PATRONYMIC' => $profile_user->get_patronymic(),
                'USER_BIRTHDATE' => date('d.m.Y', $profile_user->get_birthdate_unix_timestamp()),
                'PROFILE_ADDITIONAL_FIELDS' => implode($additional_fields_elements)
              ])
            ]);
          } else {
            $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
              'PAGE_NAME' => 'profile-editor',
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
          }
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