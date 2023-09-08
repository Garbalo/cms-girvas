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
  use \core\PHPLibrary\Entries as Entries;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\WebChannel\Importer as WebChannelImporter;

  class PageIndex implements InterfacePage {
    public SystemCore $system_core;
    public Page $page;
    public string $assembled = '';

    public function __construct(SystemCore $system_core, Page $page) {
      $this->system_core = $system_core;
      $this->page = $page;
    }

    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page/index.css', 'rel' => 'stylesheet']);

      $web_channel_importer = new WebChannelImporter($this->system_core, 'https://www.cms-girvas.ru/web-channel/latest-news');
      $web_channel_xml = $web_channel_importer->get();
      $web_channel_items_assembled = [];
      if (isset($web_channel_xml->channel->item)) {
        $count_max = 3; $item_index = 0;
        foreach ($web_channel_xml->channel->item as $item) {
          array_push($web_channel_items_assembled, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/webChannel/listItem.tpl', [
            'ITEM_TITLE' => $item->title,
            'ITEM_DESCRIPTION' => $item->description,
            'ITEM_LINK' => $item->link
          ]));

          if ($item_index == $count_max - 1) break;
          $item_index++;
        }
      }

      unset($web_channel_importer);
      unset($web_channel_xml);

      $web_channel_last_news_list = 'Записи не были получены.';
      if (count($web_channel_items_assembled) > 0) {
        $web_channel_last_news_list = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/webChannel/list.tpl', [
          'WEB_CHANNEL_ITEMS' => implode($web_channel_items_assembled)
        ]);
      }

      $web_channel_importer = new WebChannelImporter($this->system_core, 'https://www.cms-girvas.ru/web-channel/last-releases');
      $web_channel_xml = $web_channel_importer->get();
      $web_channel_items_assembled = [];
      if (isset($web_channel_xml->channel->item)) {
        $count_max = 3; $item_index = 0;
        foreach ($web_channel_xml->channel->item as $item) {
          array_push($web_channel_items_assembled, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/webChannel/listItem.tpl', [
            'ITEM_TITLE' => $item->title,
            'ITEM_DESCRIPTION' => $item->description,
            'ITEM_LINK' => $item->link
          ]));

          if ($item_index == $count_max - 1) break;
          $item_index++;
        }
      }

      unset($web_channel_importer);
      unset($web_channel_xml);

      $web_channel_last_releases_list = 'Записи не были получены.';
      if (count($web_channel_items_assembled) > 0) {
        $web_channel_last_releases_list = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index/webChannel/list.tpl', [
          'WEB_CHANNEL_ITEMS' => implode($web_channel_items_assembled)
        ]);
      }

      /** @var string $site_page Содержимое шаблона страницы */
      $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/index.tpl', [
        'ADMIN_PANEL_PAGE_NAME' => 'index',
        'WEB_CHANNEL_LATEST_NEWS_LIST' => $web_channel_last_news_list,
        'WEB_CHANNEL_LATEST_RELEASES_LIST' => $web_channel_last_releases_list,
      ]);
    }

  }

}

?>