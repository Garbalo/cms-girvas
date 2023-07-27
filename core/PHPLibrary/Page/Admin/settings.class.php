<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageSettings implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/settings.css', 'rel' => 'stylesheet']);
      
      $this->system_core->template->add_script(['src' => 'admin/page/settings.js'], true);

      $settings_name = (!is_null($this->system_core->urlp->get_path(2))) ? $this->system_core->urlp->get_path(2) : 'base';
      $settings_template_form_path = sprintf('templates/page/settings/%s.tpl', $settings_name);
      //$settings_style_form_path = sprintf('%s/styles/page/settings/%s.css', $template_path, $settings_name);

      $settings_core_path = sprintf('%s/core/PHPLibrary/Page/Admin/Settings/%s.class.php', $this->system_core->get_cms_path(), $settings_name);
      if (file_exists($settings_core_path)) {
        http_response_code(200);

        $class_namespace = sprintf('\\core\\PHPLibrary\\Page\\Admin\\Settings\\Settings%s', ucfirst($settings_name));
        $settings = new $class_namespace($this->system_core);

        if ($settings_name == 'base') {
          $settings->set_title('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_MAIN_TITLE}');
          $settings->set_description('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_MAIN_DESCRIPTION}');
        }

        if ($settings_name == 'seo') {
          $settings->set_title('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_SEO_TITLE}');
          $settings->set_description('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_SEO_DESCRIPTION}');
        }

        if ($settings_name == 'security') {
          $settings->set_title('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_SECURITY_TITLE}');
          $settings->set_description('{LANG:SETTINGS_PAGE_SETTINGS_GROUP_SECURITY_DESCRIPTION}');
        }

        $settings_title = $settings->get_title();
        $settings_description = $settings->get_description();
        $settings->assembly();
      } else {
        http_response_code(404);
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/settings.tpl', [
        'SETTINGS_TITLE' => (isset($settings_title)) ? $settings_title : 'Неизвестные настройки',
        'SETTINGS_DESCRIPTION' => (isset($settings_description)) ? $settings_description : 'Настройки не найдены',
        'SETTINGS_FORM' => TemplateCollector::assembly($settings->assembled, [

        ])
      ]);
    }

  }

}

?>