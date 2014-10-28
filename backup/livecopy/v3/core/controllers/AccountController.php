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
			
			$this->_view->set('title', htmlspecialchars('Welcome '. App::User()->first_name . '!'));
			$this->_view->set('userdata', $this->_model->getUserData());
			$this->_view->set('activeTab', $this->activeTab);
			$this->_view->set('myOrg', $org->getUserOrganizations());
			$this->_view->set('myProject', $proj->getUserProjects());
			$this->_view->set('model', $this->_model);
			return $this->_view->output();
		}
	}
	
	public function login()
	{
		if( App::User()->isGuest ){
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) )
			{
				$remember = isset($_POST['remember']) ? isset($_POST['remember']) : 0;
				
				$login_attempt = $this->_model->login($_POST['email'], $_POST['password'], $remember);
				
				if( AccountModel::LOGIN_SUCCESS == $login_attempt )
					RouteManager::redirect('/account');
					
				$this->_view->set('error', true);

				if( AccountModel::INVALID_EMAIL == $login_attempt ) {
					$this->_view->set('error_message', 'Invalid email address');
				}
				elseif( AccountModel::INVALID_PASSWORD == $login_attempt ) {
					$this->_view->set('error_message', 'Invalid password');
				}
				else {
					$this->_view->set('error_message', 'Account not yet verified. Please check your email and verify your account');
				}
			}
			if( isset( $_GET['regs'] ) ) {
				$this->_view->set('regs_success', 'An email verification was sent to your email address.');
			}
			$this->_view->set('title', 'Login');
			
			return $this->_view->output();
		}
		else {
			RouteManager::redirect('/account');
		}
	}
	
	public function register()
	{
		$this->_view->set('title', 'Register');
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_register" ) {
		
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) && isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) 
			&& trim( $_POST['email'] ) && trim( $_POST['password'] ) && trim( $_POST['first_name'] ) && trim( $_POST['last_name'] ))
			{
				$data = array_map('trim', $_POST);

				if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'Invalid email address.');
				}
				elseif($this->_model->emailIsUnique($data['email'])) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'Email address already used.');
				}
				elseif($data['password'] != $data['password_confirm']) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'Password didn\'t match.');
				}
				elseif( strlen($data['password'] ) < 5 ) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'Password too short, must be at least 5 characters.');
				}
				elseif( empty($data['first_name']) ) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'First name must not be empty.');
				}
				elseif( empty($data['last_name']) ) {
					$this->_view->set('error', true);
					$this->_view->set('error_message', 'Last name must not be empty.');
				}
				else {
					$newUser = $this->_model->createNewUser();

					if( $newUser ) {
						RouteManager::redirect('/account/login&regs=1');
					}
					else {
						$this->_view->set('error', true);
						$this->_view->set('error_message', 'Unknown error.');
					}
				}
			}else{
				$this->_view->set('error', true);
				$this->_view->set('error_message', 'All fields are required');
			}
		}
		
		$this->_view->set('title', 'Register');
		return $this->_view->output();
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
		$hash_type = 1;//email verification
		
		$result = $this->_model->verify($hash,$hash_type);
		
		if( $result['status'] ) {
			$this->_view->set('error_type', 1);
		}
		else {
			$this->_view->set('error_type', 0);
		}
		$this->_view->set('error_message', $result['message']);
		$this->_view->set('title', 'Account Verification');
		
		return $this->_view->output();
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
		$this->_view->set('userdata',$this->_model->getUserData($user_id));
		$this->_view->set('title', 'User Profile');
		return $this->_view->output();
	}
}