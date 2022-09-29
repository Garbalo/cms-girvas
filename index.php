<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
ini_set('error_log', sprintf('%s/logs/error.log', DOCUMENT_ROOT));

define('IS_NOT_HACKED', true);
define('PRIVATE_ACCESS', false);

require_once(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

$libraries_list = scandir(sprintf('%s/cron/php-library', DOCUMENT_ROOT));
foreach($libraries_list as $library_index => $library_file) {
	if (preg_match('/^([a-zA-Z_]+)\.class\.php$/', $library_file)) {
		require_once(sprintf('%s/cron/php-library/%s', DOCUMENT_ROOT, $library_file));
	}
}

//$client = new cron\library\User();
$surl = new cron\library\SimpleUrl();

if (!file_exists(sprintf('%s/install', DOCUMENT_ROOT))) {

	if ($surl->get_path(0) == 'girvas-admin') {

		require(sprintf('%s/admin/admin.php', DOCUMENT_ROOT));

	} else {

		$template_name = 'default';

		$template = cron\library\Template::connect_core($template_name, sprintf('%s/templates', DOCUMENT_ROOT));
		$template->set_assembly();
		echo $template->get_ready_assembly();

	}

} else {

	require(sprintf('%s/install/install.php', DOCUMENT_ROOT));

}

?>