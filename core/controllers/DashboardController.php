<?php

class DashboardController extends Controller
{
	public function indexAction()
	{
		$this->layout = 'main-test';
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
			
		$this->setPageTitle('Dashboard');
		$data = [];
		
		$this->add_style('/assets/css/jquery-ui.min.css');
		$this->add_style('/assets/css/jquery.gridster.min.css');
		
		
		$this->add_script('/assets/js/jquery.gridster.js',true);
		$this->add_script('/assets/js/jquery-ui.min.js',true);
		$this->add_script('/assets/js/widget.js',true);
		
		$this->render('index',$data);
	}
	
	public function saveUserWidgetSettingsAction()
	{
		$settings = $_POST['settings'];
		
		$result = $this->loadModel('WidgetModel')->saveWidgetSettings($settings);
		App::Tools()->toJson($result,true);
	}
	
	public function getUserWidgetSettingsAction()
	{
		$result = $this->loadModel('WidgetModel')->getWidgetSettings();
		App::Tools()->toJson($result,true);
	}
	
	public function index2Action()
	{
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
			
		$this->setPageTitle('Dashboard');
		$data = [
			'myOrg' => $this->loadModel('OrganizationModel')->getUserOrganizations(),
			'myProject' => $this->loadModel('ProjectModel')->getUserProjects(),
		];
		
		$this->render('index',$data);
	}
	public function errorAction()
	{
		$this->activeNav = "";
		$this->setPageTitle('Error 404 ',true);
		$this->render('error');
	}
	
	public function gridAction()
	{
		$this->loginGuest();
		
		$this->setPageTitle('SlickGrid');
		
		$params = [
			'allProjects' => $this->loadModel('ProjectModel')->getAllProjects()
		];
		$this->add_style('/assets/js/slick/slick.grid.css');
		//$this->add_style('/assets/css/smoothness/jquery-ui-1.8.16.custom.css');
		$this->add_style('/assets/js/slick/slick-default-theme.css');
		
		$this->add_script('/assets/js/slick/jquery.event.drag-2.2.js',true);
		$this->add_script('/assets/js/slick/slick.core.js',true);
		$this->add_script('/assets/js/slick/slick.grid.js',true);
		$this->add_script('/assets/js/slick/scripts.slick.js',true);
		$this->render('grid',$params);
	}
}