<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\WebChannel as WebChannel;
  use \core\PHPLibrary\WebChannel\Builder as WebChannelBuilder;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageWebChannel implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/webChannel.css', 'rel' => 'stylesheet']);
      
      $this->system_core->template->add_script(['src' => 'admin/page/webChannel.js', 'type' => 'module'], true);

      $web_channel = null;
      if (!is_null($this->system_core->urlp->get_path(2))) {
        $web_channel_id = (is_numeric($this->system_core->urlp->get_path(2))) ? (int)$this->system_core->urlp->get_path(2) : 0;
        $web_channel = (WebChannel::exists_by_id($this->system_core, $web_channel_id)) ? new WebChannel($this->system_core, $web_channel_id) : null;
        
        if (!is_null($web_channel_id)) {
          $web_channel->init_data(['id', 'name', 'entries_category_id', 'type_id', 'texts']);
        }
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/webChannel.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'web-channel',
        'WEB_CHANNEL_ID' => (!is_null($web_channel)) ? $web_channel->get_id() : 0,
        'WEB_CHANNEL_NAME' => (!is_null($web_channel)) ? $web_channel->get_name() : '',
        'WEB_CHANNEL_TITLE' => (!is_null($web_channel)) ? $web_channel->get_title() : '',
        'WEB_CHANNEL_DESCRIPTION' => (!is_null($web_channel)) ? $web_channel->get_description() : '',
        'WEB_CHANNEL_FORM_METHOD' => (!is_null($web_channel)) ? 'PATCH' : 'PUT',
      ]);
    }

  }

}

?>