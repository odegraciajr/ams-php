<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'AMS',
	'defaultController' => 'dashboard',
	'domainName' => 'dss.dev',
	'loginCookieLife' => 30,//days
	'db'=>array(
			'host' => 'localhost',
			'name' => 'dss',
			'user' => 'root',
			'pass' => '',
			'charset' => 'utf8'
		)
);