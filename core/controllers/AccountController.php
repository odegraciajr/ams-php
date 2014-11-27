<?php

class AccountController extends Controller
{
	private $activeTab = "profile";

	public function __construct()
	{

		$this->activeNav = "profile";
	}
	
	public function indexAction()
	{
		$this->loginGuest();

		$this->activeNav = "profile";
		
		$this->setPageTitle("Profile");
		$accModel = $this->loadmodel('AccountModel');
		
		$data = [
			'userdata' => $accModel->getUserData(),
			'myOrg' => $this->loadmodel('OrganizationModel')->getUserOrganizations(),
			'myProject' => $this->loadmodel('ProjectModel')->getUserProjects(),
			'model' => $accModel
		];
		
		$this->render('index');
	}
	
	public function editAction()
	{
		$this->activeNav = "profile";
		
		$this->loginGuest();

		$params = [
			'post' => '',
			'error' => false
		];
		$model = $this->loadModel();

		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_edit_profile" ) {
			$tools = App::Tools();

			$post = $tools->sanitize($_POST);
			$update = $model->updateUser(App::User()->id,$post);

			if( $update ){
				$params['error'] = true;
				$params['error_type'] = 'info';
				$params['error_message'] = 'Account updated!';
			}
			else {
				$params['error'] = true;
				$params['error_type'] = 'danger';
				$params['error_message'] = 'Failed on updating your account. Please check your all fields and try again.';
			}
		}
			
		$this->setPageTitle("Edit Profile");
		$params['userInfo'] = (object)$model->getUserData();
		$this->render('edit',$params);
	}
	
	public function loginAction()
	{
		$this->layout = 'login';
		$data = [];
		$data['error'] = false;
		$data['email_default'] = "";
		$data['password_default'] = "";
		$data['invalid_login_counter'] = empty(App::getSession('invalid_login_counter' )) ? 0: App::getSession('invalid_login_counter' );
		
		if( App::getSession('login_email' ) ) {
			$data['email_default'] = App::getSession('login_email' );
			App::setSession('login_email',false );
		}
			
		if( App::User()->isGuest ){
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) )
			{
				if(!$data['email_default'])
					$data['email_default'] = $_POST['email'];
					
				$data['password_default'] = $_POST['password'];
		
				$remember = isset($_POST['remember']) ? isset($_POST['remember']) : 0;
				
				$login_attempt = $this->loadModel('AccountModel')->login($_POST['email'], $_POST['password'], $remember);
				
				if( AccountModel::LOGIN_SUCCESS == $login_attempt ) {
					if( $this->getReturnUrl() )
						$this->redirect($this->getReturnUrl());
					
					$this->redirect('/dashboard');
				}
					
				$data['error'] = true;
				
				if( AccountModel::INVALID_EMAIL == $login_attempt ) {
					$data['error_message'] = 'Invalid email address';
					$data['invalid_login_counter']++;
					App::setSession('invalid_login_counter',$data['invalid_login_counter'] );
				}
				elseif( AccountModel::INVALID_PASSWORD == $login_attempt ) {
					$data['error_message'] = 'Invalid password';
					$data['invalid_login_counter']++;
					App::setSession('invalid_login_counter',$data['invalid_login_counter'] );
				}
				else {
					$data['error_message'] = 'Account not yet verified. Please check your email and verify your account';
				}
			}
			if( isset( $_GET['regs'] ) ) {
				$data['regs_success'] = 'An email verification was sent to your email address.';
			}
			$this->setPageTitle("Login");
			
			$this->add_script('https://www.google.com/recaptcha/api.js',true);
			//App::setSession('invalid_login_counter',false );
			$this->render('login',$data);
		}
		else {
			$this->redirect('/dashboard');
		}
	}
	public function forgotAction()
	{
		$this->layout = 'login';
		$params = [];
		$params['email_reset'] = "";
		$params['error'] = false;
		$params['error_type'] = 'danger';
		$this->setPageTitle( 'Forgot');
		
		if( isset( $_POST['email_reset'] )){
			if($_POST['email_reset']){
				$result = $this->loadModel()->sendResetPasswordEmail($_POST['email_reset']);

				if( $result ){
					$params['error'] = true;
					$params['error_type'] = 'info';
					$params['error_message'] = "Password reset instruction was sent to your email address.";
				}
				else {
					$params['error'] = true;
					$params['error_message'] = "Invalid email address. Please check your email address and try again.";
					$params['email_reset'] = $_POST['email_reset'];
				}
			}
			else {
				$params['error'] = true;
				$params['error_message'] = "You need to enter your email address to reset your password";
			}
		}
		
		$this->render('forgot',$params);
	}
	
	public function registerAction()
	{
		$this->layout='login';
		$data = [];
		$data['error'] = false;
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_register" ) {
		
			if( isset( $_POST['email'] ) && isset( $_POST['password'] ) && isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) 
			&& trim( $_POST['email'] ) && trim( $_POST['password'] ) && trim( $_POST['first_name'] ) && trim( $_POST['last_name'] ))
			{
				$post = array_map('trim', $_POST);

				if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
					$data['error'] = true;
					$data['error_message'] = 'Invalid email address.';
				}
				elseif($this->loadModel('AccountModel')->emailIsUnique($post['email'])) {
					$data['error'] = true;
					$data['error_message'] = 'Email address already used.';
				}
				elseif($post['password'] != $post['password_confirm']) {
					$data['error'] = true;
					$data['error_message'] = 'Password didn\'t match.';
				}
				elseif( strlen($post['password'] ) < 5 ) {
					$data['error'] = true;
					$data['error_message'] = 'Password too short, must be at least 5 characters.';
				}
				elseif( empty($post['first_name']) ) {
					$data['error'] = true;
					$data['error_message'] = 'First name must not be empty.';
				}
				elseif( empty($post['last_name']) ) {
					$data['error'] = true;
					$data['error_message'] = 'Last name must not be empty.';
				}
				else {
					$newUser = $this->loadModel('AccountModel')->createNewUser();

					if( $newUser ) {
						$this->redirect($this->createUrl('/account/login',array("regs"=>1)));
					}
					else {
						$data['error'] = true;
						$data['error_message'] = 'Unknown error.';
					}
				}
			}else{
				$data['error'] = true;
				$data['error_message'] = 'All fields are required';
			}
		}
		
		$this->setPageTitle( 'Register');
		return $this->render('register',$data);
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
	
	public function verifyAction( $hash )
	{
		$this->layout ='login';
		
		$hash_type = 1;//email verification
		
		$result = $this->loadModel('AccountModel')->verify($hash,$hash_type);
		$params = [];
		
		if( $result['status'] ) {
			if( isset( $result['email'] ) ) {
				App::setSession('login_email', $result['email']);
			}
			$params['error_type'] = 1;
		}
		else {
			$params['error_type'] = 0;
		}

		$params['error_message'] = $result['message'];

		$this->render('verify',$params);
	}
	
	public function resetPasswordAction($hash)
	{
		AccountModel::destroy();
		
		$this->layout ='login';
		
		$hash_type = AccountModel::HASH_TYPE_RESETPASSWORD;
		
		$result = $this->loadModel('AccountModel')->resetPasswordVerify($hash,$hash_type);
		$params = [];
		$params['error'] = false;
		$params['error_type'] = 'danger';
		$this->setPageTitle( 'Reset Password');
		
		if( $result['status'] ) {
			if( isset( $_POST['password_reset'] ) || isset( $_POST['password_confirm_reset'] ) ) {
				$password1 = $_POST['password_reset'];
				$password2 = $_POST['password_confirm_reset'];
				
				if($password1 != $password2) {
					$params['error'] = true;
					$params['error_message'] = 'Password didn\'t match.';
				}
				elseif( strlen($password1) < 5 ) {
					$params['error'] = true;
					$params['error_message'] = 'Password too short, must be at least 5 characters.';
				}
				else {
					$changepass = $this->loadModel('AccountModel')->resetPassword($result['email'],$password1);
					
					if( $changepass ) {
						$params['error'] = true;
						$params['error_type'] = 'info';
						$params['error_message'] = 'Password changed! You can now <a href="'.$this->createUrl( '/account/login/' ).'">login</a>';
					}
					else {
						$params['error'] = true;
						$params['error_message'] = 'Unknown error. Please try again.';
					}
				}
			}
		
			$this->render('resetpassword',$params);
		}
		else {
			$params['error_type'] = 0;
			$params['error_message'] = $result['message'];

			$this->render('verify',$params);
		}		
	}
	
	public function logoutAction( $redirect=null ){
		session_destroy();
		setcookie('user_id', '', time() - 1*24*60*60);
		setcookie('user_hash', '', time() - 1*24*60*60);
		
		if( $redirect )
			$this->redirect($redirect);
			
		$this->redirect('/');
	}
	
	public function userProfileAction($user_id)
	{
		$userdata = $this->loadModel('AccountModel')->getUserData($user_id);
		$params = [
			'userdata' => $userdata,
		];
		$this->setPageTitle( 'User Profile');
		
		$this->render('userprofile',$params);
	}
}