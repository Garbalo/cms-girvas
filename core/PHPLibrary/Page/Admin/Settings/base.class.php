<?php

namespace core\PHPLibrary\Page\Admin\Settings {
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Template as Template;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class SettingsBase {
    const FORM_PATH = 'templates/page/settings';

    public SystemCore $system_core;
    public string $title;
    public string $description;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, ) {
      $this->system_core = $system_core;
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
      $settings_name = (!is_null($this->system_core->urlp->get_path(2))) ? $this->system_core->urlp->get_path(2) : 'base';
      $form_template_path = sprintf('%s/%s.tpl', self::FORM_PATH, $this->system_core->urlp->get_path(2));
      
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

      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, $form_template_path, [
        'SETTINGS_NAME' => $settings_name,
        'SETTING_CMS_TEMPLATES_OPTIONS' => implode($settings_cms_templates_options)
      ]);
    }

  }

}

?>