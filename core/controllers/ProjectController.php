<?php

class ProjectController extends Controller
{
	public function __construct()
	{
		$this->activeNav = 'project';
	}

	public function indexAction()
	{
		$this->loginGuest();
		
		$this->setPageTitle('Project');
		
		$params = [
			'myProject' => $this->loadModel('ProjectModel')->getUserProjects()
		];
		
		$this->render('index',$params);
	}
	
	public function createAction()
	{
		$this->loginGuest();
		
		$params = [];
		$params['myOrg'] = $this->loadModel('OrganizationModel')->getOwnedOrganizations();
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project" ) {
			if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
				$newProj = $this->loadModel('ProjectModel')->createProject();
				if($newProj)
					$params = array('error_message' => 'Project Created. <a href="'.$this->createUrl('/project/view/'.$newProj).'">Click here to view.</a>');
			}
		}
		
		$this->setPageTitle('Project > Create');

		$this->render('create',$params);
	}
	
	
	public function viewAction($id,$action='main')
	{
		$this->loginGuest();
		
		$params = [
			'id' => $id,
			'error_message' => false,
			'activity_types' => null
		];

		$model = $this->loadModel('ProjectModel');
		$projInfo = $model->getProjectInfo($id);
		$isProjOwner = $model->isProjectOwner($id);
		$userRole = $model->getProjectMemberRole(App::User()->id,$id);
		
		if( $projInfo ) {
			if($model->isProjectMember(App::User()->id,$id)) {
				$params['projInfo'] = $projInfo;
				$params['isProjOwner'] = $isProjOwner;
				$params['new_user_invite'] = false;
				$params['email_value'] = '';
				$params['userRole'] = $userRole;
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
			$params['projMembers'] = $model->getProjectMembers($id);
		}
		elseif( $action == "invite" ) {
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Invite');
			if( $isProjOwner ) {
								
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
						
						$params['error_add_member'] = true;

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

						$params['error_add_member_message'] = $error_msg;
					}
				}
				$params['projMembers'] = $model->getProjectMembers($id);
			}
			else {
				$this->renderEnd('noauth',array('type'=>'Project.'));
			}
		}
		elseif( $action == "messages" ) {
			$threads = $model->getThreads($id);
			$params['threads'] = $threads;
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Messages');
		}
		elseif( $action == "newthread" ) {
			
			if( $userRole >= 8 ) {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_thread") {
					if( $_POST['subject'] && $_POST['message'] ) {
						
						$thread_id = $model->createThread($id, $_POST['subject'], $_POST['message']);
						if($thread_id)
							$params['error_message'] = 'New thread created. <a href="'.$this->createUrl('/project/messages/'.$id.'/'.$thread_id).'">Click here to view.</a>';
					}
					else {
						$params['error_message'] = 'All fields are required.';
					}
				}
			}
			else {
				$this->render('noauth',array('type'=>'feature.'));
				exit;
			}
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Messages > ' . 'New Thread');
		}
		elseif( $action == "newactivity" ) {
			$this->add_style('/assets/css/bootstrap-datetimepicker.min.css');
			$this->add_script('/assets/js/moment.js',true);
			$this->add_script('/assets/js/bootstrap-datetimepicker.min.js',true);


			if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_newactivity") {
				if( $_POST['name'] && $_POST['type_id'] ) {
					$activity_id = $this->loadModel('ActivityModel')->addActivity($id);
					if($activity_id)
						$params['error_message'] = 'New Activty created. <a href="'.$this->createUrl('/project/activity/'.$id.'/'.$activity_id).'">Click here to view.</a>';
				}
				else {
					$params['error_message'] = 'All fields are required.';
				}
			}
			$params['activities'] = $this->loadModel('ActivityModel')->getProjectActivity($id);
			$params['projMembers'] = $model->getProjectMembers($id);
			$params['activity_types'] = $this->loadModel('ActivityModel')->getActivityTypes();

			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'New Activity');
		}
		elseif( $action == "activity" ) {
			$params['activities'] = $this->loadModel('ActivityModel')->getProjectActivity($id);
			
			$this->setPageTitle('My Project > ' . $projInfo['name'] . ' > ' . 'Activity');
		}
		else {
			$this->setPageTitle('My Project > ' . $projInfo['name']);
		}
		$this->render('view'.$action,$params);
	}
	
	public function messagesAction($proj_id,$thread_id)
	{
		$this->loginGuest();
		
		$params = [
			'id' => $proj_id,
			'error_message' => false,
		];

		$model = $this->loadModel();
		
		if($model->isProjectMember(App::User()->id,$proj_id)) {

			$thread =  $model->getThread($thread_id);
			$userRole = $model->getProjectMemberRole(App::User()->id,$proj_id);
			
			if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_add_comment") {
				if( $_POST['comment'] && strlen( trim( $_POST['comment'] ) ) ) {
					$model->addComment($thread_id, $_POST['comment']);
				}
				else {
					$params['error_message'] = 'Comment should not be empty.';
				}
			}
			
			if($thread){
				$params['projInfo'] = $model->getProjectInfo($proj_id);
				$params['isThreadOwner'] = intval( $thread ) == App::User()->id ? true :false;
				$params['isProjOwner'] = $model->isProjectOwner($proj_id);
				$params['thread'] = $thread;
				$params['comments'] = $model->getComments($thread_id,$proj_id);
				$params['comment_count'] = $model->getCommentCount($thread_id);
				$params['userRole'] = $userRole;
			}
			else {
				$this->set404();
			}
			
			$this->setPageTitle('Messages > ' . $thread['subject']);
			$this->render('messages',$params);
			
		}
		else {
			$this->render('noauth',array('type'=>'Project'));
		}
	}
	
	public function activityAction($proj_id,$act_id)
	{
		$this->loginGuest();
		
		$params = [
			'id' => $proj_id,
			'error_message' => false,
		];
		$ActModel = $this->loadModel('ActivityModel');
		$ProjModel = $this->loadModel();
		$userRole = $ProjModel->getProjectMemberRole(App::User()->id,$proj_id);
		
		$params['projInfo'] = $ProjModel->getProjectInfo($proj_id);
		
		$params['activity'] = $ActModel->getActivity($act_id);
		$params['userRole'] = $userRole;
		$params['assignedUsers'] = $ActModel->getAssignedUsers($act_id);
		$params['prereqActivities'] = $this->loadModel('ActivityModel')->getPrerequisiteActivities($act_id);
		
		$this->render('activity',$params);
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
		$this->layout = "login";

		$params = [
			'error' => false,
		];
		$model = $this->loadModel();
		
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$proj_id = $_GET['id'];
					
			$result = $model->verify( $hash, $response,$proj_id );

			if( $result['status'] ) {
				$params['error_type'] = 1;
			}
			else {
				$params['error_type'] = 0;
			}
			$params['error_message'] = $result['message'];
			$this->setPageTitle( 'Project Membership Verification');

		}
		else {
			$params['error_type'] = 0;
			$params['error_message'] = 'Invalid Verification';
			$this->setPageTitle( 'Project Membership Verification');
		}

		$this->render('verify',$params);
	}
	
	public function verifynewAction( $hash )
	{
		$this->layout = "login";
		
		AccountModel::destroy();
		
		$params = [
			'error' => false,
		];
		$model = $this->loadModel();
		
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$proj_id = $_GET['id'];
			
			if( intval($response) == 0 ) {
				
				$result = $model->verifynew( $hash, $response,$proj_id, null );

				if( $result['status'] ) {
					$params['error_type'] = 1;
				}
				else {
					$params['error_type'] = 0;
				}
				$params['error_message'] = $result['message'];
				$this->setPageTitle( 'Project Membership Verification');
				
				$this->render('verify',$params);exit;
			}
			else {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_project_invitenew_user") {
					if( isset( $_POST['password'] ) && $_POST['password'] && isset( $_POST['first_name'] ) && $_POST['first_name'] && isset( $_POST['last_name'] ) && $_POST['last_name'] ) {
						
						$user_data = array(
							'password' => $_POST['password'],
							'first_name' => $_POST['first_name'],
							'last_name' => $_POST['last_name']
						);
						$result = $model->verifynew( $hash, $response,$proj_id, $user_data );

						if( $result['status'] ) {
							if( isset( $result['user_id'] ) ) {
								$userData = $this->loadModel('AccountModel')->getUserData($result['user_id']);
								App::setSession('login_email',$userData['email']);
							}
							$params['error_type'] = 1;
						}
						else {
							$params['error_type'] = 0;
						}
						
						$params['error_message'] = $result['message'];
						$this->setPageTitle( 'Project Membership Verification');
						
						$this->render('verify',$params);exit;
					}
					else {
						$params['error'] = true;
						$params['error_message'] = 'All fields are required.';
					}
				}
			}
			$this->setPageTitle( 'Project Membership Verification');
		}
		else {

			$params['error_type'] = 0;
			$params['error_message'] = 'Invalid Verification';
			$this->setPageTitle( 'Project Membership Verification');
			$this->render('verify',$params);exit;
			
		}

		$this->render('verifynew',$params);
	}
}