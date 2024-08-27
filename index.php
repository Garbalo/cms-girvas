<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

date_default_timezone_set('Europe/Moscow');

define('CMS_ROOT_DIRECTORY', $_SERVER['DOCUMENT_ROOT']);
define('IS_NOT_HACKED', true);

require_once(sprintf('%s/core/PHPLibrary/systemCore.class.php', CMS_ROOT_DIRECTORY));

$system_core = new \core\PHPLibrary\SystemCore();

if ($system_core->urlp->get_path(0) == 'handler') {

  include_once(sprintf('%s/handler.php', CMS_ROOT_DIRECTORY));

} else if ($system_core->urlp->get_path(0) == 'sitemap') {

  include_once(sprintf('%s/sitemap.php', CMS_ROOT_DIRECTORY));

} else if ($system_core->urlp->get_path(0) == 'rss') {

  include_once(sprintf('%s/rss.php', CMS_ROOT_DIRECTORY));

} else if ($system_core->urlp->get_path(0) == 'feed') {

  include_once(sprintf('%s/feed.php', CMS_ROOT_DIRECTORY));

} else if ($system_core->urlp->get_path(0) == 'password-reset') {

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_select();
  $query_builder->statement->add_selections(['id']);
  $query_builder->statement->set_clause_from();
  $query_builder->statement->clause_from->add_table('users');
  $query_builder->statement->clause_from->assembly();
  $query_builder->statement->set_clause_where();
  $query_builder->statement->clause_where->add_condition(sprintf('metadata::jsonb->>\'passwordResetToken\' = \'%s\'', $system_core->urlp->get_param('token')));
  $query_builder->statement->clause_where->assembly();
  $query_builder->statement->set_clause_limit(1);
  $query_builder->statement->assembly();

  $database_connection = $system_core->database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);
  $database_query->execute();

  $result = $database_query->fetch(\PDO::FETCH_ASSOC);

  $user = ($result) ? new \core\PHPLibrary\User($system_core, (int)$result['id']) : null;

  if (!is_null($user)) {
    $user->init_data(['login', 'email', 'metadata', 'security_hash']);
    
    if ($user->get_password_reset_created_unix_timestamp() + 600 > time()) {
      $user_new_password = random_int(10000000, 99999999);
      $user->update(['password_hash' => \core\PHPLibrary\User::password_hash($system_core, $user->get_security_hash(), $user_new_password), 'metadata' => ['passwordResetToken' => '', 'passwordResetTokenCreatedTimestamp' => 0]]);
      
      $template_base_name = ($system_core->configurator->exists_database_entry_value('base_template')) ? $system_core->configurator->get_database_entry_value('base_template') : 'default';

      $template = new \core\PHPLibrary\Template($system_core, $template_base_name);

      $email_sender = new \core\PHPLibrary\EmailSender($system_core);
      $email_sender->set_from_user('CMS GIRVAS', 'support@garbalo.com');
      $email_sender->set_to_user_email($user->get_email());
      $email_sender->add_header(sprintf("From: %s <%s>", 'CMS GIRVAS', 'support@garbalo.com'));
      $email_sender->add_header(sprintf("\r\nX-Mailer: PHP/%s", phpversion()));
      $email_sender->add_header("\r\nMIME-Version: 1.0");
      $email_sender->add_header("\r\nContent-type: text/html; charset=UTF-8");
      $email_sender->add_header("\r\n");

      $reset_password_created_timestamp = time();
      $reset_password_token = md5($reset_password_created_timestamp . $system_core::CMS_VERSION);

      $email_sender->set_subject('Новый пароль');
      $email_sender->set_content(\core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/email/default.tpl', [
        'EMAIL_TITLE' => 'Новый пароль',
        'EMAIL_CONTENT' => sprintf('%s, здравствуйте! Используйте свой новый пароль для авторизации: <b>%d</b>. После авторизации рекомендуем сразу же его сменить.', $user->get_login(), $user_new_password),
        'EMAIL_COPYRIGHT' => 'С уважением, администрация сайта.'
      ]));

      $email_sender->send();

      

      echo 'Your password reseted!';
    
    } else {
      echo 'Application is out of date!';
    }

  } else {
    echo 'Request is not exists!';
  }

} else {

  if ($system_core->urlp->get_param('mode') != 'install' && file_exists(sprintf('%s/INSTALLED', CMS_ROOT_DIRECTORY))) {
    if ($system_core->configurator->get_database_entry_value('security_allowed_admin_ip_status') == 'on' && $system_core->urlp->get_path(0) == 'admin') {
      $allowed_ips = json_decode($system_core->configurator->get_database_entry_value('security_allowed_admin_ip'), true);
      if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        http_response_code(503);
        die('An attempted hacker attack has been detected.');
      }
    }
  }

  $template = $system_core->get_template();
  $template->assembly_global_variables();
  echo $template->core->assembled;

}

?>