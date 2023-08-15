<?php

namespace core\PHPLibrary\Page\Admin\Settings {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\SystemCore\Locale as SystemCoreLocale;
  use \core\PHPLibrary\Template as Template;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class SettingsBase {
    const FORM_PATH = 'templates/page/settings';

    public SystemCore $system_core;
    public string $title;
    public string $name;
    public string $description;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, string $name) {
      $this->system_core = $system_core;
      $this->name = $name;
    }

    public function set_title(string $value) : void {
      $this->title = $value;
    }

    public function set_description(string $value) : void {
      $this->description = $value;
    }

    public function get_title() : string {
      return $this->title;
    }

    public function get_description() : string {
      return $this->description;
    }

    public function assembly(array $template_values = []) {
      $form_template_path = sprintf('%s/%s.tpl', self::FORM_PATH, $this->name);
      
      $settings_cms_templates_options = [];
      $uploaded_templates_names = $this->system_core->get_array_uploaded_templates_names();
      if (count($uploaded_templates_names) > 0) {
        foreach ($uploaded_templates_names as $setting_cms_template_name) {
          $template = new Template($this->system_core, $setting_cms_template_name);
          
          if ($template->exists_file_metadata_json()) {
            array_push($settings_cms_templates_options, TemplateCollector::assembly('<option value="{TEMPLATE_NAME}">{TEMPLATE_TITLE}</option>', [
              'TEMPLATE_NAME' => $template->get_name(),
              'TEMPLATE_TITLE' => $template->get_title()
            ]));
          }

          unset($template);
        }
      }

      $settings_cms_base_locales_options = [];
      $settings_cms_admin_locales_options = [];
      $cms_locales_names = $this->system_core->get_array_locales_names();
      if (count($cms_locales_names) > 0) {
        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new SystemCoreLocale($this->system_core, $cms_locale_name);

          if ($cms_locale->exists_file_metadata_json()) {
            if ($cms_locale_name == $this->system_core->configurator->get_database_entry_value('base_locale')) {
              array_unshift($settings_cms_base_locales_options, TemplateCollector::assembly('<option value="{LOCALE_NAME}">{LOCALE_TITLE}</option>', [
                'LOCALE_NAME' => $cms_locale->get_name(),
                'LOCALE_TITLE' => $cms_locale->get_title()
              ]));
            } else {
              array_push($settings_cms_base_locales_options, TemplateCollector::assembly('<option value="{LOCALE_NAME}">{LOCALE_TITLE}</option>', [
                'LOCALE_NAME' => $cms_locale->get_name(),
                'LOCALE_TITLE' => $cms_locale->get_title()
              ]));
            }
          }
        }

        foreach ($cms_locales_names as $index => $cms_locale_name) {
          $cms_locale = new SystemCoreLocale($this->system_core, $cms_locale_name);

          if ($cms_locale->exists_file_metadata_json()) {
            if ($cms_locale_name == $this->system_core->configurator->get_database_entry_value('base_admin_locale')) {
              array_unshift($settings_cms_admin_locales_options, TemplateCollector::assembly('<option value="{LOCALE_NAME}">{LOCALE_TITLE}</option>', [
                'LOCALE_NAME' => $cms_locale->get_name(),
                'LOCALE_TITLE' => $cms_locale->get_title()
              ]));
            } else {
              array_push($settings_cms_admin_locales_options, TemplateCollector::assembly('<option value="{LOCALE_NAME}">{LOCALE_TITLE}</option>', [
                'LOCALE_NAME' => $cms_locale->get_name(),
                'LOCALE_TITLE' => $cms_locale->get_title()
              ]));
            }
          }
        }
      }

      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, $form_template_path, [
        'SETTINGS_NAME' => $this->name,
        'SETTING_CMS_TEMPLATES_OPTIONS' => implode($settings_cms_templates_options),
        'SETTING_CMS_BASE_LOCALES_OPTIONS' => implode($settings_cms_base_locales_options),
        'SETTING_CMS_ADMIN_LOCALES_OPTIONS' => implode($settings_cms_admin_locales_options),
      ]);
    }

  }

}

?>