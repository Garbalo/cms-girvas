<?php

/**
 * ВНИМАНИЕ! Файл "configuration.sample.php" является образцом конфигурационного файла.
 * Вы можете сюда подставить Ваши данные и переименовать файл в "configuration.php".
 * 
 * РЕКОМЕНДАЦИЯ: Не следует удалять файл "configuration.sample.php", поскольку конфигурационный
 * файл всегда можно будет вернуть в исходное состояние.
 */

$configuration = [
  'domain' => 'www.example.com',
  'domain_cookies' => 'www.example.com',
  'database' => ['host' => '', 'user' => '', 'password' => '', 'name' => ''],
  // Системная соль (необходима для хеширования некоторых данных)
  // Пример: ?d7R(TF1f30br7tl=!PeIrk) <== (НЕ ИСПОЛЬЗУЙТЕ ЭТУ СОЛЬ)
  'system_salt' => '',
  // Алгоритм хеширования пароля (PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID)
  // Подробнее: https://www.php.net/manual/en/function.password-hash.php
  'password_hashing_algorithm' => PASSWORD_DEFAULT,
  'session_expires' => 86400,
  'session_admin_expires' => 86400,
];

?>