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
			'error_message' => false,
		];
		
		if( App::User()->isGuest )
			$this->redirect('/account/login');
			
		$model = $this->loadModel('ProjectModel');
		$projInfo = $model->getProjectInfo($id);
		$isProjOwner = $model->isProjectOwner($id);
		$userRole = $model->getProjectMemberRole(App::User()->id,$id);
		
		if( $projInfo ) {
			if($model->isProjectMember(App::User()->id,$id)) {
				$param['projInfo'] = $projInfo;
				$param['isProjOwner'] = $isProjOwner;
				$param['new_user_invite'] = false;
				$param['email_value'] = '';
				$param['userRole'] = $userRole;
				//$param['membersCount'] = $model->getOrgMembersCount($id);
			}
			else {
				$this->render('noauth',array('type'=>'Project.'));exit;
			}
		}
		else {
			$this->set404('dasboard/error');
		}
		
		if( $action == "members" ) {
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Members');
			$param['projMembers'] = $model->getProjectMembers($id);
		}
		elseif( $action == "invite" ) {
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Invite');
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
				$this->render('noauth',array('type'=>'Project.'));
				exit;
			}
		}
		elseif( $action == "messages" ) {
			$threads = $model->getThreads($id);
			$param['threads'] = $threads;
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Messages');
		}
		elseif( $action == "newthread" ) {
			
			if( $userRole >= 8 ) {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_thread") {
					if( $_POST['subject'] && $_POST['message'] ) {
						
						$thread_id = $model->createThread($id, $_POST['subject'], $_POST['message']);
						if($thread_id)
							$param['error_message'] = 'New thread created. <a href="'.$this->createUrl('/project/messages/'.$id.'/'.$thread_id).'">Click here to view.</a>';
					}
					else {
						$param['error_message'] = 'All fields are required.';
					}
				}
			}
			else {
				$this->render('noauth',array('type'=>'feature.'));
				exit;
			}
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Messages > ' . 'New Thread');
		}	
		else {
			$this->setPageTitle('My Project > ' . $projInfo['name']);
		}
		$this->render('view'.$action,$param);
	}
	
	public function messagesAction($proj_id,$thread_id)
	{
		
		$param = [
			'id' => $proj_id,
			'error_message' => false,
		];
		
		if( App::User()->isGuest )
			$this->redirect('/account/login');
		
		$model = $this->loadModel();
		
		if($model->isProjectMember(App::User()->id,$proj_id)) {

			$thread =  $model->getThread($thread_id);
			$userRole = $model->getProjectMemberRole(App::User()->id,$proj_id);
			
			if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_add_comment") {
				if( $_POST['comment'] && strlen( trim( $_POST['comment'] ) ) ) {
					$model->addComment($thread_id, $_POST['comment']);
				}
				else {
					$param['error_message'] = 'Comment should not be empty.';
				}
			}
			
			if($thread){
				$param['projInfo'] = $model->getProjectInfo($proj_id);
				$param['isThreadOwner'] = intval( $thread ) == App::User()->id ? true :false;
				$param['isProjOwner'] = $model->isProjectOwner($proj_id);
				$param['thread'] = $thread;
				$param['comments'] = $model->getComments($thread_id,$proj_id);
				$param['comment_count'] = $model->getCommentCount($thread_id);
				$param['userRole'] = $userRole;
			}
			else {
				$this->set404();
			}
			
			$this->setPageTitle('Messages > ' . $thread['subject']);
			$this->render('messages',$param);
			
		}
		else {
			$this->render('noauth',array('type'=>'Project'));
		}
	}
	
	public function accountSearchAction($keyword=null){
	
		if( isset( $_POST['keyword'] ) ) {
			$keyword = $_POST['keyword'];
		}
		
		$results = $this->loadModel('AccountModel')->searchUserByKeyword($keyword);

		App::Tools()->toJson($results);
	}
	
	public function verifyAction( $hash )
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
	/*
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
	}*/
}