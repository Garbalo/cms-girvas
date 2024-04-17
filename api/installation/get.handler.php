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

if (!file_exists(sprintf('%s/INSTALLED', CMS_ROOT_DIRECTORY))) {
  if ($system_core->urlp->get_param('stepIndex') == 1) {
    $dom_document = new \DOMDocument();
    
    $php_loaded_extensions = get_loaded_extensions();

    $table_data = [
      [$system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_VERSION_LABEL'), '>= 8.2.6', phpversion()],
      [sprintf('%s SimpleXML', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('SimpleXML', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      [sprintf('%s PDO', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('PDO', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      [sprintf('%s curl', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('curl', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      [sprintf('%s dom', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('dom', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      [sprintf('%s mbstring', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('mbstring', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      [sprintf('%s json', $system_core->locale->get_single_value_by_key('API_INSTALLATION_PHP_MODULE_LABEL')), $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED'), (in_array('json', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))]
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

    $table_cells_headers[0]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_NAME_LABEL');
    $table_cells_headers[1]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_EXPECTATION_LABEL');
    $table_cells_headers[2]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_FACT_LABEL');

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

    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }

  if ($system_core->urlp->get_param('stepIndex') == 2) {
    $dom_document = new \DOMDocument();
    
    $table_data = [
      ['./backups/', (file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY))) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_FOUND_LABEL') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./modules/', (file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY))) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_FOUND_LABEL') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./templates/', (file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY))) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_FOUND_LABEL') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./uploads/', (file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY))) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_FOUND_LABEL') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
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

    $table_cells_headers[0]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_NAME_LABEL');
    $table_cells_headers[1]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_FACT_LABEL');

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

    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }

  if ($system_core->urlp->get_param('stepIndex') == 3) {
    $dom_document = new \DOMDocument();
    
    $php_loaded_extensions = get_loaded_extensions();

    if (!file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY))) mkdir(sprintf('%s/backups', CMS_ROOT_DIRECTORY), 0755);
    if (!file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY))) mkdir(sprintf('%s/modules', CMS_ROOT_DIRECTORY), 0755);
    if (!file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY))) mkdir(sprintf('%s/templates', CMS_ROOT_DIRECTORY), 0755);
    if (!file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY))) mkdir(sprintf('%s/uploads', CMS_ROOT_DIRECTORY), 0755);

    $table_data = [
      ['./backups/', '0755', file_exists(sprintf('%s/backups', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/backups', CMS_ROOT_DIRECTORY))), -4) : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./modules/', '0755', file_exists(sprintf('%s/modules', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/modules', CMS_ROOT_DIRECTORY))), -4) : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./templates/', '0755', file_exists(sprintf('%s/templates', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/templates', CMS_ROOT_DIRECTORY))), -4) : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
      ['./uploads/', '0755', file_exists(sprintf('%s/uploads', CMS_ROOT_DIRECTORY)) ? substr(sprintf('%o', fileperms(sprintf('%s/uploads', CMS_ROOT_DIRECTORY))), -4) : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DIRECTORY_NOT_FOUND_LABEL')],
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

    $table_cells_headers[0]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_NAME_LABEL');
    $table_cells_headers[1]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_EXPECTATION_LABEL');
    $table_cells_headers[2]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_FACT_LABEL');

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

    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }

  if ($system_core->urlp->get_param('stepIndex') == 4) {
    $dom_document = new \DOMDocument();
    
    $php_loaded_extensions = get_loaded_extensions();

    $table_data = [
      ['Cubrid', (in_array('pdo_cubrid', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['FreeTDS / Microsoft SQL Server / Sybase', (in_array('pdo_dblib', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['Firebird', (in_array('pdo_firebird', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['IBM DB2', (in_array('pdo_ibm', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['IBM Informix Dynamic Server', (in_array('pdo_informix', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['MySQL', (in_array('pdo_mysql', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['Oracle Call Interface', (in_array('pdo_ocl', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['ODBC v3 (IBM DB2, unixODBC, win32 ODBC)', (in_array('pdo_odbc', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['PostgreSQL', (in_array('pdo_pgsql', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['SQLite 3 и SQLite 2', (in_array('pdo_sqlite', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))],
      ['Microsoft SQL Server / SQL Azure', (in_array('pdo_sqlsrv', $php_loaded_extensions) ? $system_core->locale->get_single_value_by_key('API_INSTALLATION_ENABLED') : $system_core->locale->get_single_value_by_key('API_INSTALLATION_DISABLED'))]
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

    $table_cells_headers[0]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_DRIVER_PDO_LABEL');
    $table_cells_headers[1]->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_DRIVER_PDO_STATUS_LABEL');

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

    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
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
      $domain_ssl_status = (isset($_GET['domain_ssl_status'])) ? 'true' : 'false';
      
      $file = fopen($config_file_path, 'w+');
      fwrite($file, '<?php' . PHP_EOL);
      fwrite($file, PHP_EOL);
      fwrite($file, 'use \core\PHPLibrary\Database\DatabaseManagementSystem as DMS;' . PHP_EOL);
      fwrite($file, PHP_EOL);
      fwrite($file, '$configuration = [' . PHP_EOL);
      fwrite($file, sprintf('  \'domain\' => \'%s\',', $_GET['domain']) . PHP_EOL);
      fwrite($file, sprintf('  \'domain_cookies\' => \'%s\',', $_GET['domain']) . PHP_EOL);
      fwrite($file, sprintf('  \'ssl_is_enabled\' => %s,', $domain_ssl_status) . PHP_EOL);
      fwrite($file, '  \'database\' => [' . PHP_EOL);
      fwrite($file, sprintf('    \'dms\' => %s,', $_GET['database_dms']) . PHP_EOL);
      fwrite($file, sprintf('    \'prefix\' => \'%s\',', $_GET['database_prefix']) . PHP_EOL);
      fwrite($file, sprintf('    \'scheme\' => \'%s\',', $_GET['database_scheme']) . PHP_EOL);
      fwrite($file, sprintf('    \'host\' => \'%s\',', $_GET['database_host']) . PHP_EOL);
      fwrite($file, sprintf('    \'user\' => \'%s\',', $_GET['database_user']) . PHP_EOL);
      fwrite($file, sprintf('    \'password\' => \'%s\',', $_GET['database_pass']) . PHP_EOL);
      fwrite($file, sprintf('    \'name\' => \'%s\',', $_GET['database_name']) . PHP_EOL);
      fwrite($file, '  ],' . PHP_EOL);
      fwrite($file, sprintf('  \'system_salt\' => \'%s\',', $system_salt) . PHP_EOL);
      fwrite($file, '  \'password_hashing_algorithm\' => PASSWORD_ARGON2ID,' . PHP_EOL);
      fwrite($file, '  \'session_expires\' => 86400,' . PHP_EOL);
      fwrite($file, '  \'session_admin_expires\' => 86400,' . PHP_EOL);
      fwrite($file, '];' . PHP_EOL);
      fwrite($file, PHP_EOL);
      fwrite($file, '?>');
      fclose($file);
      chmod($config_file_path, 0664);
    }

    //$db_connector_test = new \core\PHPLibrary\SystemCore\DatabaseConnector($system_core, $system_core->configurator, true);

    if (file_exists($config_file_path)) {
      $tip_block->setAttribute('class', 'tip tip_green');
      $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_CONFIGURATION_FILE_CREATED');
    } else {
      $tip_block->setAttribute('class', 'tip tip_red');
      $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_CONFIGURATION_FILE_NOT_CREATED');
    }

    $dom_document->appendChild($tip_block);

    $handler_output_data['html'] = $dom_document->saveHTML();
    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
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

    $first_users_group = \core\PHPLibrary\UserGroup::create($system_core, 'admin', $first_users_group_texts, 211959);
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
    $tip_block->nodeValue = $system_core->locale->get_single_value_by_key('API_INSTALLATION_TABLES_GENERATED');

    $dom_document->appendChild($tip_block);

    $handler_output_data['html'] = $dom_document->saveHTML();
    $handler_message = $system_core->locale->get_single_value_by_key('API_GET_DATA_SUCCESS');
    $handler_status_code = 1;
  }
}

?>