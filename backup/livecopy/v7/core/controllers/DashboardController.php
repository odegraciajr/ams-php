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
}