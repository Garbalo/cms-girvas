<?php

if (!defined('IS_NOT_HACKED')) {
  die('Unauthorized access attempt detected!');
}

$template = cron\library\Template::connect_core('default', sprintf('%s/admin/templates', DOCUMENT_ROOT), 'admin');
$template->set_assembly();
echo $template->get_ready_assembly();

?>