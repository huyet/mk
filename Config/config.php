<?php
!defined('RUNAPP') && exit('Denied');
$BaseConfig = array(
	'DEBUG'=>true,
	'WWW_ADMIN' => WWW.'/Admin',
	'CORE_PATH'=> WWW.'/Admin/Libs/Core',
	'EXTEND_PATH'=>WWW.'/Admin/Libs/Extend',
	'LOGS_PATH'=>WWW.'/Admin/Logs',
	'TPL_PATH'=>WWW.'/Admin/Tpl',
	
	'TEMPLATE_NAME' => '**displayTemplate**',
	
	'DEFAULT_MODULE' => 'Index',
	'DEFAULT_CONTROL' => 'Index',
	'DEFAULT_METHOD' => 'index',
	 
);
?>