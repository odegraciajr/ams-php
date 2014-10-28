<?php

class ProjectController extends Controller
{
	private $activeTab = "mainTab";
	
	public function __construct($model, $action)
	{
		parent::__construct($model, $action);
		$this->_setModel($model);
	}
	
	public function index()
	{
		RouteManager::redirect('/account');
	}
	
	public function view($id)
	{
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
		
		$projInfo = $this->_model->getProjectInfo($id);
		
		if( $projInfo ) {
			$this->_view->set('new_user_invite', false);
			$this->_view->set('email_value', '');
			$user_id = App::User()->id;
			if($this->_model->isProjectMember($user_id,$id)){
			
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project_invite") {
					if( $_POST['email'] ) {
						
						if( is_array( $_POST['email'] ) && count($_POST['email']) > 0 ) {
							foreach($_POST['email'] as $email){
								$invite = $this->_model->processInvite($email,$id);
							}
						}
						else {
							$invite = $this->_model->processInvite($_POST['email'],$id);
						}
						
						$this->_view->set('projMembers', $this->_model->getProjectMembers($id));
						$this->_view->set('activeTab', 'addmembers');
						$this->_view->set('projInfo', $projInfo);
						$this->_view->set('title', htmlspecialchars('Project Management'));

						$this->_view->set('error_add_member', true);

						if( $invite === ProjectModel::USER_ALREADY_MEMBER ) {
							$error_msg = "User already a member.";
						} elseif($invite === ProjectModel::USER_NOT_REGISTERED) {
							$error_msg = "Invitation sent to new user.";

							$account = new AccountModel();
							
							$user_id = $account->inviteNewUser($_POST['email'],2);
							
							$inviteNewuser = $this->_model->sendInviteNotRegistered($user_id,$id);
						}
						else {
							$error_msg = "Invitation sent!";
						}
						
						$this->_view->set('error_add_member_message', $error_msg);
					}
					else{
						$this->_view->set('projMembers', $this->_model->getProjectMembers($id));
						$this->_view->set('activeTab', 'addmembers');
						$this->_view->set('projInfo', $projInfo);
						$this->_view->set('title', htmlspecialchars('Project Management'));


						$this->_view->set('error_add_member', true);
						$this->_view->set('error_add_member_message', 'Please enter a valid email address.');
					}
				}
				else {
				
					$this->_view->set('projMembers', $this->_model->getProjectMembers($id));
					$this->_view->set('activeTab', $this->activeTab);
					$this->_view->set('projInfo', $projInfo);
					$this->_view->set('title', htmlspecialchars('Project Management'));
				}
			}
			else {
				$this->_setView('error');

				$this->_view->set('error_type', 0);
				$this->_view->set('error_message', 'You don\'t has access to this project. <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
				$this->_view->set('title', htmlspecialchars('Error!'));
			}
			$this->_view->set('isProjOwner',$this->_model->isProjectOwner($id));
		}
		else {
			$this->_setView('error');
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Project not found <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
			$this->_view->set('title', htmlspecialchars('Error!'));
			
		}
		return $this->_view->output();
	}
	
	public function verify( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$proj_id = $_GET['id'];
					
			$result = $this->_model->verify( $hash, $response,$proj_id );

			if( $result['status'] ) {
				$this->_view->set('error_type', 1);
			}
			else {
				$this->_view->set('error_type', 0);
			}
			$this->_view->set('error_message', $result['message']);
			$this->_view->set('title', 'Project Membership Verification');

		}
		else {
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Invalid Verification');
			$this->_view->set('title', 'Project Membership Verification');
		}

		return $this->_view->output();
	}
	
	public function verifynew( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$proj_id = $_GET['id'];
			
			if( intval($response) == 0 ) {
				$this->_setView('verify');
				$result = $this->_model->verifynew( $hash, $response,$proj_id, null );

				if( $result['status'] ) {
					$this->_view->set('error_type', 1);
				}
				else {
					$this->_view->set('error_type', 0);
				}
				$this->_view->set('error_message', $result['message']);
				$this->_view->set('title', 'Project Membership Verification');
			}
			else {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project_invitenew_user") {
					if( isset( $_POST['password'] ) && $_POST['password'] && isset( $_POST['first_name'] ) && $_POST['first_name'] && isset( $_POST['last_name'] ) && $_POST['last_name'] ) {
						
						$user_data = array(
							'password' => $_POST['password'],
							'first_name' => $_POST['first_name'],
							'last_name' => $_POST['last_name']
						);
						$result = $this->_model->verifynew( $hash, $response,$proj_id, $user_data );
						
						$this->_setView('verify');
						
						if( $result['status'] ) {
							$this->_view->set('error_type', 1);
						}
						else {
							$this->_view->set('error_type', 0);
						}
						
						$this->_view->set('error_message', $result['message']);
						$this->_view->set('title', 'Project Membership Verification');
						
					}
					else {
						$this->_view->set('error', true);
						$this->_view->set('error_message', 'All fields are required.');
					}
				}
			}
			$this->_view->set('title', 'Project Membership Verification');
		}
		else {
			$this->_setView('verify');
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Invalid Verification');
			$this->_view->set('title', 'Project Membership Verification');
			
		}

		return $this->_view->output();
	}
}