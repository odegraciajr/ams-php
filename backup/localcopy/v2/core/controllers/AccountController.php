<?php

class AccountController extends Controller
{
	private $activeTab = "profile";
	
	public function __construct($model, $action)
	{
		parent::__construct($model, $action);
		$this->_setModel($model);
	}
	
	public function index()
	{
		if( App::User()->isGuest ) {
			RouteManager::redirect('/account/login');
		}
		else {
			//Create Organization Call if any.
			$org = new OrganizationModel();
			$this->createOrganization($org);
			
			$proj = new ProjectModel();
			$this->createProject($proj);
			
			$this->view->setPageTitle("Profile");
			$this->view->set('userdata', $this->_model->getUserData());
			$this->view->set('activeTab', $this->activeTab);
			$this->view->set('myOrg', $org->getUserOrganizations());
			$this->view->set('myProject', $proj->getUserProjects());
			$this->view->set('model', $this->_model);
			return $this->view->output();
		}
	}
	
	public function home()
	{
		if( App::User()->isGuest ) {
			RouteManager::redirect('/account/login');
		}
		else {
			$this->view->active_nav = 'profile';
			//Create Organization Call if any.
			$org = new OrganizationModel();
			$this->createOrganization($org);

			$proj = new ProjectModel();
			$this->createProject($proj);

			$this->view->setPageTitle("Profile");
			$this->view->set('userdata', $this->_model->getUserData());
			$this->view->set('activeTab', $this->activeTab);
			$this->view->set('myOrg', $org->getUserOrganizations());
			$this->view->set('myProject', $proj->getUserProjects());
			$this->view->set('model', $this->_model);
			return $this->view->output();
		}
	}
	
	public function edit()
	{
		$this->view->active_nav = 'profile';
		$this->view->setPageTitle("Edit Profile");
		return $this->view->output();
	}
	
	public function login()
	{
		$this->view->setLayOut('login');
		
		if( App::User()->isGuest ){
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) )
			{
				$remember = isset($_POST['remember']) ? isset($_POST['remember']) : 0;
				
				$login_attempt = $this->_model->login($_POST['email'], $_POST['password'], $remember);
				
				if( AccountModel::LOGIN_SUCCESS == $login_attempt )
					RouteManager::redirect('/dashboard');
					
				$this->view->set('error', true);

				if( AccountModel::INVALID_EMAIL == $login_attempt ) {
					$this->view->set('error_message', 'Invalid email address');
				}
				elseif( AccountModel::INVALID_PASSWORD == $login_attempt ) {
					$this->view->set('error_message', 'Invalid password');
				}
				else {
					$this->view->set('error_message', 'Account not yet verified. Please check your email and verify your account');
				}
			}
			if( isset( $_GET['regs'] ) ) {
				$this->view->set('regs_success', 'An email verification was sent to your email address.');
			}
			$this->view->setPageTitle("Login");
			
			return $this->view->output();
		}
		else {
			RouteManager::redirect('/dashboard');
		}
	}
	
	public function register()
	{
		$this->view->setLayOut('login');
		$this->view->setPageTitle( 'Register');
		$this->view->setPageTitle("Register");
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_register" ) {
		
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) && isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) 
			&& trim( $_POST['email'] ) && trim( $_POST['password'] ) && trim( $_POST['first_name'] ) && trim( $_POST['last_name'] ))
			{
				$data = array_map('trim', $_POST);

				if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'Invalid email address.');
				}
				elseif($this->_model->emailIsUnique($data['email'])) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'Email address already used.');
				}
				elseif($data['password'] != $data['password_confirm']) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'Password didn\'t match.');
				}
				elseif( strlen($data['password'] ) < 5 ) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'Password too short, must be at least 5 characters.');
				}
				elseif( empty($data['first_name']) ) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'First name must not be empty.');
				}
				elseif( empty($data['last_name']) ) {
					$this->view->set('error', true);
					$this->view->set('error_message', 'Last name must not be empty.');
				}
				else {
					$newUser = $this->_model->createNewUser();

					if( $newUser ) {
						RouteManager::redirect('/account/login&regs=1');
					}
					else {
						$this->view->set('error', true);
						$this->view->set('error_message', 'Unknown error.');
					}
				}
			}else{
				$this->view->set('error', true);
				$this->view->set('error_message', 'All fields are required');
			}
		}
		
		$this->view->setPageTitle( 'Register');
		return $this->view->output();
	}
	
	private function createOrganization( $model )
	{
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization" ) {
			if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
				$newOrg = $model->createOrganization();
				$this->activeTab = "organization";
			}
		}
	}
	private function createProject( $model )
	{
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project" ) {
			if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
				$newProj = $model->createProject();
				$this->activeTab = "project";
			}
		}
	}
	
	public function verify( $hash )
	{
		$this->view->setLayOut('login');
		
		$hash_type = 1;//email verification
		
		$result = $this->_model->verify($hash,$hash_type);
		
		if( $result['status'] ) {
			$this->view->set('error_type', 1);
		}
		else {
			$this->view->set('error_type', 0);
		}
		$this->view->set('error_message', $result['message']);
		$this->view->setPageTitle( 'Account Verification');
		
		return $this->view->output();
	}
		
	public function accountSearch($keyword=null){
	
		if( isset( $_POST['keyword'] ) ) {
			$keyword = $_POST['keyword'];
		}
		
		$results = $this->_model->searchUserByKeyword($keyword);

		App::Tools()->toJson($results);
	}
	public function logout( $redirect=null ){
		session_destroy();
		setcookie('user_id', '', time() - 1*24*60*60);
		setcookie('user_hash', '', time() - 1*24*60*60);
		
		if( $redirect )
			RouteManager::redirect($redirect);
			
		RouteManager::redirect('/');
	}
	
	public function userprofile($user_id)
	{
		$this->view->set('userdata',$this->_model->getUserData($user_id));
		$this->view->setPageTitle( 'User Profile');
		return $this->view->output();
	}
}