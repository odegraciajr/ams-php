<?php

class AccountModel extends Model
{
	/**
	 * Error Handling
	 **/
	const INVALID_EMAIL		= -1;
	const INVALID_PASSWORD	= -2;
	const USER_NOT_VERIFIED	= -3;
	const LOGIN_SUCCESS		= 1;
	const HASH_TYPE_NEWUSER = 1;
	const HASH_TYPE_RESETPASSWORD = 4;
	const INVITE_TYPE_ORG	= 2;
	const INVITE_TYPE_PROJECT = 3;
	const STATUS_INVITED 	= 3;
	
	public function createNewUser(){
		$data = array_map('trim', $_POST);
		
		if( is_array( $data ) && count( $data ) > 0 ){
			
			$email = $data['email'];
			$password = $this->prepare_password( $data['password'] );
			$type = 1;
			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			$address = '';//$data['address'];
			$city = '';//$data['city'];
			$state = '';//$data['state'];
			$postal_code = '';//$data['postal_code'];
			$country = '';//$data['country'];
			$phone = '';//$data['phone'];
			$status = 0;
			
			
			$sql = "INSERT INTO users(email, password,type,first_name,last_name,address,city,state,postal_code,country,phone,date_created,status) ";
			$sql .="VALUES(?,?,?,?,?,?,?,?,?,?,?,NOW(),?)";
			
			$newUser = $this->_db->prepare($sql);
			$newUser->execute(array($email,$password,$type,$first_name,$last_name,$address,$city,$state,$postal_code,$country,$phone,$status));
			
			if( $newUser->rowCount() ) {
				$mail = App::Mail();
				
				$mail->From = 'noreply@datastreamsolutions.com';
				$mail->FromName = 'Mailer';
				$mail->addAddress($email, $first_name . " " . $last_name);

				$mail->isHTML(true);
				
				$hash = $this->createUserHash($email,self::HASH_TYPE_NEWUSER,$this->_db->lastInsertId());
				$verify_link = App::baseUrl() . "/account/verify/$hash";
				
				$mail->Subject = 'Account Verification';
				$mail->Body    = "Hi $first_name,<br/><br/>Thank you for registering!<br/>To finish your registration, there is one more quick step:<br/><br/><a href='$verify_link'>Click to verify your email address</a><br/><br/>Or you can paste this link to your browser<br/><br/>$verify_link";
				$mail->AltBody = "Hi $first_name\r\nThank you for registering!\r\nTo finish your registration, there is one more quick step:\r\n\r\nYou can paste this link to your browser\r\n$verify_link";
				
				$mail->send();
				
				return true;
			}
			return false;
		}
	}
	
	public function inviteNewUser($email, $type=2)
	{
		$sql = "INSERT INTO users(email,type,date_created,status) ";
		$sql .="VALUES(?,2,NOW(),?)";
		
		$invited = $this->_db->prepare($sql);
		$invited->bindValue(1, $email, PDO::PARAM_STR);
		$invited->bindValue(2, $type, PDO::PARAM_INT);
		$invited->execute();
		
		return $this->_db->lastInsertId();
		
	}
	
	public function createUserHash( $email_or_id, $hash_type, $salt )
	{
		$raw_hash = uniqid($salt.$hash_type, true);
		$hash = hash('sha256', $raw_hash);
		
		$sql = "INSERT INTO users_hashes(email_or_id,hash,type,date_created) ";
		$sql .="VALUES(?,?,?,NOW())";

		$newHash = $this->_db->prepare($sql);
		$newHash->bindValue(1, $email_or_id, PDO::PARAM_STR);
		$newHash->bindValue(2, $hash, PDO::PARAM_STR);
		$newHash->bindValue(3, $hash_type, PDO::PARAM_INT);
		$newHash->execute();
		
		return $hash;
		
	}	
	
	public function login( $email, $password, $remember=null ){


		$sth = $this->_db->prepare("SELECT id,password,status FROM users WHERE email=:email");
		$sth->execute(array(':email' => $email));
		$row = $sth->fetch();
		
		
		if ($sth->rowCount() > 0) {
			$hash = $row['password'];
			
			if( intval( $row['status'] ) == 1 ) {
			
				if( password_verify($password, $hash ) )
				{
					App::setSession("user_id",$row['id']);
					
					if($remember) {
						
						setcookie('user_id', $row['id'], time() + App::config()->loginCookieLife*24*60*60);
						setcookie('user_hash', $this->prepare_password( $row['id'] ), time() + App::config()->loginCookieLife*24*60*60);
					}
					else {
						//destroy any previously set cookie
						setcookie('user_id', '', time() - 1*24*60*60);
						setcookie('user_hash', '', time() - 1*24*60*60);
					}

					return self::LOGIN_SUCCESS;
				}
				else {
					return self::INVALID_PASSWORD;
				}
			}
			else {
				return self::USER_NOT_VERIFIED;
			}
			
		}
		else {
			return self::INVALID_EMAIL;
		}
	}
	
	public function verify( $hash, $hash_type )
	{
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=?");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, $hash_type, PDO::PARAM_INT);
		$sth->bindValue(3, 0, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id']) ) {
			$login = RouteManager::createUrl('/account/login');
			
			$sth = $this->_db->prepare("SELECT status FROM users WHERE email=?");
			$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
			$sth->execute();
			$status = $sth->fetch();
			
			if(is_array($status) && count($status)> 0) {
				if( 0 == intval( $status['status'] ) ) {
					$sth = $this->_db->prepare("UPDATE users SET status=1 WHERE email=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->execute();
					
					$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=? AND hash=?");
					$sth->bindValue(1, $result['email_or_id'], PDO::PARAM_STR);
					$sth->bindValue(2, $hash_type, PDO::PARAM_INT);
					$sth->bindValue(3, $hash, PDO::PARAM_STR);
					$sth->execute();
					
					return array( "status" => true, "email" => $result['email_or_id'], "message" => "Account activated. You can now <a href=\"$login\">Login</a>." );
				}
				else{
					return array( "status" => false, "message" => "Account already activated. You can now <a href=\"$login\">Login</a>." );
				}
			}
			else {
				return array( "status" => false, "message" => "Invalid verification!");
			}
			
			return array( "status" => true, "message" => "Account activated. You can now <a href=\"$login\">Login</a>." );
		}else{
			return array( "status" => false, "message" => "Invalid verification!");
		}
	}
	
	public function resetPasswordVerify( $hash, $hash_type )
	{
		$sth = $this->_db->prepare("SELECT email_or_id FROM users_hashes WHERE hash=? AND type=? AND status=?");
		$sth->bindValue(1, $hash, PDO::PARAM_STR);
		$sth->bindValue(2, $hash_type, PDO::PARAM_INT);
		$sth->bindValue(3, 0, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch();
		
		if( isset( $result['email_or_id'] ) ) {
			return array( "status" => true, "email" => $result['email_or_id'] );
		}
		else {
			return array( "status" => false, "message" => "Invalid reset password link!");
		}
	}
	
	public function resetPassword($email, $newpassword)
	{
		$sth = $this->_db->prepare("UPDATE users SET password=? WHERE email=?");
		$sth->bindValue(1, $this->prepare_password( $newpassword ), PDO::PARAM_STR);
		$sth->bindValue(2, $email, PDO::PARAM_STR);
		$sth->execute();
		
		$sth = $this->_db->prepare("UPDATE users_hashes SET status=1 WHERE email_or_id=? AND type=?");
		$sth->bindValue(1, $email, PDO::PARAM_STR);
		$sth->bindValue(2, self::HASH_TYPE_RESETPASSWORD, PDO::PARAM_INT);
		
		return $sth->execute();
	}
	
	public function sendResetPasswordEmail($email=null)
	{
		$userData = $this->getUserDataByEmail($email);
		
		if($userData) {
			$full_name = $userData['full_name'];
			$user_email = $userData['email'];
			
			$mail = App::Mail();

			$mail->From = 'noreply@datastreamsolutions.com';
			$mail->FromName = 'Mailer';
			$mail->addAddress($user_email,$full_name);

			$mail->isHTML(true);

			$hash = $this->createUserHash($user_email,self::HASH_TYPE_RESETPASSWORD,$user_email);
			$verify_link = App::baseUrl() . "/account/resetpassword/$hash";

			$mail->Subject = 'Password Reset';
			$mail->Body    = "Hi $full_name,<br/><br/>You requested to reset your password from our system.<br/><br/><a href='$verify_link'>Click here to begin the process.</a>";

			$mail->send();
			
			return true;
		}
		else {
			return false;
		}

		//var_dump($this->getUserDataByEmail($email));
	}
	public function searchUserByKeyword( $keyword )
	{
		$keyword = '%'.$keyword.'%';
		
		$sth = $this->_db->prepare("SELECT id, CONCAT( first_name,' ', last_name ) as full_name, email FROM users WHERE email LIKE :keyone OR CONCAT( first_name,' ', last_name ) LIKE  :keytwo AND status=1 ORDER BY first_name");
		$sth->bindParam(':keyone', $keyword, PDO::PARAM_STR);
		$sth->bindParam(':keytwo', $keyword, PDO::PARAM_STR);
		$sth->execute();
		//$sth->debugDumpParams();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
		
	public function emailIsUnique($email)
	{
		$sth = $this->_db->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
		$sth->bindValue(1, $email, PDO::PARAM_STR);
		$sth->execute();
		return $sth->fetch();
	}
	
	public function getUserData($user_id=null){
		if( !$user_id ) {
			if( App::User()->id ) {
				$user_id = App::User()->id;
			}
		}
		if( $user_id ) {
			$sth = $this->_db->prepare("SELECT *,CONCAT(first_name,' ', last_name) as full_name FROM users WHERE id=:id");
			$sth->execute(array(':id' => $user_id));
			return $sth->fetch();
		}
		
		return 0;
	}
	
	public function getUserDataByEmail($email=null){
	
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$sth = $this->_db->prepare("SELECT *,CONCAT(first_name,' ', last_name) as full_name FROM users WHERE email=:email");
			$sth->execute(array(':email' => $email));
			return $sth->fetch();
		}

		return 0;
	}
		
	public function prepare_password( $password )
	{
		$options = [
			'salt' => $this->generate_salt_iv(22),
			'cost' => 12
		];
		
		return password_hash($password, PASSWORD_DEFAULT, $options);
	}
	
	protected function generate_salt_iv( $length = 22 ){
		return mcrypt_create_iv( $length, MCRYPT_DEV_RANDOM );
	}
	
	public static function destroy(){
		if(isset($_SESSION['user_id']))
			unset($_SESSION['user_id']);
			
		session_destroy();
		setcookie('user_id', '', time() - 1*24*60*60);
		setcookie('user_hash', '', time() - 1*24*60*60);
	}
}