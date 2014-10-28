<?php

class DashboardController extends Controller
{
	public function __construct($model, $action)
	{
		if( App::User()->isGuest ) {
			RouteManager::redirect('/account/login');
		}
		parent::__construct($model, $action);
		$this->_view->set('title', htmlspecialchars('Welcome '. App::User()->first_name . '!'));
	}
	
	public function index()
	{

		$org = new OrganizationModel();
		$proj = new ProjectModel();
		
		$this->_view->set('myOrg', $org->getUserOrganizations());
		$this->_view->set('myProject', $proj->getUserProjects());
		return $this->_view->output();
	}
	
	
}