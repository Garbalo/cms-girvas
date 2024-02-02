<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

use \core\PHPLibrary\User as User;
use \core\PHPLibrary\UserGroup as UserGroup;

if ($system_core->urlp->get_path(2) == 'request-password-reset') {
  $user_login_or_email = $_POST['user_login_or_email'];

  $user = null;
  if (filter_var($user_login_or_email, FILTER_VALIDATE_EMAIL)) {
    if (User::exists_by_email($system_core, $user_login_or_email)) {
      $user = User::get_by_email($system_core, $user_login_or_email);
    }
  } else {
    if (User::exists_by_login($system_core, $user_login_or_email)) {
      $user = User::get_by_login($system_core, $user_login_or_email);
    }
  }

  $user->init_data(['login', 'email', 'metadata_json']);

  if (!is_null($user)) {
    $user_email = $user->get_email();
    $user_login = $user->get_login();

    $template = new \core\PHPLibrary\Template($system_core, 'official');

    $email_sender = new \core\PHPLibrary\EmailSender($system_core);
    $email_sender->set_from_user('CMS GIRVAS', 'support@garbalo.com');
    $email_sender->set_to_user_email($user_email);
    $email_sender->add_header(sprintf("From: %s <%s>", 'CMS GIRVAS', 'support@garbalo.com'));
    $email_sender->add_header(sprintf("\r\nX-Mailer: PHP/%s", phpversion()));
    $email_sender->add_header("\r\nMIME-Version: 1.0");
    $email_sender->add_header("\r\nContent-type: text/html; charset=UTF-8");
    $email_sender->add_header("\r\n");

    $reset_password_created_unix_timestamp = time();
    $reset_password_token = md5($reset_password_created_unix_timestamp . $system_core::CMS_VERSION);

    $email_sender->set_subject('Восстановление пароля');
    $email_sender->set_content(\core\PHPLibrary\Template\Collector::assembly_file_content($template, 'templates/email/default.tpl', [
      'EMAIL_TITLE' => 'Запрос на восстановление пароля',
      'EMAIL_CONTENT' => sprintf('%s, здравствуйте! Для восстановления пароля пройдите по следующей ссылке: %s', $user_login, sprintf('%s/password-reset?token=%s', $system_core->get_site_url(), $reset_password_token)),
      'EMAIL_COPYRIGHT' => 'С уважением, администрация сайта.'
    ]));

    $email_sender->send();

    $reset_password_created_unix_timestamp = time();
    $user->update(['metadata_json' => ['passwordResetToken' => $reset_password_token, 'passwordResetTokenCreatedUnixTimestamp' => $reset_password_created_unix_timestamp]]);

    $handler_message = 'Запрос на восстановление успешно отправлен.';
    $handler_status_code = 1;
  } else {
    $handler_message = 'Запрос на восстановление отправить невозможно. Такого пользователя не существует.';
    $handler_status_code = 0;
  }
}

?>