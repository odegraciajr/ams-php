<?php

class ActivityModel extends Model
{
	/**
	 * 
	 */
	const INVALID_EMAIL		= 1;

	public function addActivity($project_id)
	{
		$func_trim_string = function($value) {
			if( !is_array($value) )
				return trim($value);
		};
		
		$assigned_users = null;
		$activity_predecessor = null;
		$estimate_duration = null;
		
		if( isset( $_POST['assigned_user'] ) && is_array($_POST['assigned_user']) && count($_POST['assigned_user'])> 0)
			$assigned_users = $_POST['assigned_user'];
		if( isset( $_POST['prereq_act'] ) && is_array($_POST['prereq_act']) && count($_POST['prereq_act'])> 0)
			$activity_predecessor = $_POST['prereq_act'];
		if( isset( $_POST['estimate_duration'] ) && is_array($_POST['estimate_duration']) && count($_POST['estimate_duration'])> 0)
			$estimate_duration = $_POST['estimate_duration'];
			
		$data = array_map($func_trim_string, $_POST);

		if( is_array( $data ) && count( $data ) > 0 ){

			$owner_id = App::User()->id;
			$name = $data['name'];
			$description = $data['description'];
			$project_id = $project_id;
			$comment = $data['comment'];
			$request_date = date("Y-m-d H:i:s", strtotime($data['request_date']));
			$due_date = date("Y-m-d H:i:s", strtotime($data['due_date']));
			$due_time = date("Y-m-d H:i:s", strtotime($data['due_time']));
			$start_date = date("Y-m-d H:i:s", strtotime($data['start_date']));
			$start_time = date("Y-m-d H:i:s", strtotime($data['start_time']));
			//$estimate_duration = date("Y-m-d H:i:s", strtotime($data['estimate_duration']));
			$estimate_duration_formated = $this->parseEstimateDuration($estimate_duration);
			$parent_activity = $data['parent_activity'];
			$priority = $data['priority'];
			$requestor = $data['requestor'];
			$status = $data['status'];
			$type_id = $data['type_id'];
			$wbs = $data['wbs'];


			$sql = "INSERT INTO activity(name, description,project_id,owner_id,type_id,parent_activity,requestor,request_date,estimate_duration,due_date,due_time,comment,priority,status,start_date,start_time,wbs) ";
			$sql .="VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

			$newActivity = $this->_db->prepare($sql);
			$newActivity->bindValue(1, $name, PDO::PARAM_STR);
			$newActivity->bindValue(2, $description, PDO::PARAM_STR);
			$newActivity->bindValue(3, $project_id, PDO::PARAM_INT);
			$newActivity->bindValue(4, $owner_id, PDO::PARAM_INT);
			$newActivity->bindValue(5, $type_id, PDO::PARAM_INT);
			$newActivity->bindValue(6, $parent_activity, PDO::PARAM_INT);
			$newActivity->bindValue(7, $requestor, PDO::PARAM_INT);
			$newActivity->bindValue(8, $request_date, PDO::PARAM_STR);
			$newActivity->bindValue(9, $estimate_duration_formated, PDO::PARAM_STR);
			$newActivity->bindValue(10, $due_date, PDO::PARAM_STR);
			$newActivity->bindValue(11, $due_time, PDO::PARAM_STR);
			$newActivity->bindValue(12, $comment, PDO::PARAM_STR);
			$newActivity->bindValue(13, $priority, PDO::PARAM_INT);
			$newActivity->bindValue(14, $status, PDO::PARAM_INT);
			
			$newActivity->bindValue(15, $start_date, PDO::PARAM_STR);
			$newActivity->bindValue(16, $start_time, PDO::PARAM_STR);
			$newActivity->bindValue(17, $wbs, PDO::PARAM_STR);
			$newActivity->execute();

			$activity_id = $this->_db->lastInsertId();
			
			if( $activity_id ){
				if( $assigned_users )
					$this->assignUsersToActivity($activity_id,$assigned_users);
				if( $activity_predecessor )
					$this->assignActivityPrerequisite($activity_id,$activity_predecessor);
			}
			
			return $activity_id;
		}
	}
	
	public function updateActivity($act_id)
	{
		$func_trim_string = function($value) {
			if( !is_array($value) )
				return trim($value);
		};
		
		$assigned_users = null;
		$activity_predecessor = null;
		$estimate_duration = null;
		
		if( isset( $_POST['assigned_user'] ) && is_array($_POST['assigned_user']) && count($_POST['assigned_user'])> 0)
			$assigned_users = $_POST['assigned_user'];
		if( isset( $_POST['prereq_act'] ) && is_array($_POST['prereq_act']) && count($_POST['prereq_act'])> 0)
			$activity_predecessor = $_POST['prereq_act'];
		if( isset( $_POST['estimate_duration'] ) && is_array($_POST['estimate_duration']) && count($_POST['estimate_duration'])> 0)
			$estimate_duration = $_POST['estimate_duration'];
			
		$data = array_map($func_trim_string, $_POST);

		if( is_array( $data ) && count( $data ) > 0 ){

			$owner_id = App::User()->id;
			$name = $data['name'];
			$description = $data['description'];
			//$project_id = $project_id;
			$comment = $data['comment'];
			$request_date = date("Y-m-d H:i:s", strtotime($data['request_date']));
			$due_date = date("Y-m-d H:i:s", strtotime($data['due_date']));
			$due_time = date("Y-m-d H:i:s", strtotime($data['due_time']));
			$start_date = date("Y-m-d H:i:s", strtotime($data['start_date']));
			$start_time = date("Y-m-d H:i:s", strtotime($data['start_time']));
			//$estimate_duration = date("Y-m-d H:i:s", strtotime($data['estimate_duration']));
			$estimate_duration_formated = $this->parseEstimateDuration($estimate_duration);
			$parent_activity = $data['parent_activity'];
			$priority = $data['priority'];
			$requestor = $data['requestor'];
			$status = $data['status'];
			$type_id = $data['type_id'];
			$wbs = $data['wbs'];


			
			$sql = "UPDATE activity SET name=?, description=?,wbs=?,owner_id=?,type_id=?,parent_activity=?,";
			$sql .="requestor=?,request_date=?,estimate_duration=?,due_date=?,due_time=?,";
			$sql .="comment=?,priority=?,status=?,start_date=?,start_time=? WHERE id=?";
			
			
			$updatedActivity = $this->_db->prepare($sql);
			$updatedActivity->bindValue(1, $name, PDO::PARAM_STR);
			$updatedActivity->bindValue(2, $description, PDO::PARAM_STR);
			$updatedActivity->bindValue(3, $wbs, PDO::PARAM_STR);
			$updatedActivity->bindValue(4, $owner_id, PDO::PARAM_INT);
			$updatedActivity->bindValue(5, $type_id, PDO::PARAM_INT);
			$updatedActivity->bindValue(6, $parent_activity, PDO::PARAM_INT);
			$updatedActivity->bindValue(7, $requestor, PDO::PARAM_INT);
			$updatedActivity->bindValue(8, $request_date, PDO::PARAM_STR);
			$updatedActivity->bindValue(9, $estimate_duration_formated, PDO::PARAM_STR);
			$updatedActivity->bindValue(10, $due_date, PDO::PARAM_STR);
			$updatedActivity->bindValue(11, $due_time, PDO::PARAM_STR);
			$updatedActivity->bindValue(12, $comment, PDO::PARAM_STR);
			$updatedActivity->bindValue(13, $priority, PDO::PARAM_INT);
			$updatedActivity->bindValue(14, $status, PDO::PARAM_INT);
			
			$updatedActivity->bindValue(15, $start_date, PDO::PARAM_STR);
			$updatedActivity->bindValue(16, $start_time, PDO::PARAM_STR);
			$updatedActivity->bindValue(17, $act_id, PDO::PARAM_INT);
			$updatedActivity->execute();

			if( $act_id ){
				$this->deleteAssignedUsers($act_id);
				$this->deletePrerequisiteActivity($act_id);
				
				if( $assigned_users )
					$this->assignUsersToActivity($act_id,$assigned_users);
				if( $activity_predecessor )
					$this->assignActivityPrerequisite($act_id,$activity_predecessor);
			}
			
			return $act_id;
		}
	}
	
	private function deleteAssignedUsers($act_id)
	{
		$stmt = $this->_db->prepare("DELETE FROM activity_assignment WHERE activity_id=:act_id");
		$stmt->bindValue(':act_id', $act_id, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	private function deletePrerequisiteActivity($act_id)
	{
		$stmt = $this->_db->prepare("DELETE FROM activity_predecessor WHERE main_activity=:act_id");
		$stmt->bindValue(':act_id', $act_id, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function getActivityTypes()
	{
		$sth = $this->_db->prepare("SELECT id,name FROM activity_type WHERE status=?");
		$sth->bindValue(1, 1, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}

	public function getProjectActivity($project_id)
	{
		$sql = "SELECT a.id,a.name,at.name AS type_name, CONCAT(u.first_name,' ',u.last_name) AS owner_name, ";
		$sql .= "CONCAT(u2.first_name,' ',u2.last_name) AS requestor_name, ";
		$sql .= "a.due_date, a.due_time, a.request_date , a.status, a.requestor,a.owner_id ";
		$sql .= "FROM activity AS a ";
		$sql .= "LEFT JOIN activity_type AS at ON a.type_id=at.id ";
		$sql .= "LEFT JOIN users AS u ON a.owner_id=u.id ";
		$sql .= "LEFT JOIN users AS u2 ON a.requestor=u2.id ";
		$sql .= "WHERE a.project_id = ? ORDER BY a.id DESC";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $project_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();

	}
	
	public function getActivity($id)
	{
		$sql = "SELECT a.id,a.description,a.estimate_duration,a.name,at.name AS type_name, CONCAT(u.first_name,' ',u.last_name) AS owner_name, ";
		$sql .= "CONCAT(u2.first_name,' ',u2.last_name) AS requestor_name, ";
		$sql .= "a.parent_activity, a2.name AS parent_name, a.comment, ";
		$sql .= "a.due_date, a.due_time, a.request_date , a.priority ,a.status, a.requestor,a.owner_id, ";
		$sql .= "a.start_date,a.start_time,a.wbs,a.type_id ";
		$sql .= "FROM activity AS a ";
		$sql .= "LEFT JOIN activity_type AS at ON a.type_id=at.id ";
		$sql .= "LEFT JOIN users AS u ON a.owner_id=u.id ";
		$sql .= "LEFT JOIN users AS u2 ON a.requestor=u2.id ";
		$sql .= "LEFT JOIN activity AS a2 ON a.parent_activity=a2.id ";
		$sql .= "WHERE a.id = ?";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function getPrerequisiteActivities($act_id)
	{
		$sql = "SELECT a.id,a.description,a.estimate_duration,a.name,at.name AS type_name, CONCAT(u.first_name,' ',u.last_name) AS owner_name, ";
		$sql .= "CONCAT(u2.first_name,' ',u2.last_name) AS requestor_name, ";
		$sql .= "a.parent_activity, ";
		$sql .= "a.due_date, a.due_time, a.request_date , a.status, a.requestor,a.owner_id ";
		$sql .= "FROM activity_predecessor AS ap ";
		$sql .= "LEFT JOIN activity AS a ON a.id=ap.predecessor_id ";
		$sql .= "LEFT JOIN activity_type AS at ON a.type_id=at.id ";
		$sql .= "LEFT JOIN users AS u ON a.owner_id=u.id ";
		$sql .= "LEFT JOIN users AS u2 ON a.requestor=u2.id ";
		$sql .= "WHERE ap.main_activity = ?";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $act_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	public function assignUsersToActivity($act_id,$users=array())
	{
		foreach($users as $id){
			$sql = "INSERT INTO activity_assignment(user_id, activity_id) VALUES(?,?)";
			$sth = $this->_db->prepare($sql);
			$sth->bindValue(1, $id, PDO::PARAM_INT);
			$sth->bindValue(2, $act_id, PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	public function assignActivityPrerequisite($act_id,$acts=array())
	{
		foreach($acts as $id){
			$sql = "INSERT INTO activity_predecessor(predecessor_id, main_activity) VALUES(?,?)";
			$sth = $this->_db->prepare($sql);
			$sth->bindValue(1, $id, PDO::PARAM_INT);
			$sth->bindValue(2, $act_id, PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	public function getAssignedUsers($act_id,$html=true,$none="None")
	{
		$sql = "SELECT aa.*,CONCAT(u.first_name,' ',u.last_name) AS full_name FROM activity_assignment AS aa LEFT JOIN users AS u ON aa.user_id=u.id WHERE aa.activity_id=?";
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $act_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			$users = $sth->fetchAll();
			
			if($html){
				$list = "<ul>";
				foreach($users as $user){
					$url = RouteManager::createUrl('/account/userprofile/'.$user['user_id']);
					$list .= '<li><a href="'.$url.'"><span class="item">'.$user['full_name'].'</a><span class="sep">,</span></li>';
				}
				$list .= "</ul>";
				
				return $list;
			}
			else{
				return $users;
			}
		}
		else{
			return $none;
		}
	}
	
	public function isActivityOwner($act_id,$user_id=null)
	{
		if(!$user_id) {
			$user_id = App::User()->id;
		}
		
		$sql = "SELECT (1) FROM activity WHERE owner_id=? AND id=?";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $act_id, PDO::PARAM_INT);
		$sth->execute();
		
		if ($sth->rowCount() > 0) {
			return true;
		}
		
		return false;
	}
	
	public function getActivityAssignment($act_id)
	{
		$sql  = "SELECT aa.user_id,CONCAT(u.first_name,' ',u.last_name) AS full_name FROM activity_assignment AS aa LEFT JOIN users AS u ON aa.user_id=u.id ";
		$sql .= "WHERE aa.activity_id=?";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $act_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function getActivityPredecessor($act_id)
	{
		$sql  = "SELECT ap.predecessor_id,p.name FROM activity_predecessor AS ap LEFT JOIN activity AS p ON ap.predecessor_id=p.id ";
		$sql .= "WHERE ap.main_activity=?";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $act_id, PDO::PARAM_INT);
		$sth->execute();
		
		return $sth->fetchAll();
	}
	
	public function getActivityStatus($index=null)
	{
	
		$status = [
			-1	=> 'Disabled',
			0	=>'Pending',
			1	=> 'Active',
			2	=> 'Completed'
		];
		if($index!==null)
			return $status[$index];
		else
			return $status;
	}
	
	public function getActivityPriority($index=null)
	{
		$priority = [
			1	=> 'Level 1',
			2	=> 'Level 2',
			3	=> 'Level 3',
			4	=> 'Level 4',
			5	=> 'Level 5'
		];
		
		if($index!==null)
			return $priority[$index];
		else
			return $priority;
	}
	
	public function parseEstimateDuration($duration=array(),$delimiter=":")
	{
		$value="";
		
		if(is_array($duration) && count($duration)>0){
			$value = implode($delimiter, $duration);
		}
		
		return $value;
	}
	
	public function estimateDurationToArr($duration=null,$delimiter=":")
	{
		$arr = array(
			'days'=>0,
			'hours'=>0,
			'mins'=>0
		);
		if($duration!==null){
			$durationArr = explode($delimiter, $duration);
			
			if( isset( $durationArr[0] ) && is_numeric( $durationArr[0] ) ){
				$arr['days'] = sprintf("%02s", $durationArr[0]);
			}
			else{
				$arr['days'] = 0;
			}
			
			if( isset( $durationArr[1] ) && is_numeric( $durationArr[1] ) ){
				$arr['hours'] = sprintf("%02s", $durationArr[1]);
			}
			else{
				$arr['hours'] = 0;
			}
			
			if( isset( $durationArr[2] ) && is_numeric( $durationArr[2] ) ){
				$arr['mins'] = sprintf("%02s", $durationArr[2]);
			}
			else{
				$arr['mins'] = 0;
			}
				
			
		}
		
		return $arr;
	}
}