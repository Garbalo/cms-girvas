<?php

if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

if (defined('IS_NOT_HACKED')) {
  $sitemap_builder = new \core\PHPLibrary\SitemapBuilder($system_core);

  $entries = new \core\PHPLibrary\Entries($system_core);
  foreach ($entries->get_all() as $entry) {
    $entry->init_data(['name', 'updated_unix_timestamp']);
    $sitemap_builder->add_url(
      sprintf('/entry/%s', $entry->get_name()),
      $entry->get_updated_unix_timestamp(),
      'weekly',
      0.8
    );
  }

  $pages_static = new \core\PHPLibrary\Pages($system_core);
  foreach ($pages_static->get_all() as $page_static) {
    $page_static->init_data(['name', 'updated_unix_timestamp']);
    $sitemap_builder->add_url(
      sprintf('/page/%s', $page_static->get_name()),
      $page_static->get_updated_unix_timestamp(),
      'monthly',
      0.5
    );
  }

  header('Content-type: text/xml');

  $sitemap_builder->assembly();

  echo $sitemap_builder->assembled;
}

?>