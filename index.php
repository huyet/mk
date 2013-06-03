<?php
define('WWW',str_replace('\\','/', dirname(__FILE__)));
include_once(WWW.'/Admin/Libs/Core/App.php');
App::Run();
?>