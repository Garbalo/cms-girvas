<?php

namespace templates\default {

  class Core extends \cron\library\Template {

    public function __construct($name, $path) {
      $this->set_path($path);
      $this->set_name($name);
      $this->connect_parent_class();
    }

    public function set_assembly() {

      require(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

      parent::add_style(['url' => sprintf('/templates/%s/styles/normalize.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/templates/%s/styles/global.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/templates/%s/styles/header.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/templates/%s/styles/main.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);
      parent::add_style(['url' => sprintf('/templates/%s/styles/footer.css', $this->name), 'attr' => ['rel' => 'stylesheet']]);

      parent::add_javascript(['src' => '/cron/js-library/jquery-3.6.0.min.js']);

      if (!is_null($this->surl->get_path(0))) {

        $site_header = parent::assembly(
          file_get_contents(sprintf('%s/templates/header.tpl', $this->path_to_template_folder)), [
            'NAVIGATION' => parent::assembly(
              file_get_contents(sprintf('%s/templates/header/navigation.tpl', $this->path_to_template_folder)), [
                
              ]
            )
          ]
        );

        if ($this->surl->get_path(0) == 'entries') {

          $site_main = parent::assembly(
            file_get_contents(sprintf('%s/templates/page/entries.tpl', $this->path_to_template_folder)), [
              
            ]
          );

        } else if ($this->surl->get_path(0) == 'projects') {



        } else if ($this->surl->get_path(0) == 'about') {



        } else {

          http_response_code(404);

          $site_main = parent::assembly(
            file_get_contents(sprintf('%s/templates/page.tpl', $this->path_to_template_folder)), [
              'PAGE' => parent::assembly(
                file_get_contents(sprintf('%s/templates/page/404.tpl', $this->path_to_template_folder)), [
                  
                ]
              )
            ]
          );

        }

        $site_footer = parent::assembly(
          file_get_contents(sprintf('%s/templates/footer.tpl', $this->path_to_template_folder)), [
            
          ]
        );

      } else {

        $site_header = parent::assembly(
          file_get_contents(sprintf('%s/templates/page/index/header.tpl', $this->path_to_template_folder)), [
            'NAVIGATION' => parent::assembly(
              file_get_contents(sprintf('%s/templates/header/navigation.tpl', $this->path_to_template_folder)), [
                
              ]
            )
          ]
        );

        $site_main = parent::assembly(
          file_get_contents(sprintf('%s/templates/page.tpl', $this->path_to_template_folder)), [
            'PAGE' => parent::assembly(
              file_get_contents(sprintf('%s/templates/page/index.tpl', $this->path_to_template_folder)), [
                
              ]
            )
          ]
        );

        $site_footer = parent::assembly(
          file_get_contents(sprintf('%s/templates/footer.tpl', $this->path_to_template_folder)), [
            
          ]
        );

      }

      $site_body = parent::assembly(
        file_get_contents(sprintf('%s/templates/main.tpl', $this->path_to_template_folder)), [
          'SITE_HEADER' => $site_header,
          'SITE_MAIN' => $site_main,
          'SITE_FOOTER' => $site_footer,
        ]
      );

      $this->assembled = parent::assembly(
        file_get_contents(sprintf('%s/templates/html.tpl', $this->path_to_template_folder)), [
          'SITE_TITLE' => (!isset($page_title)) ? 'DRELAGAS.RU' : sprintf('%s | DRELAGAS.RU', $page_title),
          'SITE_DESCRIPTION' => (!isset($site_description)) ? $_CMS['seo_default']['description'] : $site_description,
          'SITE_KEYWORDS' => (!isset($site_keywords)) ? $_CMS['seo_default']['keywords'] : $site_keywords,
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