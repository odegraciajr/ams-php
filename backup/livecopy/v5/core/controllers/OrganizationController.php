<?php

class OrganizationController extends Controller
{
	private $activeTab = "mainTab";
	
	public function __construct($model, $action)
	{
		parent::__construct($model, $action);
		$this->_setModel($model);
		$this->_view->active_nav = 'organization';
	}
	
	public function index()
	{
		
		$org = new OrganizationModel();
		
		$this->_view->set('myOrg', $org->getUserOrganizations());
		
		$this->_view->set('title', htmlspecialchars('Organization'));
		return $this->_view->output();
	}
	
	public function create()
	{
		$this->_view->set('title', htmlspecialchars('Organization > Create'));
		return $this->_view->output();
	}
	
	public function view($id)
	{
		if( App::User()->isGuest )
			RouteManager::redirect('/account/login');
		
		$orgInfo = $this->_model->getOrgInfo($id);
		
		if( $orgInfo ) {
			$this->_view->set('new_user_invite', false);
			$this->_view->set('email_value', '');
			$user_id = App::User()->id;
			if($this->_model->isOrgMember($user_id,$id)){
			
				if( isset( $_POST['action_post'] ) && $_POST['action_post'] == "do_organization_invite") {
					if( $_POST['email'] ) {
						$invite = $this->_model->processInvite($_POST['email'],$id);
						
						$this->_view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
						$this->_view->set('activeTab', 'addmembers');
						$this->_view->set('orgInfo', $orgInfo);
						$this->_view->set('title', htmlspecialchars('Organization Management'));


						$this->_view->set('error_add_member', true);

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
						
						$this->_view->set('error_add_member_message', $error_msg);
					}
					else{
						$this->_view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
						$this->_view->set('activeTab', 'addmembers');
						$this->_view->set('orgInfo', $orgInfo);
						$this->_view->set('title', htmlspecialchars('Organization Management'));


						$this->_view->set('error_add_member', true);
						$this->_view->set('error_add_member_message', 'Please enter a valid email address.');
					}
				}
				else {
				
					$this->_view->set('orgMembers', $this->_model->getOrganizationsMembers($id));
					$this->_view->set('activeTab', $this->activeTab);
					$this->_view->set('orgInfo', $orgInfo);
					$this->_view->set('title', htmlspecialchars('Organization Management'));
				}
			}
			else {
				$this->_setView('error');

				$this->_view->set('error_type', 0);
				$this->_view->set('error_message', 'You don\'t has access to this organization. <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
				$this->_view->set('title', htmlspecialchars('Error!'));
			}
			$this->_view->set('isOrgOwner',$this->_model->isOrgOwner($id));
		}
		else {
			$this->_setView('error');
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Organization not found <a href="'.RouteManager::createUrl('/account').'">Go back to account settings<a>.');
			$this->_view->set('title', htmlspecialchars('Error!'));
			
		}
		return $this->_view->output();
	}
	
	public function verify( $hash )
	{
		AccountModel::destroy();
		if( isset( $hash ) ) {
			$response = $_GET['accept'];
			$org_id = $_GET['id'];
					
			$result = $this->_model->verify( $hash, $response,$org_id );

			if( $result['status'] ) {
				$this->_view->set('error_type', 1);
			}
			else {
				$this->_view->set('error_type', 0);
			}
			$this->_view->set('error_message', $result['message']);
			$this->_view->set('title', 'Organization Membership Verification');

		}
		else {
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Invalid Verification');
			$this->_view->set('title', 'Organization Membership Verification');
		}

		return $this->_view->output();
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
					$this->_view->set('error_type', 1);
				}
				else {
					$this->_view->set('error_type', 0);
				}
				$this->_view->set('error_message', $result['message']);
				$this->_view->set('title', 'Organization Membership Verification');
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
							$this->_view->set('error_type', 1);
						}
						else {
							$this->_view->set('error_type', 0);
						}
						
						$this->_view->set('error_message', $result['message']);
						$this->_view->set('title', 'Organization Membership Verification');
						
					}
					else {
						$this->_view->set('error', true);
						$this->_view->set('error_message', 'All fields are required.');
					}
				}
			}
			$this->_view->set('title', 'Organization Membership Verification');
		}
		else {
			$this->_setView('verify');
			$this->_view->set('error_type', 0);
			$this->_view->set('error_message', 'Invalid Verification');
			$this->_view->set('title', 'Organization Membership Verification');
			
		}

		return $this->_view->output();
	}
}