<?php

if (!defined('IS_NOT_HACKED')) {
  die('Unauthorized access attempt detected!');
}

$template = cron\library\Template::connect_core('default', sprintf('%s/install/templates', DOCUMENT_ROOT), 'install');
$template->set_assembly();
echo $template->get_ready_assembly();

?>