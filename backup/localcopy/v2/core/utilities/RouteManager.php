<?php
/**
 * This class handle all url, url direct, params.
 */
 
class RouteManager extends 
{

	public static 	$_url_scheme;
	
	public function __construct($config=null)
	{
		self::$_url_scheme = isset($_GET['load']) ? trim($_GET['load']) : null;
		
		$controller = App::config()->defaultController;
		$action = "index";
		$query = null;
		
		if ( !empty( self::$_url_scheme ) )
		{
			$params = array();
			$params = explode("/", self::$_url_scheme );

			$controller = ucwords($params[0]);

			if (isset($params[1]) && !empty($params[1]))
			{
				$action = $params[1];
			}

			if (isset($params[2]) && !empty($params[2]))
			{
				$query = $params[2];
			}
		}
		
		try {
		
			$modelName = $controller;
			$controllerName = ucwords( $controller ) . self::getControllerSuffix();
			$load = new $controllerName($modelName, $action);
			
		} catch (Exception $e) {
			echo $e->getMessage(), "\n";
		}
		
		if (method_exists($load, $action)){
			$load->{$action}($query);
		}else{
			die('Invalid method. Please check the URL.');
		}
	}
	
	public static function redirect( $link )
	{
		header("Location: $link");
		exit;
	}
	/**
	 * TODO: fix the url routing depending on controller.
	 */
	public static function createUrl($route,$params=array(),$ampersand='&')
	{
		$route = preg_replace('#/+#','/', "/" . $route);
		
		return App::baseUrl() . $route;
	}
	
	private static function getControllerSuffix()
	{
		return 'Controller';
	}
}