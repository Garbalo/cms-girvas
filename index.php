<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('CMS_ROOT_DIRECTORY', $_SERVER['DOCUMENT_ROOT']);
define('IS_NOT_HACKED', true);

require_once(sprintf('%s/core/PHPLibrary/systemCore.class.php', CMS_ROOT_DIRECTORY));

$system_core = new \core\PHPLibrary\SystemCore();
$template = new \core\PHPLibrary\Template($system_core);
echo $template->get_core_assembled();

?>