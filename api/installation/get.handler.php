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
    fwrite($file, sprintf('    \'prefix\' => \'%s\',', $_GET['database_prefix']) . PHP_EOL);
    fwrite($file, sprintf('    \'scheme\' => \'%s\',', $_GET['database_scheme']) . PHP_EOL);
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
  $database_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator);
  $database_configurations = $system_core->configurator->get('database');

  $dom_document = new \DOMDocument();
  $tip_block = $dom_document->createElement('div');

  $db_prefix = $database_configurations['prefix'];
  $db_scheme = $database_configurations['scheme'];
  $db_prefix = ($db_prefix != '') ? $db_prefix . '_' : '';
  $db_scheme = ($db_scheme != '') ? $db_scheme . '.' : '';

  // =======================
  // ТАБЛИЦА КОНФИГУРАЦИЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('configurations');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('value', 'text');
  $query_builder->statement->add_column('texts', 'json');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ЗАПИСЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('entries');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('category_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('author_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('texts', 'jsonb');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА КАТЕГОРИЙ ЗАПИСЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('entries_categories');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('parent_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('texts', 'jsonb');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА КОММЕНТАРИЕВ ЗАПИСЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('entries_comments');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('entry_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('author_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('content', 'text');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА СТАТИЧЕСКИХ СТРАНИЦ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('pages_static');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('texts', 'jsonb');
  $query_builder->statement->add_column('author_id', 'bigint');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ОТЧЕТОВ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('reports');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('variables', 'jsonb');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ПОЛЬЗОВАТЕЛЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('users');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('login', 'text', 'NOT NULL');
  $query_builder->statement->add_column('email', 'text', 'NOT NULL');
  $query_builder->statement->add_column('password_hash', 'text', 'NOT NULL');
  $query_builder->statement->add_column('security_hash', 'text', 'NOT NULL');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('email_is_submitted', 'boolean', 'NOT NULL DEFAULT false');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ГРУПП ПОЛЬЗОВАТЕЛЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('users_groups');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('texts', 'jsonb');
  $query_builder->statement->add_column('permissions', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('metadata', 'jsonb');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ЗАЯВОК НА ПОДТВЕРЖДЕНИЕ РЕГИСТРАЦИИ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('users_registration_submits');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('user_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('submit_token', 'text', 'NOT NULL');
  $query_builder->statement->add_column('refusal_token', 'text', 'NOT NULL');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА СЕССИЙ ПОЛЬЗОВАТЕЛЕЙ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('users_sessions');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('user_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('token', 'text', 'NOT NULL');
  $query_builder->statement->add_column('user_ip', 'text', 'NOT NULL');
  $query_builder->statement->add_column('type_id', 'integer', 'NOT NULL DEFAULT 1');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ТАБЛИЦА ВЕБ-КАНАЛОВ
  // =======================

  $query_builder = new \core\PHPLibrary\Database\QueryBuilder($system_core);
  $query_builder->set_statement_create_table();
  $query_builder->statement->set_check_exists(true);
  $query_builder->statement->set_table_name('web_channels');
  $query_builder->statement->add_column('id', 'serial', 'NOT NULL PRIMARY KEY');
  $query_builder->statement->add_column('name', 'text', 'NOT NULL');
  $query_builder->statement->add_column('entries_category_id', 'bigint', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('type_id', 'integer', 'NOT NULL DEFAULT 1');
  $query_builder->statement->add_column('texts', 'jsonb');
  $query_builder->statement->add_column('created_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->add_column('updated_unix_timestamp', 'integer', 'NOT NULL DEFAULT 0');
  $query_builder->statement->assembly();

  $database_connection = $database_connector->database->connection;
  $database_query = $database_connection->prepare($query_builder->statement->assembled);

  $execute = $database_query->execute();

  // =======================
  // ПЕРВИЧНОЕ НАПОЛНЕНИЕ БАЗЫ ДАННЫХ
  // =======================

  $system_core->database_connector = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator);

  $first_entry_texts = [];
  $first_entry_texts['en_US'] = [
    'title' => 'Hello, World!',
    'content' => 'Welcome to &nbsp; **CMS &laquo;GIRVAS&raquo;**! Here is the first published entry &nbsp;&mdash; It was generated automatically when installing a control system. If you&nbsp;it&nbsp;you see, then &nbsp; then the installation has passed well.<br><br>## What is next?<br>You need <a href="/admin">to configure</a> the installed system. To do this, go to&nbsp;the administrative panel and&nbsp;start following the six blocks that will be displayed on&nbsp;the start page.<br><br>Thanks for&nbsp;what you selected **CMS&laquo;GIRVAS&raquo;**, good luck!'
  ];

  $first_entry = \core\PHPLibrary\Entry::create($system_core, 'hello-world', 1, 1, $first_entry_texts);
  $first_entry->update(['metadata' => ['is_publish' => true]]);

  $first_users_group_texts = [];
  $first_users_group_texts['en_US'] = [
    'title' => 'Administrator'
  ];

  $second_users_group_texts = [];
  $second_users_group_texts['en_US'] = [
    'title' => 'Moderator'
  ];

  $thirty_users_group_texts = [];
  $thirty_users_group_texts['en_US'] = [
    'title' => 'Editor'
  ];

  $fourty_users_group_texts = [];
  $fourty_users_group_texts['en_US'] = [
    'title' => 'User'
  ];

  $first_users_group = \core\PHPLibrary\UserGroup::create($system_core, 'admin', $first_users_group_texts, 80887);
  $second_users_group = \core\PHPLibrary\UserGroup::create($system_core, 'moder', $second_users_group_texts, 66433);
  $thirty_users_group = \core\PHPLibrary\UserGroup::create($system_core, 'editor', $thirty_users_group_texts, 79873);
  $fourty_users_group = \core\PHPLibrary\UserGroup::create($system_core, 'user', $fourty_users_group_texts, 65536);

  $system_core->configurator->insert_database_entry_value('base_template', 'default');
  $system_core->configurator->insert_database_entry_value('base_site_title', 'CMS GIRVAS');
  $system_core->configurator->insert_database_entry_value('base_engineering_works', 'off');
  $system_core->configurator->insert_database_entry_value('base_engineering_works_text', '');
  $system_core->configurator->insert_database_entry_value('seo_site_description', 'CMS GIRVAS - a multidisciplinary free contents control system from the Garbalo Karelian developers.');
  $system_core->configurator->insert_database_entry_value('seo_site_keywords', '["CMS GIRVAS","Free Content Management System","Free CMS","Garbalo"]');
  $system_core->configurator->insert_database_entry_value('base_site_title', 'CMS GIRVAS');

  $tip_block->setAttribute('class', 'tip tip_green');
  $tip_block->nodeValue = 'Таблицы сгенерированы.';

  $dom_document->appendChild($tip_block);

  $handler_output_data['html'] = $dom_document->saveHTML();
  $handler_message = 'Данные успешно получены.';
  $handler_status_code = 1;
}

?>