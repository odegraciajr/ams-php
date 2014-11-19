<?php

class OrganizationModel extends Model
{
	/**
	 * Error Handling
	 **/
	const ROLE_ADMIN			= 9;
	const ROLE_MEMBER			= 1;
	const STATUS_PENDING		= 0;
	const STATUS_ACTIVE			= 1;
	const STATUS_DECLINED		= 2;
	const USER_NOT_REGISTERED	= -1;
	const USER_ALREADY_MEMBER	= 2;
	const HASH_TYPE_ORGINVITE 	= 2;
	
	public static function getRole($role)
	{
		$roleArray = array(
			self::ROLE_ADMIN => 'Admin',
			self::ROLE_MEMBER => 'Member'
		);
		
		return $roleArray[$role];
	}
	public static function getStatus($status)
	{
		$statusArray = array(
			self::STATUS_ACTIVE => 'Active',
			self::STATUS_PENDING => 'Pending'
		);
		
		return $statusArray[$status];
	}
	
	public function createOrganization( $owner_id=null, $name=null, $desc=null )
	{
		
		$post = array_map('trim', $_POST);
		
		if( is_array( $post ) && count( $post ) > 0 ) {
			$org_id = 0;
			$owner_id = App::User()->id;
			$name = $post['name'];
			$description = $post['description'];
			
			$sql = "INSERT INTO organization(name, description,user_id,date_created) ";
			$sql .="VALUES(?,?,?,NOW())";
			
			$newOrg = $this->_db->prepare($sql);
			$newOrg->bindValue(1, $name, PDO::PARAM_STR);
			$newOrg->bindValue(2, $description, PDO::PARAM_STR);
			$newOrg->bindValue(3, $owner_id, PDO::PARAM_INT);
			$newOrg->execute();
			
			$org_id = $this->_db->lastInsertId();
			
			if( $newOrg->rowCount() ) {
				//Add the owner as memebr also
				$this->createOrganizationMember($org_id, $owner_id, self::ROLE_ADMIN, self::STATUS_ACTIVE );
				
				$mail = App::Mail();
				
				$mail->From = 'noreply@datastreamsolutions.com';
				$mail->FromName = 'Mailer';
				$mail->addAddress(App::User()->email, App::User()->full_name);

				$mail->isHTML(true);
				$profile_link = App::baseUrl() . "/account";
				$first_name = App::User()->first_name;
				$mail->Subject = 'Organization Created';
				$mail->Body    = "Hi $first_name,<br/><br/>Organization: <b>$name</b> has been created. You can view your organization settings at $profile_link";
				$mail->AltBody = "Hi $first_name,\r\nOrganization: $name has been created. You can view your organization settings at $profile_link";
				
				$mail->send();
				
			}
			
			return $org_id;
		}
		return false;
	}
	
	public function updateOrganization( $org_id )
	{
		
		$data = array_map('trim', $_POST);
		
		if( is_array( $data ) && count( $data ) > 0 ) {
			
			$owner_id = App::User()->id;
			$name = $data['name'];
			$description = $data['description'];
			
			$sql = "UPDATE organization SET name=?,description=? WHERE id=? AND user_id=?";
			
			$updateproj = $this->_db->prepare($sql);
			$updateproj->bindValue(1, $name, PDO::PARAM_STR);
			$updateproj->bindValue(2, $description, PDO::PARAM_STR);
			$updateproj->bindValue(3, $org_id, PDO::PARAM_INT);
			$updateproj->bindValue(4, $owner_id, PDO::PARAM_INT);
			$updateproj->execute();
			
			if($org_id)
				return $org_id;
			
		}
		return false;
	}
	
	public function createOrganizationMember( $org_id, $user_id, $role_id=1, $status=0 )
	{
		$sql = "INSERT INTO organization_members(organization_id,user_id,role_id,status,date_joined) ";
		$sql .="VALUES(?,?,?,?,NOW())";
		
		$newOrgMember = $this->_db->prepare($sql);
		$newOrgMember->bindValue(1, $org_id, PDO::PARAM_INT);
		$newOrgMember->bindValue(2, $user_id, PDO::PARAM_INT);
		$newOrgMember->bindValue(3, $role_id, PDO::PARAM_INT);
		$newOrgMember->bindValue(4, $status, PDO::PARAM_INT);
		$newOrgMember->execute();
		
		return $this->_db->lastInsertId();
	}
	
	public function getUserOrganizations($user_id=null)
	{
		if(!$user_id) {
			$user_id = App::User()->id;
		}
		
		$sql = "SELECT o.id,o.name,o.description,om.role_id,om.date_joined ";
		$sql .= "FROM organization_members AS om ";
		$sql .= "INNER JOIN organization AS o ";
		$sql .= "ON om.organization_id=o.id ";
		$sql .= "WHERE om.user_id=? ";
		$sql .= "ORDER BY om.role_id DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}

	public function getOwnedOrganizations($user_id=null)
	{
		if(!$user_id) {
			$user_id = App::User()->id;
		}
		
		$sql = "SELECT o.id,o.name,o.description,om.role_id,om.date_joined ";
		$sql .= "FROM organization_members AS om ";
		$sql .= "INNER JOIN organization AS o ";
		$sql .= "ON om.organization_id=o.id ";
		$sql .= "WHERE om.user_id=? AND om.role_id = 9 ";
		$sql .= "ORDER BY om.role_id DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function getOrgInfo($id)
	{
		$sql = "SELECT * FROM organization WHERE id=?";
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return $sth->fetch();
		}
		return false;		
	}
	public function getOrgMembersCount($id)
	{
		$sql = "SELECT status,COUNT(organization_id) AS members FROM organization_members WHERE organization_id=? GROUP BY status ORDER BY status DESC";
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return $sth->fetchAll();
		}
		return false;
	}
	public function getOrganizationsMembers($org_id)
	{
		if(!$org_id)
			return false;
		
		$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,o.id,o.name,om.role_id,om.date_joined,om.status,om.user_id ";
		$sql .= "FROM organization_members AS om ";
		$sql .= "INNER JOIN organization AS o ";
		$sql .= "ON om.organization_id=o.id ";
		$sql .= "INNER JOIN users AS u ON om.user_id=u.id ";
		$sql .= "WHERE om.organization_id=? ";
		$sql .= "ORDER BY om.role_id DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $org_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function isOrgMember($user_id, $org_id)
	{
		$sth = $this->_db->prepare("SELECT organization_id FROM organization_members WHERE user_id=? AND organization_id=?");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $org_id, PDO::PARAM_INT);
		$sth->execute();

		if ($sth->rowCount() > 0) {
			return true;
		}
		return false;		
	}
	private function sendInvite($user_id,$org_id)
	{
		$sth = $this->_db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$membership_id = $this->createOrganizationMember($org_id, $user_id, self::ROLE_MEMBER, self::STATUS_PENDING );
			$row = $sth->fetch();
			$org = $this->getOrgInfo($org_id);
			$mail = App::Mail();

			$mail->From = 'noreply@datastreamsolutions.com';
			$mail->FromName = 'Mailer';
			$mail->addAddress($row['email'], $row['first_name'] ." ". $row['last_name']);

			$mail->isHTML(true);
			
			$first_name = $row['first_name'];
			
			$mail->Subject = 'Organization Invite';
			$message = "Hi $first_name,<br/><br/>I would like to invite you to join us.<br/>";
			$message .= "ORGANIZATION NAME: <b>".$org['name']."</b><br/>";
			$message .= "ORGANIZATION Description: <b>".$org['description']."</b><br/><br/>";
			$hash = $this->createInviteLink($membership_id,$org_id);
			
			$verify_link = App::baseUrl() . "/organization/verify/$hash?id=$org_id&accept=";
			$message .= "Accept Invitation: <a href='$verify_link". 1 ."'>Accept</a><br/>";
			$message .= "Decline Invitation: <a href='$verify_link". 0 ."'>Decline</a><br/>";
			
			$mail->Body = $message;
			return $mail->send();
		}
		return false;
	}
	public function sendInviteNotRegistered($user_id,$org_id)
	{
		$sth = $this->_db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$membership_id = $this->createOrganizationMember($org_id, $user_id, self::ROLE_MEMBER, self::STATUS_PENDING );
			$row = $sth->fetch();
			$org = $this->getOrgInfo($org_id);
			$mail = App::Mail();

			$mail->From = 'noreply@datastreamsolutions.com';
			$mail->FromName = 'Mailer';
			$mail->addAddress($row['email'], 'New User' );

			$mail->isHTML(true);
			
			$first_name = $row['first_name'];
			
			$mail->Subject = 'Organization Invite';
			$message = "I would like to invite you to join our Organization.<br/>";
			$message .= "ORGANIZATION NAME: <b>".$org['name']."</b><br/>";
			$message .= "ORGANIZATION Description: <b>".$org['description']."</b><br/><br/>";
			$hash = $this->createInviteLink($membership_id,$org_id);
			
			$verify_link = App::baseUrl() . "/organization/verifynew/$hash?id=$org_id&accept=";
			$message .= "Accept Invitation: <a href='$verify_link". 1 ."'>Accept</a><br/>";
			$message .= "Decline Invitation: <a href='$verify_link". 0 ."'>Decline</a><br/>";
			
			$mail->Body = $message;
			return $mail->send();
		}
		return false;
	}
	
	private function createInviteLink( $membership_id, $org_id )
	{
		$acc = new AccountModel();
		$raw = "membershi_id_" .$membership_id . "_org_id_" .$org_id;
		
		return $acc->createUserHash($membership_id,self::HASH_TYPE_ORGINVITE,$membership_id);
		
	}
	public function processInvite( $email, $org_id )
	{
		
		$sth = $this->_db->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
		$sth->bindValue(1, $email, PDO::PARAM_STR);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$row = $sth->fetch();
			$user_id = $row['id'];
			
			if( !$this->isOrgMember($user_id,$org_id) ) {
				return $this->sendInvite($user_id,$org_id);
			}
			else {
				return self::USER_ALREADY_MEMBER;
			}
		}
		else {
			return self::USER_NOT_REGISTERED;
		}
	}
	
	public function isOrgOwner($org_id, $user_id=null){
		
		if(isset($user_id) && $user_id!=null) {
			$user_id = intval($user_id);
		}
		else {
			$user_id = App::User()->id;
		}
		
		$sth = $this->_db->prepare("SELECT id FROM organization WHERE user_id=? AND id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $org_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return true;
		}
		return false;
		
	}
	
	public function verify( $hash, $response,$org_id )
	{
		if( !$hash )
			return array( "status" => false, "message" => "Invalid verification!");
			
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=0");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, self::HASH_TYPE_ORGINVITE, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id']) ) {
			$org_link = RouteManager::createUrl('/organization/view/'.$org_id);
			
			$sth = $this->_db->prepare("SELECT status FROM organization_members WHERE id=? AND organization_id=?");
			$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
			$sth->bindValue(2, $org_id, PDO::PARAM_STR);
			$sth->execute();
			$status = $sth->fetch();
			
			if(is_array($status) && count($status)> 0) {
				if( 0 == intval( $status['status'] ) ) {
					
					if( $response == self::STATUS_ACTIVE ) {
						$new_response =  self::STATUS_ACTIVE;
					}
					else {
						$new_response = self::STATUS_DECLINED;
					}
					$sth = $this->_db->prepare("UPDATE organization_members SET status=? WHERE id=? AND organization_id=?");
					$sth->bindValue(1, $new_response, PDO::PARAM_INT);
					$sth->bindValue(2, $result['email_or_id'], PDO::PARAM_INT);
					$sth->bindValue(3, $org_id, PDO::PARAM_INT);
					$sth->execute();
					
					$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=? AND hash=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->bindValue(2, self::HASH_TYPE_ORGINVITE, PDO::PARAM_INT);
					$sth->bindValue(3, $hash, PDO::PARAM_STR);
					$sth->execute();
					
					if( $new_response == self::STATUS_DECLINED ) {
						return array( "status" => true, "message" => "Organization membership declined." );
					}
					else{
						return array( "status" => true, "message" => "Organization membership activated. You can now <a href=\"$org_link\">view the organization</a>." );
					}
				}
				else{
					return array( "status" => false, "message" => "Organization membership already activated. You can now <a href=\"$org_link\">view the organization</a>." );
				}
			}
			else {
				return array( "status" => false, "message" => "Invalid verification!");
			}
		}else{
			return array( "status" => false, "message" => "Invalid verification!");
		}
	}
	
	public function verifynew( $hash, $response,$org_id, $user_data )
	{
		if( !$hash )
			return array( "status" => false, "message" => "Invalid verification!");
			
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=0");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, self::HASH_TYPE_ORGINVITE, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id']) ) {
			$org_link = RouteManager::createUrl('/organization/view/'.$org_id);
			
			$sth = $this->_db->prepare("SELECT * FROM organization_members WHERE id=? AND organization_id=?");
			$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
			$sth->bindValue(2, $org_id, PDO::PARAM_STR);
			$sth->execute();
			$status = $sth->fetch();
			
			if(is_array($status) && count($status)> 0) {
				if( 0 == intval( $status['status'] ) ) {
					
					if( $response == self::STATUS_ACTIVE ) {
						$new_response =  self::STATUS_ACTIVE;
						//insert here the user data update
						
						$account = new AccountModel();
						$password= $account->prepare_password( $user_data['password'] );
						$first_name = $user_data['first_name'];
						$last_name = $user_data['last_name'];
						
						$sth = $this->_db->prepare("UPDATE users SET status=1,type=0,password=?,first_name=?,last_name=? WHERE id=?");
						$sth->bindValue(1, $password, PDO::PARAM_STR);
						$sth->bindValue(2, $first_name, PDO::PARAM_STR);
						$sth->bindValue(3, $last_name, PDO::PARAM_STR);
						$sth->bindValue(4, $status['user_id'], PDO::PARAM_STR);
						$sth->execute();
					}
					else {
						$new_response = self::STATUS_DECLINED;
					}
					$sth = $this->_db->prepare("UPDATE organization_members SET status=? WHERE id=? AND organization_id=?");
					$sth->bindValue(1, $new_response, PDO::PARAM_INT);
					$sth->bindValue(2, $result['email_or_id'], PDO::PARAM_INT);
					$sth->bindValue(3, $org_id, PDO::PARAM_INT);
					$sth->execute();
					
					$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=? AND hash=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->bindValue(2, self::HASH_TYPE_ORGINVITE, PDO::PARAM_INT);
					$sth->bindValue(3, $hash, PDO::PARAM_STR);
					$sth->execute();
					
								
					if( $new_response == self::STATUS_DECLINED ) {
						return array( "status" => true, "message" => "Organization membership declined." );
					}
					else{
						return array( "status" => true, "user_id" => $status['user_id'], "message" => "Organization membership activated. You can now <a href=\"$org_link\">view the organization</a>." );
					}
				}
				else{
					return array( "status" => false, "message" => "Organization membership already activated. You can now <a href=\"$org_link\">view the organization</a>." );
				}
			}
			else {
				return array( "status" => false, "message" => "Invalid verification!");
			}
		}
		else {
			return array( "status" => false, "message" => "Invalid verification!");
		}
	}
}