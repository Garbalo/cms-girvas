<?php

namespace core\PHPLibrary\Page\Admin {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\WebChannels as WebChannels;
  use \core\PHPLibrary\WebChannel\Builder as WebChannelBuilder;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;

  class PageWebChannels implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/webChannels.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_script(['src' => 'admin/page/webChannels.js'], true);

      $web_channels_table_items_assembled_array = [];
      $web_channels = new WebChannels($this->system_core);
      $web_channels_array_objects = $web_channels->get_all();
      unset($web_channels);

      foreach ($web_channels_array_objects as $web_channel_index => $web_channel_object) {
        $web_channel_object->init_data(['id', 'name', 'type_id', 'texts', 'created_unix_timestamp', 'updated_unix_timestamp']);

        $web_channel_created_date_timestamp = date('d.m.Y H:i:s', $web_channel_object->get_created_unix_timestamp());
        $web_channel_updated_date_timestamp = date('d.m.Y H:i:s', $web_channel_object->get_updated_unix_timestamp());

        array_push($web_channels_table_items_assembled_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/webChannels/tableItem.tpl', [
          'WEB_CHANNEL_ID' => $web_channel_object->get_id(),
          'WEB_CHANNEL_INDEX' => $web_channel_index + 1,
          'WEB_CHANNEL_NAME' => $web_channel_object->get_name(),
          'WEB_CHANNEL_TITLE' => $web_channel_object->get_title(),
          'WEB_CHANNEL_TYPE_TITLE' => WebChannelBuilder::get_type_title($web_channel_object->get_type_id()),
          'WEB_CHANNEL_CREATED_DATE_TIMESTAMP' => $web_channel_created_date_timestamp,
          'WEB_CHANNEL_UPDATED_DATE_TIMESTAMP' => $web_channel_updated_date_timestamp
        ]));
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/webChannels.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'web-channels',
        'ADMIN_PANEL_WEB_CHANNELS_TABLE' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/webChannels/table.tpl', [
          'ADMIN_PANEL_WEB_CHANNELS_TABLE_ITEMS' => implode($web_channels_table_items_assembled_array)
        ])
      ]);
    }

  }

}

?>