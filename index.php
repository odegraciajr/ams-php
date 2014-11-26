<?php
define('ABSPATH', dirname(__FILE__));
define('COREPATH', dirname(__FILE__) . '/core');

if( $_SERVER['REMOTE_ADDR'] == "127.0.0.1" )
	$config = COREPATH . '/config/main.php';
else
	$config = COREPATH . '/config/prod.php';
	
require_once COREPATH . '/utilities/Engine.php';

App::startApp($config);