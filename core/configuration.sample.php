<?php

/**
 * ВНИМАНИЕ! Файл "configuration.sample.php" является образцом конфигурационного файла.
 * Вы можете сюда подставить Ваши данные и переименовать файл в "configuration.php".
 * 
 * РЕКОМЕНДАЦИЯ: Не следует удалять файл "configuration.sample.php", поскольку конфигурационный
 * файл всегда можно будет вернуть в исходное состояние.
 */

$configuration = [
  'database' => ['host' => '', 'user' => '', 'password' => '', 'name' => ''],
  'seo_base' => [
    // Наименование сайта
    'title' => '',
    // Описание стартовой страницы или любой другой, где нет описания
    'description' => 'CMS GIRVAS - многопрофильная бесплатная система управления содержимым от карельских разработчиков Garbalo.',
    // Ключевые слова, по которым поисковые роботы смогут найти сайт
    'keywords' => ['cms girvas', 'система управления содержимым', 'content management system', 'бесплатная cms', 'garbalo']
  ],
  // Системная соль (необходима для хеширования некоторых данных)
  // Пример: ?d7R(TF1f30br7tl=!PeIrk) <== (НЕ ИСПОЛЬЗУЙТЕ ЭТУ СОЛЬ)
  'system_salt' => '',
  // Алгоритм хеширования пароля (PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID)
  // Подробнее: https://www.php.net/manual/en/function.password-hash.php
  'password_hashing_algorithm' => PASSWORD_DEFAULT 
];

?>