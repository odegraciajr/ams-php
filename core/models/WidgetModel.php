<?php

class WidgetModel extends Model
{
	/**
	 * This will be the backend class for getting various widget data for AMS, 
	 */
	public function getUserProjects($user_id=null)
	{
		$results = array("status" => false);
		
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
		
		if ($sth->rowCount() > 0) {
			$results = array("status" => true, "results" => $sth->fetchAll());
		}

		header('Content-Type: application/json');
		echo json_encode($results);
		die();
	}
	
	public function getUserOrganizations($user_id=null)
	{
		$results = array("status" => false);
		
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
		
		if ($sth->rowCount() > 0) {
			$results = array("status" => true, "results" => $sth->fetchAll());
		}
		
		header('Content-Type: application/json');
		echo json_encode($results);
		die();
	}
	
	public function getRecentComments($thread_id,$project_id)
	{
		$results = array("status" => false);
		
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
		
		if ($sth->rowCount() > 0) {
			$results = array("status" => true, "results" => $sth->fetchAll());
		}
		
		header('Content-Type: application/json');
		echo json_encode($results);
		die();
	}
	
	public function getUserWidgetSettings($user_id=null)
	{
		$results = array("status" => false);
		
		$sql = "SELECT value FROM user_meta WHERE user_id=? AND meta_key='widget_settings'";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->execute();
		

		if ($sth->rowCount() > 0) {
			$results = array("status" => true, "results" => $sth->fetch());
		}
		
		header('Content-Type: application/json');
		echo json_encode($results);
		die();
	}
	
	public function saveUserMeta($user_id,$meta_key,$value)
	{
		$sql = "INSERT INTO user_meta(user_id, meta_key, value) ";
		$sql .="VALUES(?,?,?)";
		
		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $meta_key, PDO::PARAM_STR);
		$sth->bindValue(2, $value, PDO::PARAM_STR);
		$sth->execute();
		
		$meta_id = $this->_db->lastInsertId();
		
		$results = array("status" => true, "meta_id" => $meta_id);
		
		header('Content-Type: application/json');
		echo json_encode($results);
		die();
	}
	
	public function saveWidgetSettings($settings=null,$tab=1,$tab_name=null,$widget_id=null,$user_id=null)
	{
	
		if(!$user_id) {
			$user_id = App::User()->id;
		}
		
		$sql = "SELECT widget_id FROM user_widget_settings WHERE user_id=? AND tab_order=?";

		$sth = $this->_db->prepare($sql);
		$sth->bindValue(1, $user_id, PDO::PARAM_INT);
		$sth->bindValue(2, $tab, PDO::PARAM_INT);
		$sth->execute();
		
		$settings = json_encode($settings, JSON_UNESCAPED_UNICODE);
		if($tab_name==null){
			$tab_name = "Tab ".$tab;
		}
		
		if ($sth->rowCount() > 0) {
			$data = $sth->fetch();
			
			$sql = "UPDATE user_widget_settings SET widget_settings=?,tab_name=? WHERE widget_id=?";
			
			$sth = $this->_db->prepare($sql);
			$sth->bindValue(1, $settings, PDO::PARAM_STR);
			$sth->bindValue(2, $tab_name, PDO::PARAM_STR);
			$sth->bindValue(3, $data['widget_id'], PDO::PARAM_INT);
			$sth->execute();
			
			$meta_id = $this->_db->lastInsertId();
			
			$results = array("status" => true, "id" => $meta_id);
		}
		else{
			$sql = "INSERT INTO user_widget_settings (user_id, widget_settings,tab_name,tab_order) VALUES(:user_id, :settings,:tab_name,:tab_order) ";
			
			$sth = $this->_db->prepare($sql);
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':settings', $settings, PDO::PARAM_STR);
			$sth->bindParam(':tab_name', $tab_name, PDO::PARAM_STR);
			$sth->bindParam(':tab_order', $tab, PDO::PARAM_INT);
			$sth->execute();
			
			$meta_id = $this->_db->lastInsertId();
			
			$results = array("status" => true, "id" => $meta_id);
		}

		
		return $results;
		
	}
	public function getWidgetSettings($settings=null,$user_id=null)
	{
		$results = array("status" => false);
		
		if(App::User()->id){
			$user_id = App::User()->id;
			
			$sql = "SELECT widget_id,widget_settings AS settings,tab_name,tab_order FROM user_widget_settings WHERE user_id=?";

			$sth = $this->_db->prepare($sql);
			$sth->bindValue(1, $user_id, PDO::PARAM_INT);
			$sth->execute();
			
			if ($sth->rowCount() > 0) {
				//json_decode($json, true);
				
				$data = $sth->fetchAll();
				
				$newdata = array();
				foreach($data as $k=>$v){

						$newdata[$k]['widget_id'] = $v['widget_id'];
						$newdata[$k]['settings'] = json_decode($v['settings'], true);
						$newdata[$k]['tab_name'] = $v['tab_name'];
						$newdata[$k]['tab_order'] = $v['tab_order'];
				}
				
				//var_dump($data);die;
				
				$results = array("status" => true, "userWidgets" => $newdata);
			}
			else{
				$results = array("status" => true, "widgets" => false);
			}
			
		}
		return $results;
	}

}