<?php

class Controller extends Cview
{
	public $layout='main';
	
	public $menu=array();
	
	public $breadcrumbs=array();
	
	public $activeNav = "dashboard";
	
	public function beforeAction($action)
	{

		$this->add_script('/assets/js/jquery.js',true);
		$this->add_script('/assets/js/bootstrap.min.js',true);
		return parent::beforeAction($action);
	}
}
