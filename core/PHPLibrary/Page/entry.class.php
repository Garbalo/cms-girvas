<?php

namespace core\PHPLibrary\Page {
  use \core\PHPLibrary\InterfacePage as InterfacePage;
  use \core\PHPLibrary\SystemCore as SystemCore;
  use \core\PHPLibrary\Page as Page;
  use \core\PHPLibrary\Parsedown as Parsedown;
  use \core\PHPLibrary\Entry as Entry;
  use \core\PHPLibrary\SystemCore\Locale as SystemCoreLocale;
  use \core\PHPLibrary\Template\Collector as TemplateCollector;

  class PageEntry implements InterfacePage {
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
     * Сборка шаблона страницы
     *
     * @return void
     */
    public function assembly() : void {
      $this->system_core->template->add_style(['href' => 'styles/page.css', 'rel' => 'stylesheet']);
      $this->system_core->template->add_style(['href' => 'styles/page/entry.css', 'rel' => 'stylesheet']);

      if (!is_null($this->system_core->urlp->get_path(1))) {
        $entry_name = urldecode($this->system_core->urlp->get_path(1));

        if (Entry::exists_by_name($this->system_core, $entry_name)) {
          http_response_code(200);

          $cms_base_locale_setted_name = $this->system_core->configurator->get_database_entry_value('base_locale');
          $cms_base_locale_name = (!is_null($this->system_core->urlp->get_param('locale'))) ? $this->system_core->urlp->get_param('locale') : $cms_base_locale_setted_name;
          $cms_base_locale = new SystemCoreLocale($this->system_core, $cms_base_locale_name);
          if (!$cms_base_locale->exists_file_data_json()) {
            $cms_base_locale_name = $cms_base_locale_setted_name;
          }

          $entry = Entry::get_by_name($this->system_core, $entry_name);
          $entry->init_data(['id', 'category_id', 'texts', 'name']);
          $entry_category = $entry->get_category();
          $entry_category_title = $entry_category->get_title($cms_base_locale_name);

          $this->system_core->configurator->set_meta_title($entry->get_title($cms_base_locale_name));
          $this->system_core->configurator->set_meta_description($entry->get_description($cms_base_locale_name));

          $this->page->breadcrumbs->add('Все записи', '/entries');
          $this->page->breadcrumbs->add($entry_category_title, sprintf('/entries/%s', $entry_category->get_name()));
          $this->page->breadcrumbs->add($entry->get_title($cms_base_locale_name), sprintf('/entry/%s', $entry->get_name()));
          $this->page->breadcrumbs->assembly();

          $entry_comments_array = $entry->get_comments();
          $entry_comments_transformed_array = [];
          $entry_comment_index = 1;
          foreach ($entry_comments_array as $entry_comment) {
            $entry_comment->init_data(['entry_id', 'author_id', 'content', 'created_unix_timestamp']);
            $entry_comment_author = $entry_comment->get_author();
            $entry_comment_author->init_data(['login']);
            array_push($entry_comments_transformed_array, TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry/comment.tpl', [
              'COMMENT_INDEX' => $entry_comment_index,
              'COMMENT_CREATED_DATE_TIMESTAMP' => date('d.m.Y H:i:s', $entry_comment->get_created_unix_timestamp()),
              'COMMENT_AUTHOR_LOGIN' => $entry_comment_author->get_login(),
              'COMMENT_AUTHOR_AVATAR_URL' => $entry_comment_author->get_avatar_url(64),
              'COMMENT_CONTENT' => $entry_comment->get_content()
            ]));

            $entry_comment_index++;
          }

          if (count($entry_comments_array) > 0) {
            $entry_comments_transformed = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry/commentsList.tpl', [
              'COMMENTS_ITEMS' => implode($entry_comments_transformed_array)
            ]);
          }

          $parsedown = new Parsedown();

          $this->assembled = TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page.tpl', [
            'PAGE_NAME' => 'entry',
            'PAGE_CONTENT' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry.tpl', [
              'PAGE_BREADCRUMPS' => $this->page->breadcrumbs->assembled,
              'ENTRY_TITLE' => $entry->get_title($cms_base_locale_name),
              'ENTRY_CONTENT' => $parsedown->text($entry->get_content($cms_base_locale_name)),
              'ENTRY_COMMENT_FORM' => TemplateCollector::assembly_file_content($this->system_core->template, 'templates/page/entry/commentForm.tpl', [
                'ENTRY_ID' => $entry->get_id(),
              ]),
              'ENTRY_COMMENTS_LIST' => (count($entry_comments_array) > 0) ? $entry_comments_transformed : 'К этой записи нет комментариев.'
            ])
          ]);
        } else {
          http_response_code(404);

          $page_error = new PageError($this->system_core, $this->page, 404);
          $page_error->assembly();
          $this->assembled = $page_error->assembled;
        }
      } else {
        http_response_code(404);

        $page_error = new PageError($this->system_core, $this->page, 404);
        $page_error->assembly();
        $this->assembled = $page_error->assembled;
      }
    }

  }

}

?>