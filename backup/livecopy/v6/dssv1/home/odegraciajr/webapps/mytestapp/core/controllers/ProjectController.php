<?php

class ProjectController extends Controller
{
	public function __construct()
	{
		$this->activeNav = 'project';
	}

	public function indexAction()
	{
		$this->setPageTitle('Project');
		
		$params = [
			'myProject' => $this->loadModel('ProjectModel')->getUserProjects()
		];
		
		$this->render('index',$params);
	}
	
	public function createAction()
	{
		$param = [];
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project" ) {
			if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
				$newProj = $this->loadModel('ProjectModel')->createProject();
				if($newProj)
					$param = array('error_message' => 'Project Created. <a href="'.$this->createUrl('/project/view/'.$newProj).'">Click here to view.</a>');
			}
		}
		
		$this->setPageTitle('Project > Create');

		$this->render('create',$param);
	}
	
	
	public function viewAction($id,$action='main')
	{
		$param = [
			'id' => $id,
			
		];
		
		if( App::User()->isGuest )
			$this->redirect('/account/login');
			
		$model = $this->loadModel('ProjectModel');
		$projInfo = $model->getProjectInfo($id);
		$isProjOwner = $model->isProjectOwner($id);
		
		if( $projInfo ) {
			if($model->isProjectMember(App::User()->id,$id)) {
				$param['projInfo'] = $projInfo;
				$param['isProjOwner'] = $isProjOwner;
				$param['new_user_invite'] = false;
				$param['email_value'] = '';
				//$param['membersCount'] = $model->getOrgMembersCount($id);
			}
			else {
				$this->render('noauth',array('type'=>'Project'));
			}
		}
		else {
			$this->set404('dasboard/error');
		}
		
		if( $action == "members" ) {
			$param['projMembers'] = $model->getProjectMembers($id);
		}
		elseif( $action == "invite" ) {
		
			if( $isProjOwner ) {
				$param['projMembers'] = $model->getProjectMembers($id);
				
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project_invite") {
					if( $_POST['email'] ) {
						if( is_array( $_POST['email'] ) && count($_POST['email']) > 0 ) {
							foreach($_POST['email'] as $email){
								$invite = $model->processInvite($email,$id);
							}
						}
						else {
							$invite = $model->processInvite($_POST['email'],$id);
						}
						
						$param['error_add_member'] = true;

						if( $invite === ProjectModel::USER_ALREADY_MEMBER ) {
							$error_msg = "User already a member.";
						} elseif($invite === ProjectModel::USER_NOT_REGISTERED) {
							$error_msg = "Invitation sent to new user.";

							$account = new AccountModel();
							
							$user_id = $account->inviteNewUser($_POST['email'],2);
							
							$inviteNewuser = $model->sendInviteNotRegistered($user_id,$id);
						}
						else {
							$error_msg = "Invitation sent!";
						}

						$param['error_add_member_message'] = $error_msg;
					}
				}
			}
			else {
				$this->render('noauth',array('type'=>'Project'));
				exit;
			}
		}
		else {
			$this->setPageTitle('My Project > ' . $projInfo['name']);
		}
		$this->render('view'.$action,$param);
	}
	/*public function view($id)
	{
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
		
		$projInfo = $this->_model->getProjectInfo($id);
		
		if( $projInfo ) {
			$this->view->set('new_user_invite', false);
			$this->view->set('email_value', '');
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
						
						$this->view->set('projMembers', $this->_model->getProjectMembers($id));
						$this->view->set('activeTab', 'addmembers');
						$this->view->set('projInfo', $projInfo);
						$this->view->setPageTitle('Project Management');

						$this->view->set('error_add_member', true);

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
						
						$this->view->set('error_add_member_message', $error_msg);
					}
					else{
						$this->view->set('projMembers', $this->_model->getProjectMembers($id));
						$this->view->set('activeTab', 'addmembers');
						$this->view->set('projInfo', $projInfo);
						$this->view->setPageTitle('Project Management');


						$this->view->set('error_add_member', true);
						$this->view->set('error_add_member_message', 'Please enter a valid email address.');
					}
				}
				else {
				
					$this->view->set('projMembers', $this->_model->getProjectMembers($id));
					$this->view->set('activeTab', $this->activeTab);
					$this->view->set('projInfo', $projInfo);
					$this->view->setPageTitle('Project Management');
				}
			}
			else {
				$this->_setView('error');

				$this->view->set('error_type', 0);
				$this->view->set('error_message', 'You don\'t has access to this project. <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
				$this->view->setPageTitle('Error!');
			}
			$this->view->set('isProjOwner',$this->_model->isProjectOwner($id));
		}
		else {
			$this->_setView('error');
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Project not found <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
			$this->view->setPageTitle('Error!');
			
		}
		return $this->view->output();
	}*/
	public function accountSearchAction($keyword=null){
	
		if( isset( $_POST['keyword'] ) ) {
			$keyword = $_POST['keyword'];
		}
		
		$results = $this->loadModel('AccountModel')->searchUserByKeyword($keyword);

		App::Tools()->toJson($results);
	}
	
	public function verify( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$proj_id = $_GET['id'];
					
			$result = $this->_model->verify( $hash, $response,$proj_id );

			if( $result['status'] ) {
				$this->view->set('error_type', 1);
			}
			else {
				$this->view->set('error_type', 0);
			}
			$this->view->set('error_message', $result['message']);
			$this->view->setPageTitle( 'Project Membership Verification');

		}
		else {
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Invalid Verification');
			$this->view->setPageTitle( 'Project Membership Verification');
		}

		return $this->view->output();
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
					$this->view->set('error_type', 1);
				}
				else {
					$this->view->set('error_type', 0);
				}
				$this->view->set('error_message', $result['message']);
				$this->view->setPageTitle( 'Project Membership Verification');
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
							$this->view->set('error_type', 1);
						}
						else {
							$this->view->set('error_type', 0);
						}
						
						$this->view->set('error_message', $result['message']);
						$this->view->setPageTitle( 'Project Membership Verification');
						
					}
					else {
						$this->view->set('error', true);
						$this->view->set('error_message', 'All fields are required.');
					}
				}
			}
			$this->view->setPageTitle( 'Project Membership Verification');
		}
		else {
			$this->_setView('verify');
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Invalid Verification');
			$this->view->setPageTitle( 'Project Membership Verification');
			
		}

		return $this->view->output();
	}
}