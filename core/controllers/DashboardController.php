<?php

class DashboardController extends Controller
{
	public function indexAction()
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