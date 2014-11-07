<?php

class ProjectModel extends Model
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
	const HASH_TYPE_PROJGINVITE = 3;
	
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
	public function createProject( $owner_id=null, $name=null, $desc=null )
	{
		
		$data = array_map('trim', $_POST);
		
		if( is_array( $data ) && count( $data ) > 0 ) {
			
			$owner_id = App::User()->id;
			$name = $data['name'];
			$description = $data['description'];
			
			$sql = "INSERT INTO project(name, description,user_id,date_created) ";
			$sql .="VALUES(?,?,?,NOW())";
			
			$newproj = $this->_db->prepare($sql);
			$newproj->bindValue(1, $name, PDO::PARAM_STR);
			$newproj->bindValue(2, $description, PDO::PARAM_STR);
			$newproj->bindValue(3, $owner_id, PDO::PARAM_INT);
			$newproj->execute();
			
			$proj_id = $this->_db->lastInsertId();
			
			if( $newproj->rowCount() ) {
				//Add the owner as memebr also
				$this->createProjectMember($proj_id, $owner_id, self::ROLE_ADMIN, self::STATUS_ACTIVE );
				
				$mail = App::Mail();
				
				$mail->From = 'noreply@datastreamsolutions.com';
				$mail->FromName = 'Mailer';
				$mail->addAddress(App::User()->email, App::User()->full_name);

				$mail->isHTML(true);
				$profile_link = App::baseUrl() . "/account";
				$first_name = App::User()->first_name;
				$mail->Subject = 'Project Created';
				$mail->Body    = "Hi $first_name,<br/><br/>Project: <b>$name</b> has been created. You can view your project settings at $profile_link";
				$mail->AltBody = "Hi $first_name,\r\nProject: $name has been created. You can view your project settings at $profile_link";
				
				$mail->send();
				
			}
			
			return $proj_id;
		}
		return false;
	}
	
	public function createProjectMember( $proj_id, $user_id, $role_id=1, $status=0 )
	{
		$sql = "INSERT INTO project_members(project_id,user_id,role_id,status,date_joined) ";
		$sql .="VALUES(?,?,?,?,NOW())";
		
		$newprojMember = $this->_db->prepare($sql);
		$newprojMember->bindValue(1, $proj_id, PDO::PARAM_INT);
		$newprojMember->bindValue(2, $user_id, PDO::PARAM_INT);
		$newprojMember->bindValue(3, $role_id, PDO::PARAM_INT);
		$newprojMember->bindValue(4, $status, PDO::PARAM_INT);
		$newprojMember->execute();
		
		return $this->_db->lastInsertId();
	}
	
	public function getUserProjects($user_id=null)
	{
		if(!$user_id) {
			$user_id = App::User()->id;
		}
		
		$sql = "SELECT o.id,o.name,o.description,om.role_id,om.date_joined ";
		$sql .= "FROM project_members AS om ";
		$sql .= "INNER JOIN project AS o ";
		$sql .= "ON om.project_id=o.id ";
		$sql .= "WHERE om.user_id=? ";
		$sql .= "ORDER BY om.role_id DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function getProjectInfo($id)
	{
		$sql = "SELECT * FROM project WHERE id=?";
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return $sth->fetch();
		}
		return false;
	}
	
	public function getProjectMembers($proj_id)
	{
		if(!$proj_id)
			return false;
		
		$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,o.id,o.name,om.role_id,om.date_joined,om.status,om.user_id ";
		$sql .= "FROM project_members AS om ";
		$sql .= "INNER JOIN project AS o ";
		$sql .= "ON om.project_id=o.id ";
		$sql .= "INNER JOIN users AS u ON om.user_id=u.id ";
		$sql .= "WHERE om.project_id=? ";
		$sql .= "ORDER BY om.role_id DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $proj_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function getAllProjectUsersForInvite($user_id=null){

		if($user_id===null)
			$user_id = App::User()->id;

		$validUsers = [];

		$sth = $this->_db->prepare("SELECT organization_id as g_id FROM organization_members WHERE user_id=?");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		$org_ids = $sth->fetchAll();

		$organization_ids = [];

		if( is_array($org_ids) && count($org_ids)>0){
			foreach($org_ids as $id)
				$organization_ids[] = $id['g_id'];
		}
		$in_ids = trim( implode(",", $organization_ids), ",");

		if($in_ids) {
			$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,u.id ";
			$sql .= "FROM organization_members AS om ";
			$sql .= "INNER JOIN users AS u ON om.user_id=u.id ";
			$sql .= "WHERE u.status<>0 AND om.organization_id IN(?) ";
			$sql .= "ORDER BY u.first_name DESC";


			$sth = $this->_db->prepare($sql);
			$sth->bindValue(1, $in_ids, PDO::PARAM_INT);
			$sth->execute();

			$organization_ids = $sth->fetchAll();
		}
		
		
		return $organization_ids;

	}
	
	public function isProjectMember($user_id, $proj_id)
	{
		$sth = $this->_db->prepare("SELECT project_id FROM project_members WHERE user_id=? AND project_id=?");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $proj_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return true;
		}
		return false;		
	}
	
	public function getProjectMemberRole($user_id, $proj_id)
	{
		$sth = $this->_db->prepare("SELECT role_id FROM project_members WHERE user_id=? AND project_id=?");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $proj_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$result = $sth->fetch();
			return intval($result['role_id']);
		}
		return 0;		
	}
	
	private function sendInvite($user_id,$proj_id)
	{
		$sth = $this->_db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$membership_id = $this->createProjectMember($proj_id, $user_id, self::ROLE_MEMBER, self::STATUS_PENDING );
			$row = $sth->fetch();
			$prog = $this->getProjectInfo($proj_id);
			$mail = App::Mail();

			$mail->From = 'noreply@datastreamsolutions.com';
			$mail->FromName = 'Mailer';
			$mail->addAddress($row['email'], $row['first_name'] ." ". $row['last_name']);

			$mail->isHTML(true);
			
			$first_name = $row['first_name'];
			
			$mail->Subject = 'Project Invite';
			$message = "Hi $first_name,<br/><br/>I would like to invite you to join us.<br/>";
			$message .= "Project name: <b>".$prog['name']."</b><br/>";
			$message .= "Project Description: <b>".$prog['description']."</b><br/><br/>";
			$hash = $this->createInviteLink($membership_id,$proj_id);
			
			$verify_link = App::baseUrl() . "/project/verify/$hash?id=$proj_id&accept=";
			$message .= "Accept Invitation: <a href='$verify_link". 1 ."'>Accept</a><br/>";
			$message .= "Decline Invitation: <a href='$verify_link". 0 ."'>Decline</a><br/>";
			
			$mail->Body = $message;
			return $mail->send();
		}
		return false;
	}
	
	public function sendInviteNotRegistered($user_id,$proj_id)
	{
		$sth = $this->_db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$membership_id = $this->createProjectMember($proj_id, $user_id, self::ROLE_MEMBER, self::STATUS_PENDING );
			$row = $sth->fetch();
			$proj = $this->getProjectInfo($proj_id);
			$mail = App::Mail();

			$mail->From = 'noreply@datastreamsolutions.com';
			$mail->FromName = 'Mailer';
			$mail->addAddress($row['email'], 'New User' );

			$mail->isHTML(true);
			
			$first_name = $row['first_name'];
			
			$mail->Subject = 'Project Invite';
			$message = "I would like to invite you to join our project.<br/>";
			$message .= "Project NAME: <b>".$proj['name']."</b><br/>";
			$message .= "Project Description: <b>".$proj['description']."</b><br/><br/>";
			$hash = $this->createInviteLink($membership_id,$proj_id);
			
			$verify_link = App::baseUrl() . "/project/verifynew/$hash?id=$proj_id&accept=";
			$message .= "Accept Invitation: <a href='$verify_link". 1 ."'>Accept</a><br/>";
			$message .= "Decline Invitation: <a href='$verify_link". 0 ."'>Decline</a><br/>";
			
			$mail->Body = $message;
			return $mail->send();
		}
		return false;
	}
	
	private function createInviteLink( $membership_id, $proj_id )
	{
		$acc = new AccountModel();
		$raw = "membershi_id_" .$membership_id . "_proj_id_" .$proj_id;
		
		return $acc->createUserHash($membership_id,self::HASH_TYPE_PROJGINVITE,$membership_id);
		
	}
	public function processInvite( $email, $proj_id )
	{
		
		$sth = $this->_db->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
		$sth->bindValue(1, $email, PDO::PARAM_STR);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$row = $sth->fetch();
			$user_id = $row['id'];
			
			if( !$this->isProjectMember($user_id,$proj_id) ) {
				return $this->sendInvite($user_id,$proj_id);
			}
			else {
				return self::USER_ALREADY_MEMBER;
			}
		}
		else {
			return self::USER_NOT_REGISTERED;
		}
	}
	
	public function isProjectOwner($proj_id, $user_id=null){
		
		if(isset($user_id) && $user_id!=null) {
			$user_id = intval($user_id);
		}
		else {
			$user_id = App::User()->id;
		}
		
		$sth = $this->_db->prepare("SELECT id FROM project WHERE user_id=? AND id=? LIMIT 1");
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $proj_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return true;
		}
		return false;
		
	}
	
	public function verify( $hash, $response,$proj_id )
	{
		if( !$hash )
			return array( "status" => false, "message" => "Invalid verification!");
			
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=0");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, self::HASH_TYPE_PROJGINVITE, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id']) ) {
			$proj_link = RouteManager::createUrl('/project/view/'.$proj_id);
			
			$sth = $this->_db->prepare("SELECT status FROM project_members WHERE id=? AND project_id=?");
			$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
			$sth->bindValue(2, $proj_id, PDO::PARAM_STR);
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
					$sth = $this->_db->prepare("UPDATE project_members SET status=? WHERE id=? AND project_id=?");
					$sth->bindValue(1, $new_response, PDO::PARAM_INT);
					$sth->bindValue(2, $result['email_or_id'], PDO::PARAM_INT);
					$sth->bindValue(3, $proj_id, PDO::PARAM_INT);
					$sth->execute();
					
					$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=? AND hash=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->bindValue(2, self::HASH_TYPE_PROJGINVITE, PDO::PARAM_INT);
					$sth->bindValue(3, $hash, PDO::PARAM_STR);
					$sth->execute();
					
					if( $new_response == self::STATUS_DECLINED ) {
						return array( "status" => true, "message" => "Project membership declined." );
					}
					else{
						return array( "status" => true, "message" => "Project membership activated. You can now <a href=\"$proj_link\">view the project</a>." );
					}
				}
				else{
					return array( "status" => false, "message" => "Project membership already activated. You can now <a href=\"$proj_link\">view the project</a>." );
				}
			}
			else {
				return array( "status" => false, "message" => "Invalid verification!");
			}
		}else{
			return array( "status" => false, "message" => "Invalid verification!");
		}
	}
	
	public function verifynew( $hash, $response,$proj_id, $user_data )
	{
		if( !$hash )
			return array( "status" => false, "message" => "Invalid verification!");
			
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=0");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, self::HASH_TYPE_PROJGINVITE, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id']) ) {
			$proj_link = RouteManager::createUrl('/project/view/'.$proj_id);
			
			$sth = $this->_db->prepare("SELECT * FROM project_members WHERE id=? AND project_id=?");
			$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
			$sth->bindValue(2, $proj_id, PDO::PARAM_STR);
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
					$sth = $this->_db->prepare("UPDATE project_members SET status=? WHERE id=? AND project_id=?");
					$sth->bindValue(1, $new_response, PDO::PARAM_INT);
					$sth->bindValue(2, $result['email_or_id'], PDO::PARAM_INT);
					$sth->bindValue(3, $proj_id, PDO::PARAM_INT);
					$sth->execute();
					
					$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=? AND hash=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->bindValue(2, self::HASH_TYPE_PROJGINVITE, PDO::PARAM_INT);
					$sth->bindValue(3, $hash, PDO::PARAM_STR);
					$sth->execute();
					
								
					if( $new_response == self::STATUS_DECLINED ) {
						return array( "status" => true, "message" => "Project membership declined." );
					}
					else{
						return array( "status" => true, "user_id" => $status['user_id'], "message" => "Project membership activated. You can now <a href=\"$proj_link\">view the project</a>." );
					}
				}
				else{
					return array( "status" => false, "message" => "Project membership already activated. You can now <a href=\"$proj_link\">view the project</a>." );
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
	
	public function createThread($proj_id, $subject, $message)
	{
		$user_id = App::User()->id;
		$item_id = $proj_id;
		$message_type = 1;
		$subject = $subject;
		$message = $message;
		
		$sql = "INSERT INTO message_thread(user_id,item_id,message_type,subject,message,date_created) ";
		$sql .="VALUES(?,?,?,?,?,NOW())";
		
		$newThread = $this->_db->prepare($sql);
		$newThread->bindValue(1, $user_id, PDO::PARAM_INT);
		$newThread->bindValue(2, $item_id, PDO::PARAM_INT);
		$newThread->bindValue(3, $message_type, PDO::PARAM_INT);
		$newThread->bindValue(4, $subject, PDO::PARAM_STR);
		$newThread->bindValue(5, $message, PDO::PARAM_STR);
		$newThread->execute();
		
		return $this->_db->lastInsertId();
	}
	public function getThreads($item_id)
	{
		//$sql = "SELECT * FROM message_thread AS mt ";
		//$sql .= "WHERE mt.item_id=? AND status=1 ORDER BY date_created DESC";
		
		$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,mt.* ";
		$sql .= "FROM message_thread AS mt ";
		$sql .= "INNER JOIN users AS u ON mt.user_id=u.id ";
		$sql .= "WHERE mt.item_id=? AND mt.status=1 ";
		$sql .= "ORDER BY mt.date_created DESC";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $item_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function getThread($thread_id)
	{
		//$sql = "SELECT * FROM message_thread AS mt ";
		//$sql .= "WHERE mt.item_id=? AND status=1 ORDER BY date_created DESC";
		
		$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,mt.* ";
		$sql .= "FROM message_thread AS mt ";
		$sql .= "INNER JOIN users AS u ON mt.user_id=u.id ";
		$sql .= "WHERE mt.id=? AND mt.status=1 ";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $thread_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function addComment($thread_id,$comment_text)
	{
		$user_id = App::User()->id;
		$comment_text = strip_tags($comment_text, '<p><a><b><code>');
		$sql = "INSERT INTO messages(user_id,thread_id,message_text,date_created) ";
		$sql .="VALUES(?,?,?,NOW())";
		
		$newThread = $this->_db->prepare($sql);
		$newThread->bindValue(1, $user_id, PDO::PARAM_INT);
		$newThread->bindValue(2, $thread_id, PDO::PARAM_INT);
		$newThread->bindValue(3, $comment_text, PDO::PARAM_STR);
		$newThread->execute();
		
		return $this->_db->lastInsertId();
	}
	
	public function getComments($thread_id,$project_id)
	{
		$sql = "SELECT CONCAT(u.first_name,' ',u.last_name) AS full_name,u.email,pm.role_id, mt.* ";
		$sql .= "FROM messages AS mt ";
		$sql .= "INNER JOIN users AS u ON mt.user_id=u.id ";
		$sql .= "INNER JOIN project_members AS pm ON mt.user_id=pm.user_id AND pm.project_id=? ";
		$sql .= "WHERE mt.thread_id=? AND mt.status=1 ";
		$sql .= "ORDER BY mt.date_created DESC";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $project_id, PDO::PARAM_INT);
		$sth->bindValue(2, $thread_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	public function getCommentCount($thread_id)
	{
		$sql = "SELECT COUNT(1) AS count FROM messages WHERE thread_id=? AND status=1";
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $thread_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch();
	}
}