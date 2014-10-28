<?php
class UserIdentity
{
	public static $_user_id;
	
	public function __construct()
	{
		session_start();
		self::$_user_id = $_SESSION['user_id'];
		$this->_authenticate();

	}
	
	private function _authenticate() {
       
        if(self::$_user_id) {
            return true;
        }
		else {
            if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_hash'])) {
			
                if( password_verify($_COOKIE['user_id'], $_COOKIE['user_hash'] ) ) {
                    $_SESSION['user_id'] = $_COOKIE['user_id'];
					
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
		}

    }
	
	public static function _userInfo()
	{
		if( isset( self::$_user_id ) ) {
			$db = Db::init();
			$sth = $db->prepare("SELECT * FROM users WHERE id=?");
			$sth->bindValue(1, self::$_user_id, PDO::PARAM_INT);
			$sth->execute();
			$user = $sth->fetch(PDO::FETCH_OBJ);
			
			if( $sth->rowCount() > 0 ) {
			
			$user->isGuest = false;
			
			$user->full_name = $user->first_name . " " . $user->last_name;
			
			return $user;
			}
			else{
				$user = array( 'isGuest' => true );

				return (object) $user;
			}
		}
		else {
			$user = array( 'isGuest' => true );
			
			return (object) $user;
		}
	}
}
?>