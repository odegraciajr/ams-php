<?php

class ActivityModel extends Model
{
	/**
	 * 
	 */
	const INVALID_EMAIL		= 1;

	public function addActivity($project_id)
	{
		$data = array_map('trim', $_POST);

		if( is_array( $data ) && count( $data ) > 0 ){


			/*
				action_post	do_newactivity
				comment	Additional Comment
				description	Description
				due_date	1415752565502
				due_time	1415752574089
				estimate_duration	1415752582101
				name	Name
				parent_activity	1
				priority	1
				request_date	1415752558336
				requestor	2
				status	1
				type_id	1
			*/

			$owner_id = App::User()->id;
			$name = $data['name'];
			$description = $data['description'];
			$project_id = $project_id;
			$comment = $data['comment'];
			$request_date = date("Y-m-d H:i:s", strtotime($data['request_date']));
			$due_date = date("Y-m-d H:i:s", strtotime($data['due_date']));
			$due_time = date("Y-m-d H:i:s", strtotime($data['due_time']));
			$estimate_duration = date("Y-m-d H:i:s", strtotime($data['estimate_duration']));
			$parent_activity = 0;//$data['parent_activity'];
			$priority = $data['priority'];
			$requestor = $data['requestor'];
			$status = $data['status'];
			$type_id = $data['type_id'];


			$sql = "INSERT INTO activity(name, description,project_id,owner_id,type_id,parent_activity,requestor,request_date,estimate_duration,due_date,due_time,comment,priority,status) ";
			$sql .="VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

			$newActivity = $this->_db->prepare($sql);
			$newActivity->bindValue(1, $name, PDO::PARAM_STR);
			$newActivity->bindValue(2, $description, PDO::PARAM_STR);
			$newActivity->bindValue(3, $project_id, PDO::PARAM_INT);
			$newActivity->bindValue(4, $owner_id, PDO::PARAM_INT);
			$newActivity->bindValue(5, $type_id, PDO::PARAM_INT);
			$newActivity->bindValue(6, $parent_activity, PDO::PARAM_INT);
			$newActivity->bindValue(7, $requestor, PDO::PARAM_INT);
			$newActivity->bindValue(8, $request_date, PDO::PARAM_STR);
			$newActivity->bindValue(9, $estimate_duration, PDO::PARAM_STR);
			$newActivity->bindValue(10, $due_date, PDO::PARAM_STR);
			$newActivity->bindValue(11, $due_time, PDO::PARAM_STR);
			$newActivity->bindValue(12, $comment, PDO::PARAM_STR);
			$newActivity->bindValue(13, $priority, PDO::PARAM_INT);
			$newActivity->bindValue(14, $status, PDO::PARAM_INT);
			$newActivity->execute();

			return $this->_db->lastInsertId();
		}
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


}