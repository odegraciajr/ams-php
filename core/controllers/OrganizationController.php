<?php

class OrganizationController extends Controller
{
	public function __construct()
	{
		$this->activeNav = 'organization';
	}

	public function indexAction()
	{
		$this->loginGuest();
		
		$this->setPageTitle('Organization');
		
		$params = [
			'myOrg' => $this->loadModel('OrganizationModel')->getUserOrganizations()
		];
		
		$this->render('index',$params);
	}
	
	public function createAction()
	{
		$this->loginGuest();
		
		$param = [];
		
		if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization" ) {
			if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
				$org_id = $this->loadModel('OrganizationModel')->createOrganization();
				if($org_id)
					$param = array('error_message' => 'Organization Created. <a href="'.$this->createUrl('/organization/view/'.$org_id).'">Click here to view.</a>');
			}
		}
		
		$this->setPageTitle('Organization > Create');

		$this->render('create',$param);
	}
	
	public function editAction($id)
	{
		$this->loginGuest();
		
		$params = [
			'id' => $id,
			'error_message' => false
		];

		$model = $this->loadModel('OrganizationModel');
		$orgInfo = $model->getOrgInfo($id);
		$isOrgOwner = $model->isOrgOwner($id);
		
		if( $orgInfo ) {
		
			if($isOrgOwner){
			
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_update_organization" ) {
					if( isset( $_POST['name'] ) && trim( $_POST['name'] ) ) {
						$updatedOrg = $model->updateOrganization($id);
						if($updatedOrg){
							$params['error_message']='Organization Updated. <a href="'.$this->createUrl('/organization/view/'.$updatedOrg).'">Click here to view.</a>';
							$orgInfo = $model->getOrgInfo($id);
						}
					}
				}
			
				$params['orgInfo'] = $orgInfo;
			}
			else{
				$this->renderEnd('noauth',array('type'=>'Organization.'));
			}
			
		}
		else {
			$this->set404();
		}
		$this->setPageTitle('Organization > Edit');
		
		$this->render('edit',$params);
		
	}
	
	public function viewAction($id,$action='main')
	{
		$this->loginGuest();
		
		$param = [
			'id' => $id,
			
		];

		$model = $this->loadModel('OrganizationModel');
		$orgInfo = $model->getOrgInfo($id);
		$isOrgOwner = $model->isOrgOwner($id);
		
		if( $orgInfo ) {
			if($model->isOrgMember(App::User()->id,$id)) {
				$param['orgInfo'] = $orgInfo;
				$param['isOrgOwner'] = $isOrgOwner;
				$param['new_user_invite'] = false;
				$param['email_value'] = '';
				$param['membersCount'] = $model->getOrgMembersCount($id);
				
			}
			else {
				$this->renderEnd('noauth',array('type'=>'Organization'));
			}
		}
		else {
			$this->set404('dasboard/error');
		}
		
		if( $action == "members" ) {
			$param['orgMembers'] = $model->getOrganizationsMembers($id);
		}
		elseif( $action == "invite" ) {
			if( $isOrgOwner ) {
			
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization_invite") {
					if( $_POST['email'] ) {
						$invite = $model->processInvite($_POST['email'],$id);
						
						$param['error_add_member'] = true;
						if( $invite === OrganizationModel::USER_ALREADY_MEMBER ) {
							$error_msg = "User already a member.";
						} elseif($invite === OrganizationModel::USER_NOT_REGISTERED) {
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
				$this->render('noauth',array('type'=>'Organization'));
				exit;
			}
		}
		elseif( $action == "projects" ) {
				$param['projects'] = $this->loadModel('ProjectModel')->getOrganizationProjects($id);
		}
		else {
			$this->setPageTitle('My Organization > ' . $orgInfo['name']);
		}
		
		$this->render('view'.$action,$param);
	}
	
	public function verifyAction( $hash )
	{
		$this->layout = "login";
		
		AccountModel::destroy();
		
		$param = [
			'error' => false,
		];
		$model = $this->loadModel();
		
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$org_id = $_GET['id'];
					
			$result = $model->verify( $hash, $response,$org_id );

			if( $result['status'] ) {
				$param['error_type'] = 1;
			}
			else {
				$param['error_type'] = 0;
			}
			$param['error_message'] = $result['message'];
			$this->setPageTitle( 'Organization Membership Verification');

		}
		else {
			$param['error_type'] = 0;
			$param['error_message'] = 'Invalid Verification';
			$this->setPageTitle( 'Organization Membership Verification');
		}

		$this->render('verify',$param);
	}
	public function verifynewAction( $hash )
	{
		$this->layout = "login";
		
		AccountModel::destroy();
		
		$param = [
			'error' => false,
		];
		$model = $this->loadModel();
		
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$org_id = $_GET['id'];
			
			if( intval($response) == 0 ) {
				$result = $model->verifynew( $hash, $response,$org_id, null );

				if( $result['status'] ) {
					$param['error_type'] = 1;
				}
				else {
					$param['error_type'] = 0;
				}
				$param['error_message'] = $result['message'];
				$this->setPageTitle( 'Organization Membership Verification');
				
				$this->render('verify',$param);exit;
			}
			else {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization_invitenew_user") {
					if( isset( $_POST['password'] ) && $_POST['password'] && isset( $_POST['first_name'] ) && $_POST['first_name'] && isset( $_POST['last_name'] ) && $_POST['last_name'] ) {
						
						$user_data = array(
							'password' => $_POST['password'],
							'first_name' => $_POST['first_name'],
							'last_name' => $_POST['last_name']
						);
						$result = $model->verifynew( $hash, $response,$org_id, $user_data );

						if( $result['status'] ) {
							if( isset( $result['user_id'] ) ) {
								$userData = $this->loadModel('AccountModel')->getUserData($result['user_id']);
								App::setSession('login_email',$userData['email']);
							}
							$param['error_type'] = 1;
						}
						else {
							$param['error_type'] = 0;
						}
						$param['error_message'] =$result['message'];
						$this->setPageTitle( 'Organization Membership Verification');
						
						$this->render('verify',$param);exit;
					}
					else {
						$param['error'] = true;
						$param['error_message'] = 'All fields are required.';
					}
				}
			}
			$this->setPageTitle( 'Organization Membership Verification');
		}
		else {
			$param['error_type'] = 0;
			$param['error_message'] = 'Invalid Verification';
			$this->setPageTitle( 'Organization Membership Verification');
			$this->render('verify',$param);exit;
			
		}

		$this->render('verifynew',$param);
	}
}