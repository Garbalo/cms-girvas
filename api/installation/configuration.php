<?php
/**
 * ВНИМАНИЕ! Файл "configuration.sample.php" является образцом конфигурационного файла.
 * Вы можете сюда подставить Ваши данные и переименовать файл в "configuration.php".
 *
 * РЕКОМЕНДАЦИЯ: Не следует удалять файл "configuration.sample.php", поскольку конфигурационный
 * файл всегда можно будет вернуть в исходное состояние.
 */

$configuration = [
  'domain' => 'www.cms-girvas.ru',
  'domain_cookies' => 'www.cms-girvas.ru',
  'ssl_is_enabled' => true,
  'database' => [
    'host' => 'db_host',
    'user' => 'db_user',
    'password' => 'db_pass',
    'name' => 'db_name',
  ]
  'system_salt' => '?Q59DL+(/W29tM(aLij3D/X8',
  'password_hashing_algorithm' => PASSWORD_ARGON2ID,
  'session_expires' => 86400,
  'session_admin_expires' => 86400,
];
?>