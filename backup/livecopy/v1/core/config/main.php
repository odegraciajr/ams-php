<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'DSS',
	'defaultController' => 'account',
	'domainName' => 'testapp.mhgr.us',
	'loginCookieLife' => 30,//days
	'db'=>array(
			'host' => 'localhost',
			'name' => 'mytestapp',
			'user' => 'mytestapp',
			'pass' => 'admin123',
			'charset' => 'utf8'
		)
);