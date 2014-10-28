<?php

class OrganizationController extends Controller
{
	public function __construct()
	{
		$this->activeNav = 'organization';
	}

	public function indexAction()
	{
		$this->setPageTitle('Organization');
		
		$params = [
			'myOrg' => $this->loadModel('OrganizationModel')->getUserOrganizations()
		];
		
		$this->render('index',$params);
	}
	
	public function createAction()
	{
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
	
	public function viewAction($id,$action='main')
	{
		$param = [
			'id' => $id,
			
		];
		
		if( App::User()->isGuest )
			$this->redirect('/account/login');
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
				$this->render('noauth',array('type'=>'Organization'));
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
		else {
			$this->setPageTitle('My Organization > ' . $orgInfo['name']);
		}
		
		$this->render('view'.$action,$param);
	}
	
	/*public function view($id)
	{
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
		
		$orgInfo = $this->_model->getOrgInfo($id);
		
		if( $orgInfo ) {
			$this->view->set('new_user_invite', false);
			$this->view->set('email_value', '');
			$user_id = App::User()->id;
			if($this->_model->isOrgMember($user_id,$id)){
			
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization_invite") {
					if( $_POST['email'] ) {
						$invite = $this->_model->processInvite($_POST['email'],$id);
						
						$this->view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
						$this->view->set('activeTab', 'addmembers');
						$this->view->set('orgInfo', $orgInfo);
						$this->view->setPageTitle('Organization Management');


						$this->view->set('error_add_member', true);

						if( $invite === OrganizationModel::USER_ALREADY_MEMBER ) {
							$error_msg = "User already a member.";
						} elseif($invite === OrganizationModel::USER_NOT_REGISTERED) {
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
						$this->view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
						$this->view->set('activeTab', 'addmembers');
						$this->view->set('orgInfo', $orgInfo);
						$this->view->setPageTitle('Organization Management');


						$this->view->set('error_add_member', true);
						$this->view->set('error_add_member_message', 'Please enter a valid email address.');
					}
				}
				else {
				
					$this->view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
					$this->view->set('activeTab', $this->activeTab);
					$this->view->set('orgInfo', $orgInfo);
					$this->view->setPageTitle('Organization Management');
				}
			}
			else {
				$this->_setView('error');

				$this->view->set('error_type', 0);
				$this->view->set('error_message', 'You don\'t has access to this organization. <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
				$this->view->setPageTitle( 'Error!');
			}
			$this->view->set('isOrgOwner',$this->_model->isOrgOwner($id));
		}
		else {
			$this->_setView('error');
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Organization not found <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
			$this->view->setPageTitle('Error!');
			
		}
		return $this->view->output();
	}*/
	
	public function verify( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$org_id = $_GET['id'];
					
			$result = $this->_model->verify( $hash, $response,$org_id );

			if( $result['status'] ) {
				$this->view->set('error_type', 1);
			}
			else {
				$this->view->set('error_type', 0);
			}
			$this->view->set('error_message', $result['message']);
			$this->view->setPageTitle( 'Organization Membership Verification');

		}
		else {
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Invalid Verification');
			$this->view->setPageTitle( 'Organization Membership Verification');
		}

		return $this->view->output();
	}
	public function verifynew( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$org_id = $_GET['id'];
			
			if( intval($response) == 0 ) {
				$this->_setView('verify');
				$result = $this->_model->verifynew( $hash, $response,$org_id, null );

				if( $result['status'] ) {
					$this->view->set('error_type', 1);
				}
				else {
					$this->view->set('error_type', 0);
				}
				$this->view->set('error_message', $result['message']);
				$this->view->setPageTitle( 'Organization Membership Verification');
			}
			else {
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization_invitenew_user") {
					if( isset( $_POST['password'] ) && $_POST['password'] && isset( $_POST['first_name'] ) && $_POST['first_name'] && isset( $_POST['last_name'] ) && $_POST['last_name'] ) {
						
						$user_data = array(
							'password' => $_POST['password'],
							'first_name' => $_POST['first_name'],
							'last_name' => $_POST['last_name']
						);
						$result = $this->_model->verifynew( $hash, $response,$org_id, $user_data );
						
						$this->_setView('verify');
						
						if( $result['status'] ) {
							$this->view->set('error_type', 1);
						}
						else {
							$this->view->set('error_type', 0);
						}
						
						$this->view->set('error_message', $result['message']);
						$this->view->setPageTitle( 'Organization Membership Verification');
						
					}
					else {
						$this->view->set('error', true);
						$this->view->set('error_message', 'All fields are required.');
					}
				}
			}
			$this->view->setPageTitle( 'Organization Membership Verification');
		}
		else {
			$this->_setView('verify');
			$this->view->set('error_type', 0);
			$this->view->set('error_message', 'Invalid Verification');
			$this->view->setPageTitle( 'Organization Membership Verification');
			
		}

		return $this->view->output();
	}
}