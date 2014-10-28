<?php
define('ABSPATH', dirname(__FILE__));
define('COREPATH', dirname(__FILE__) . '/core');

$config = COREPATH . '/config/main.php';
require_once COREPATH . '/utilities/Engine.php';

App::startApp($config);