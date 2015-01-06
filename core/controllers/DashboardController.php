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
		
		//activate slick-grid
		$this->add_style('/assets/js/slick/slick.grid.css');
		//$this->add_style('/assets/css/smoothness/jquery-ui-1.8.16.custom.css');
		$this->add_style('/assets/js/slick/slick-default-theme.css');
		$this->add_style('/assets/js/slick/controls/slick.pager.css');
		
		$this->add_style('/assets/css/slick-default-theme.css');
		$this->add_style('/assets/css/slick.css');
		
		$this->add_script('/assets/js/slick/jquery.event.drag-2.2.js',true);
		$this->add_script('/assets/js/slick/slick.core.js',true);
		$this->add_script('/assets/js/slick/slick.grid.js',true);
		$this->add_script('/assets/js/slick/slick.dataview.js',true);
		$this->add_script('/assets/js/slick/controls/slick.pager.js',true);
		
		$this->add_style('//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
		$this->add_style('/assets/css/jquery.gridster.min.css');
		
		
		$this->add_script('/assets/js/jquery.gridster.js',true);
		$this->add_script('/assets/js/jquery-ui.min.js',true);
		
		//loadwidget cores
		$this->add_script('/assets/widgets/widget.templates.js',true);
		$this->add_script('/assets/widgets/widget.core.js',true);
		
		//load widget plugins
		$this->add_script('/assets/widgets/plugins/projects.js',true);
		
		//load widget init
		$this->add_script('/assets/widgets/widget.js',true);
		
		$this->render('index',$data);
	}
	
	public function saveUserWidgetSettingsAction()
	{
		$settings = $_POST['settings'];
		$widget_id = isset($_POST['widget_id']) ? $_POST['widget_id']: 0;
		$tab_id = isset($_POST['tab_id']) ? $_POST['tab_id']: 1;
		$tab_name = isset($_POST['tab_name']) ? $_POST['tab_name']: null;
		
		$result = $this->loadModel('WidgetModel')->saveWidgetSettings($settings,$widget_id,$tab_name,$tab_id);
		App::Tools()->toJson($result,true);
	}
	
	public function getUserWidgetSettingsAction()
	{
		$result = $this->loadModel('WidgetModel')->getWidgetSettings();
		App::Tools()->toJson($result,true);
	}
	
	public function createUserWidgetSettingsAction()
	{
		$settings = $_POST['settings'];
		$tab_name = isset($_POST['tab_name']) ? $_POST['tab_name']: null;
		
		$result = $this->loadModel('WidgetModel')->createWidgetSettingsTab($settings,$tab_name);
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
		$this->add_script('/assets/js/slick/slick.dataview.js',true);
		$this->add_script('/assets/js/slick/scripts.slick.js',true);
		$this->render('grid',$params);
	}
}