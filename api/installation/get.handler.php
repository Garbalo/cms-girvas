<?php

/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2023, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

if (!defined('IS_NOT_HACKED')) {
  http_response_code(503);
  die('An attempted hacker attack has been detected.');
}

if ($system_core->urlp->get_param('stepIndex') == 1) {
  $dom_document = new \DOMDocument();
  
  $php_loaded_extensions = get_loaded_extensions();

  $table_data = [
    ['Версия PHP', '>= 8.2.6', phpversion()],
    ['PHP-модуль SimpleXML', 'Включен', (in_array('SimpleXML', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PHP-модуль PDO', 'Включен', (in_array('PDO', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PHP-модуль curl', 'Включен', (in_array('curl', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PHP-модуль dom', 'Включен', (in_array('dom', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PHP-модуль mbstring', 'Включен', (in_array('mbstring', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PHP-модуль json', 'Включен', (in_array('json', $php_loaded_extensions) ? 'Включен' : 'Выключен')]
  ];

  $table_cells_font_color = [
    '',
    (in_array('SimpleXML', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('PDO', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('curl', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('dom', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('mbstring', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('json', $php_loaded_extensions) ? '#209A20' : '#9A2020')
  ];

  $table = $dom_document->createElement('table');
  $table->setAttribute('class', 'table');
  
  $table_row_header = $dom_document->createElement('tr');

  $table_cells_headers = [];
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');

  foreach ($table_cells_headers as $table_cell) {
    $table_cell->setAttribute('class', 'table__cell table__cell_header');
  }

  $table_cells_headers[0]->nodeValue = 'Наименование';
  $table_cells_headers[1]->nodeValue = 'Ожидание';
  $table_cells_headers[2]->nodeValue = 'Факт';

  $table_row_header->appendChild($table_cells_headers[0]);
  $table_row_header->appendChild($table_cells_headers[1]);
  $table_row_header->appendChild($table_cells_headers[2]);

  $table->appendChild($table_row_header);

  foreach ($table_data as $data_array_index => $data_array) {
    $table_row = $dom_document->createElement('tr');

    $table_cells = [];
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');

    foreach ($table_cells as $table_cell_index => $table_cell) {
      $table_cell->setAttribute('class', 'table__cell');

      if ($table_cell_index == 2 && !empty($table_cells_font_color[$data_array_index])) {
        $table_cell->setAttribute('style', sprintf('color: %s; border-color: inherit;', $table_cells_font_color[$data_array_index]));
      }
    }

    foreach ($data_array as $array_value_index => $array_value) {
      $table_cells[$array_value_index]->nodeValue = $data_array[$array_value_index];
      $table_row->appendChild($table_cells[$array_value_index]);
    }

    $table->appendChild($table_row);
  }
  
  $dom_document->appendChild($table);

  $handler_output_data['html'] = $dom_document->saveHTML();

  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

if ($system_core->urlp->get_param('stepIndex') == 2) {
  $dom_document = new \DOMDocument();
  
  $table_data = [
    ['./backups/', (file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY))) ? 'Директория обнаружена' : 'Директория не обнаружена'],
    ['./modules/', (file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY))) ? 'Директория обнаружена' : 'Директория не обнаружена'],
    ['./templates/', (file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY))) ? 'Директория обнаружена' : 'Директория не обнаружена'],
    ['./uploads/', (file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY))) ? 'Директория обнаружена' : 'Директория не обнаружена'],
  ];

  $table_cells_font_color = [
    (file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY)) ? '#209A20' : '#9A2020'),
    (file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY)) ? '#209A20' : '#9A2020'),
    (file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY)) ? '#209A20' : '#9A2020'),
    (file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY)) ? '#209A20' : '#9A2020'),
  ];

  $table = $dom_document->createElement('table');
  $table->setAttribute('class', 'table');
  
  $table_row_header = $dom_document->createElement('tr');

  $table_cells_headers = [];
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');

  foreach ($table_cells_headers as $table_cell) {
    $table_cell->setAttribute('class', 'table__cell table__cell_header');
  }

  $table_cells_headers[0]->nodeValue = 'Наименование';
  $table_cells_headers[1]->nodeValue = 'Факт';

  $table_row_header->appendChild($table_cells_headers[0]);
  $table_row_header->appendChild($table_cells_headers[1]);

  $table->appendChild($table_row_header);

  foreach ($table_data as $data_array_index => $data_array) {
    $table_row = $dom_document->createElement('tr');

    $table_cells = [];
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');

    foreach ($table_cells as $table_cell_index => $table_cell) {
      $table_cell->setAttribute('class', 'table__cell');

      if ($table_cell_index == 1 && !empty($table_cells_font_color[$data_array_index])) {
        $table_cell->setAttribute('style', sprintf('color: %s; border-color: inherit;', $table_cells_font_color[$data_array_index]));
      }
    }

    foreach ($data_array as $array_value_index => $array_value) {
      $table_cells[$array_value_index]->nodeValue = $data_array[$array_value_index];
      $table_row->appendChild($table_cells[$array_value_index]);
    }

    $table->appendChild($table_row);
  }
  
  $dom_document->appendChild($table);

  $handler_output_data['html'] = $dom_document->saveHTML();

  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

if ($system_core->urlp->get_param('stepIndex') == 3) {
  $dom_document = new \DOMDocument();
  
  $php_loaded_extensions = get_loaded_extensions();

  $table_data = [
    ['./backups/', '0755', file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/backups', CMS_ROOT_DIRECTORY))), -4) : 'Директория не найдена'],
    ['./modules/', '0755', file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/modules', CMS_ROOT_DIRECTORY))), -4) : 'Директория не найдена'],
    ['./templates/', '0755', file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/templates', CMS_ROOT_DIRECTORY))), -4) : 'Директория не найдена'],
    ['./uploads/', '0755', file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/uploads', CMS_ROOT_DIRECTORY))), -4) : 'Директория не найдена'],
  ];

  $table_cells_font_color = [
    !file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY)) ? '#9A2020' : (decoct(fileperms(sprintf('%s/backups', CMS_ROOT_DIRECTORY)) & 0777) == 755 ? '#209A20' : '#9A2020'),
    !file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY)) ? '#9A2020' : (decoct(fileperms(sprintf('%s/modules', CMS_ROOT_DIRECTORY)) & 0777) == 755 ? '#209A20' : '#9A2020'),
    !file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY)) ? '#9A2020' : (decoct(fileperms(sprintf('%s/templates', CMS_ROOT_DIRECTORY)) & 0777) == 755 ? '#209A20' : '#9A2020'),
    !file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY)) ? '#9A2020' : (decoct(fileperms(sprintf('%s/uploads', CMS_ROOT_DIRECTORY)) & 0777) == 755 ? '#209A20' : '#9A2020'),
  ];

  $table = $dom_document->createElement('table');
  $table->setAttribute('class', 'table');
  
  $table_row_header = $dom_document->createElement('tr');

  $table_cells_headers = [];
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');

  foreach ($table_cells_headers as $table_cell) {
    $table_cell->setAttribute('class', 'table__cell table__cell_header');
  }

  $table_cells_headers[0]->nodeValue = 'Наименование';
  $table_cells_headers[1]->nodeValue = 'Ожидание';
  $table_cells_headers[2]->nodeValue = 'Факт';

  $table_row_header->appendChild($table_cells_headers[0]);
  $table_row_header->appendChild($table_cells_headers[1]);
  $table_row_header->appendChild($table_cells_headers[2]);

  $table->appendChild($table_row_header);

  foreach ($table_data as $data_array_index => $data_array) {
    $table_row = $dom_document->createElement('tr');

    $table_cells = [];
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');

    foreach ($table_cells as $table_cell_index => $table_cell) {
      $table_cell->setAttribute('class', 'table__cell');

      if ($table_cell_index == 2 && !empty($table_cells_font_color[$data_array_index])) {
        $table_cell->setAttribute('style', sprintf('color: %s; border-color: inherit;', $table_cells_font_color[$data_array_index]));
      }
    }

    foreach ($data_array as $array_value_index => $array_value) {
      $table_cells[$array_value_index]->nodeValue = $data_array[$array_value_index];
      $table_row->appendChild($table_cells[$array_value_index]);
    }

    $table->appendChild($table_row);
  }
  
  $dom_document->appendChild($table);

  $handler_output_data['html'] = $dom_document->saveHTML();

  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

if ($system_core->urlp->get_param('stepIndex') == 4) {
  $dom_document = new \DOMDocument();
  
  $php_loaded_extensions = get_loaded_extensions();

  $table_data = [
    ['Cubrid', (in_array('pdo_cubrid', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['FreeTDS / Microsoft SQL Server / Sybase', (in_array('pdo_dblib', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['Firebird', (in_array('pdo_firebird', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['IBM DB2', (in_array('pdo_ibm', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['IBM Informix Dynamic Server', (in_array('pdo_informix', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['MySQL', (in_array('pdo_mysql', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['Oracle Call Interface', (in_array('pdo_ocl', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['ODBC v3 (IBM DB2, unixODBC, win32 ODBC)', (in_array('pdo_odbc', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['PostgreSQL', (in_array('pdo_pgsql', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['SQLite 3 и SQLite 2', (in_array('pdo_sqlite', $php_loaded_extensions) ? 'Включен' : 'Выключен')],
    ['Microsoft SQL Server / SQL Azure', (in_array('pdo_sqlsrv', $php_loaded_extensions) ? 'Включен' : 'Выключен')]
  ];

  $table_cells_font_color = [
    (in_array('pdo_cubrid', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_dblib', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_firebird', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_ibm', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_informix', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_mysql', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_ocl', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_odbc', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_pgsql', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_sqlite', $php_loaded_extensions) ? '#209A20' : '#9A2020'),
    (in_array('pdo_sqlsrv', $php_loaded_extensions) ? '#209A20' : '#9A2020')
  ];

  $table = $dom_document->createElement('table');
  $table->setAttribute('class', 'table');
  
  $table_row_header = $dom_document->createElement('tr');

  $table_cells_headers = [];
  $table_cells_headers[] = $dom_document->createElement('th');
  $table_cells_headers[] = $dom_document->createElement('th');

  foreach ($table_cells_headers as $table_cell) {
    $table_cell->setAttribute('class', 'table__cell table__cell_header');
  }

  $table_cells_headers[0]->nodeValue = 'Драйвер PDO';
  $table_cells_headers[1]->nodeValue = 'Состояние';

  $table_row_header->appendChild($table_cells_headers[0]);
  $table_row_header->appendChild($table_cells_headers[1]);

  $table->appendChild($table_row_header);

  foreach ($table_data as $data_array_index => $data_array) {
    $table_row = $dom_document->createElement('tr');

    $table_cells = [];
    $table_cells[] = $dom_document->createElement('td');
    $table_cells[] = $dom_document->createElement('td');

    foreach ($table_cells as $table_cell_index => $table_cell) {
      $table_cell->setAttribute('class', 'table__cell');

      if ($table_cell_index == 1 && !empty($table_cells_font_color[$data_array_index])) {
        $table_cell->setAttribute('style', sprintf('color: %s; border-color: inherit;', $table_cells_font_color[$data_array_index]));
      }
    }

    foreach ($data_array as $array_value_index => $array_value) {
      $table_cells[$array_value_index]->nodeValue = $data_array[$array_value_index];
      $table_row->appendChild($table_cells[$array_value_index]);
    }

    $table->appendChild($table_row);
  }
  
  $dom_document->appendChild($table);

  $handler_output_data['html'] = $dom_document->saveHTML();

  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

if ($system_core->urlp->get_param('stepIndex') == 5) {
  $dom_document = new \DOMDocument();
  $tip_block = $dom_document->createElement('div');

  $config_file_path = sprintf('%s/%s', CMS_ROOT_DIRECTORY, \core\PHPLibrary\SystemCore\Configurator::FILE_PATH);
  if (file_exists($config_file_path)) {
    unlink($config_file_path);
  }

  if (!file_exists($config_file_path)) {
    $file = fopen($config_file_path, 'w+');
    fwrite($file, '<?php' . PHP_EOL);
    fwrite($file, '/**' . PHP_EOL);
    fwrite($file, ' * ВНИМАНИЕ! Файл "configuration.sample.php" является образцом конфигурационного файла.' . PHP_EOL);
    fwrite($file, ' * Вы можете сюда подставить Ваши данные и переименовать файл в "configuration.php".' . PHP_EOL);
    fwrite($file, ' *' . PHP_EOL);
    fwrite($file, ' * РЕКОМЕНДАЦИЯ: Не следует удалять файл "configuration.sample.php", поскольку конфигурационный' . PHP_EOL);
    fwrite($file, ' * файл всегда можно будет вернуть в исходное состояние.' . PHP_EOL);
    fwrite($file, ' */' . PHP_EOL);
    fwrite($file, PHP_EOL);
    fwrite($file, '$configuration = [' . PHP_EOL);
    fwrite($file, '  \'domain\' => \'www.cms-girvas.ru\',' . PHP_EOL);
    fwrite($file, '  \'domain_cookies\' => \'www.cms-girvas.ru\',' . PHP_EOL);
    fwrite($file, '  \'ssl_is_enabled\' => true,' . PHP_EOL);
    fwrite($file, '  \'database\' => [' . PHP_EOL);
    fwrite($file, sprintf('    \'host\' => \'%s\',', $_GET['database_host']) . PHP_EOL);
    fwrite($file, sprintf('    \'user\' => \'%s\',', $_GET['database_user']) . PHP_EOL);
    fwrite($file, sprintf('    \'password\' => \'%s\',', $_GET['database_pass']) . PHP_EOL);
    fwrite($file, sprintf('    \'name\' => \'%s\',', $_GET['database_name']) . PHP_EOL);
    fwrite($file, '  ],' . PHP_EOL);
    fwrite($file, '  \'system_salt\' => \'?Q59DL+(/W29tM(aLij3D/X8\',' . PHP_EOL);
    fwrite($file, '  \'password_hashing_algorithm\' => PASSWORD_ARGON2ID,' . PHP_EOL);
    fwrite($file, '  \'session_expires\' => 86400,' . PHP_EOL);
    fwrite($file, '  \'session_admin_expires\' => 86400,' . PHP_EOL);
    fwrite($file, '];' . PHP_EOL);
    fwrite($file, '?>');
    fclose($file);
  }

  //$db_connector_test = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator, true);

  if (file_exists($config_file_path)) {
    $tip_block->setAttribute('class', 'tip tip_green');
    $tip_block->nodeValue = 'Конфигурационный файл успешно создан.';
  } else {
    $tip_block->setAttribute('class', 'tip tip_red');
    $tip_block->nodeValue = 'Конфигурационный файл не был создан.';
  }

  $dom_document->appendChild($tip_block);

  $handler_output_data['html'] = $dom_document->saveHTML();
  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

if ($system_core->urlp->get_param('stepIndex') == 6) {
  $db_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator, true);

  $dom_document = new \DOMDocument();
  $tip_block = $dom_document->createElement('div');

  if ($db_connector->database->connect_test()) {
    $tip_block->setAttribute('class', 'tip tip_green');
    $tip_block->nodeValue = 'Таблицы сгенерированы.';
  } else {
    $tip_block->setAttribute('class', 'tip tip_red');
    $tip_block->nodeValue = 'Таблицы не сгенерированы.';
  }

  $dom_document->appendChild($tip_block);

  $handler_output_data['html'] = $dom_document->saveHTML();
  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

?>