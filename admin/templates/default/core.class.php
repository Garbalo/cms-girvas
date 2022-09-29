<?php

namespace admin\templates\default {

  class Core extends \cron\library\Template {

    public function __construct($name, $path) {
      $this->set_path($path);
      $this->set_name($name);
      $this->connect_parent_class();
    }

    public function set_assembly() {

      require(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/normalize.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/fonts.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/global.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/header.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/main.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/footer.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/table.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/admin/templates/%s/styles/button.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);

      parent::add_javascript(['src' => '/cron/js-library/jquery-3.6.0.min.js']);
      parent::add_javascript(['src' => '/cron/js-library/form.js']);

      $site_header = parent::assembly(
        file_get_contents(sprintf('%s/templates/header.tpl', $this->path_to_template_folder)), [
          
        ]
      );

      $site_main = 'Test content';

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
          'SITE_TITLE' => (!isset($page_title)) ? 'Панель управления' : sprintf('%s | Панель управления', $page_title),
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