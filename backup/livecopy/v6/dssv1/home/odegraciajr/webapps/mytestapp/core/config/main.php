<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'AMS',
	'defaultController' => 'dashboard',
	'domainName' => 'odegraciajr.webfactional.com',
	'loginCookieLife' => 30,//days
	'db'=>array(
		'host' => 'localhost',
		'name' => 'mytestapp',
		'user' => 'mytestapp',
		'pass' => 'admin123',
		'charset' => 'utf8'
	),
	'errorHandler'=>'dashboardController/error',
	'routes' => array(

	),
);