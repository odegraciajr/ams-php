<?php

class DashboardController extends Controller
{
	public function __construct($model, $action)
	{
		if( App::User()->isGuest ) {
			RouteManager::redirect('/account/login');
		}
		parent::__construct($model, $action);
		$this->view->setPageTitle( 'Dashboard &raquo; Welcome '. App::User()->first_name . '!' );
	}
	
	public function index()
	{

		$org = new OrganizationModel();
		$proj = new ProjectModel();
		
		$this->view->set('myOrg', $org->getUserOrganizations());
		$this->view->set('myProject', $proj->getUserProjects());
		return $this->view->output();
	}
	
	
}