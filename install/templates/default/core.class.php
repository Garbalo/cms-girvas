<?php

namespace install\templates\default {

  class Core extends \cron\library\Template {

    public function __construct($name, $path) {
      $this->set_path($path);
      $this->set_name($name);
      $this->connect_parent_class();
    }

    public function set_assembly() {

      require(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

      parent::add_style(['url' => sprintf('/install/templates/%s/styles/normalize.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/fonts.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/global.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/header.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/main.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/footer.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/table.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/install/templates/%s/styles/button.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);

      parent::add_javascript(['src' => '/cron/js-library/jquery-3.6.0.min.js']);
      parent::add_javascript(['src' => '/cron/js-library/form.js']);
      parent::add_javascript(['src' => sprintf('/install/templates/%s/js-library/installation-master.js', $this->name)]);

      $site_sections = [];
      for ($stage_index = 0; $stage_index < 6; $stage_index++) {
        $stage_patterns = [];

        if ($stage_index == 1) {
          $stage_patterns = array_merge($stage_patterns, [
            'PHP_VERSION_STATUS' => phpversion(),
            'EXTENSION_PDO_STATUS' => (extension_loaded('pdo'))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
            'EXTENSION_POSTGRESQL_STATUS' => (extension_loaded('pgsql'))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
            'EXTENSION_XML_STATUS' => (extension_loaded('xml'))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>'
          ]);
        }

        if ($stage_index == 2) {
          $stage_patterns = array_merge($stage_patterns, [
            'CHMOD_DIR_BACKUPS' => decoct(fileperms(sprintf('%s/backups', DOCUMENT_ROOT)) & 0777),
            'CHMOD_DIR_TEMPLATES' => decoct(fileperms(sprintf('%s/templates', DOCUMENT_ROOT)) & 0777),
            'CHMOD_DIR_TEMPLATES_DEFAULT' => decoct(fileperms(sprintf('%s/templates/default', DOCUMENT_ROOT)) & 0777),
            'CHMOD_DIR_UPLOADS' => decoct(fileperms(sprintf('%s/uploads', DOCUMENT_ROOT)) & 0777),
            'CHMOD_DIR_BACKUPS_RESULT' => (is_writable(sprintf('%s/backups', DOCUMENT_ROOT)))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
            'CHMOD_DIR_TEMPLATES_RESULT' => (is_writable(sprintf('%s/templates', DOCUMENT_ROOT)))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
            'CHMOD_DIR_TEMPLATES_DEFAULT_RESULT' => (is_writable(sprintf('%s/templates/default', DOCUMENT_ROOT)))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
            'CHMOD_DIR_UPLOADS_RESULT' => (is_writable(sprintf('%s/uploads', DOCUMENT_ROOT)))
              ? '<span style="color:green;">Да</span>'
              : '<span style="color:red;">Нет</span>',
          ]);
        }

        array_push($site_sections, parent::assembly(
          file_get_contents(sprintf('%s/templates/page/stage-%d.tpl', $this->path_to_template_folder, $stage_index + 1)), $stage_patterns
        ));
      }

      $site_header = parent::assembly(
        file_get_contents(sprintf('%s/templates/header.tpl', $this->path_to_template_folder)), [
          
        ]
      );

      $site_main = implode($site_sections);

      $site_footer = parent::assembly(
        file_get_contents(sprintf('%s/templates/footer.tpl', $this->path_to_template_folder)), [
          
        ]
      );

      $site_body = parent::assembly(
        file_get_contents(sprintf('%s/templates/main.tpl', $this->path_to_template_folder)), [
          'SITE_HEADER' => $site_header,
          'SITE_MAIN' => $site_main,
          'SITE_FOOTER' => $site_footer,
        ]
      );

      $this->assembled = parent::assembly(
        file_get_contents(sprintf('%s/templates/html.tpl', $this->path_to_template_folder)), [
          'SITE_TITLE' => (!isset($page_title)) ? 'Установка системы' : sprintf('%s | Установка системы', $page_title),
          'SITE_DESCRIPTION' => '',
          'SITE_KEYWORDS' => '',
          'SITE_BODY' => $site_body,
          'SITE_COPYRIGHT' => sprintf('Powered by &laquo%s&raquo;.', $_CMS['metadata']['title'])
        ]
      );
    }

    private function set_path($path) {
      $this->path_to_template_folder = $path;
    }

    private function set_name($name) {
        $this->name = $name;
    }

    private function connect_parent_class() {
        parent::__construct();
    }

  }

}

?>