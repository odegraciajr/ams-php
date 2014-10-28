<?php
class AppBase
{
	/**
	* @var array class map used by the autoloading mechanism.
	* The array keys are the class names and the array values are the corresponding class file paths.
	* @since 0.0.1
	*/
	private static	$_coreClasses=array();
	/**
	* @var array that hold all configs
	* @since 0.0.1
	*/
	private static 	$_config=array();
	
	private static 	$_userInfo=null;
	
		
	public static function startApp($configs=null)
	{
		self::$_config = include $configs;
		self::authenticate();
		self::$_userInfo = self::getDbUserInfo();
		self::createApplication('RouteManager');
	}
	
	public static function autoload($className)
	{
		$core_folders = array('utilities','models','controllers');
		
		foreach( $core_folders as $folder ){
			$classFile = self::getClassPath($className,$folder);
			
			if(is_file($classFile))
			{
				include($classFile);
			}
		}
	}
	
	public static function createApplication($class,$config=null)
	{
		return new $class($config);
	}
	
	public static function getConfig()
	{
		return self::$_config;
	}
	
	public static function config()
	{
		return (object) self::$_config;
	}
	
	private static function getDbUserInfo()
	{
		if( isset( $_SESSION['user_id'] ) ) {
			$sth = self::Db()->prepare("SELECT * FROM users WHERE id=?");
			$sth->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
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
	
	public static function User()
	{
		if( self::$_userInfo ) {
			return self::$_userInfo;
		}
		else {
			return self::getDbUserInfo();
		}
	}
	
	public static function Db()
	{
		return Db::init();
	}
	
	public static function baseUrl($scheme="http")
	{
		$baseUrl = isset(self::$_config['domainName']) ? str_replace(array( "http://","https://","//" ), "", self::$_config['domainName']) : $_SERVER['SERVER_NAME'];
		
		if(strpos($scheme, "https")===false) {
			return "http://" . $baseUrl;
		}
		else {
			return "https://" . $baseUrl;
		}
	}
	
	public static function sessionStart()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}
	
	public static function setSession($key,$value)
	{
		self::sessionStart();
		$_SESSION[$key] = $value;
	}
	
	public static function getSession($key)
	{
		self::sessionStart();
		if( isset( $_SESSION[$key] ) )
			return $_SESSION[$key];
		
		return false;
	}
	
	public static function Mail()
	{
		return new PHPMailer;
	}
	
	private static function authenticate()
	{
		if(self::getSession('user_id')) {
			return true;
		}
		else {
			if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_hash'])) {
				//cookie found, is it really someone from the
				if( password_verify($_COOKIE['user_id'], $_COOKIE['user_hash'] ) ) {
					self::setSession("user_id",$_COOKIE['user_id']);
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
	
	private static function getClassPath( $className, $folder )
	{
		return COREPATH.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$className. '.php';
	}
	
}

spl_autoload_register(array('AppBase','autoload'));
