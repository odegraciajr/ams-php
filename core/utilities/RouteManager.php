<?php
/**
 * This class handle all url, url direct, params.
 */
 
class RouteManager extends CoreRouter
{

	protected $controller = '';
	protected $action = 'indexAction';
	
	public function __construct($config=null)
	{
		$this->controller =App::formatControllerName(App::config()->defaultController);
		
	
		$this->addRoutes(array(
			array('GET|POST', '/', 'Controller@Index'),
			//array('POST', '/ajax/[a:controller]/[a:ajaxaction]', 'Ajax@CAction'),//ajaxcaller
			//array('POST', '/ajax/[a:controller]/[a:ajaxaction]/', 'Ajax@CAction'),//ajaxcaller
			array('GET|POST', '/index.php', 'Controller@Index'),
			array('GET|POST', '/index.php/', 'Controller@Index'),
			array('GET|POST', '/[a:controller]/', 'Controller@Index'),
			array('GET|POST', '/[a:controller]', 'Controller@Index'),
			array('GET|POST', '/[a:controller]/[a:action]', 'Controller@Action'),
			array('GET|POST', '/[a:controller]/[a:action]/', 'Controller@Action'),
			array('GET|POST', '/[a:controller]/[a:action]/[i:pid]', 'Controller@Action#pid'),
			array('GET|POST', '/[a:controller]/[a:action]/[i:pid]/', 'Controller@Action#pid'),
			array('GET|POST', '/[a:controller]/[a:action]/[a:pstring]', 'Controller@Action#pstring'),
			array('GET|POST', '/[a:controller]/[a:action]/[a:pstring]/', 'Controller@Action#pstring'),
			array('GET|POST', '/[organization|project:controller]/[view:action]/[i:pid]/[projects|newactivity|activity|newthread|messages|members|invite:subaction]', 'Organization@View#Id#Subaction'),
			array('GET|POST', '/[organization|project:controller]/[view:action]/[i:pid]/[projects|newactivity|activity|newthread|messages|members|invite:subaction]/', 'Organization@View#Id#Subaction'),
			array('GET|POST', '/[a:controller]/[a:action]/[i:pid]/[i:pid2]', 'Controller@Action#pid#pid2'),
			array('GET|POST', '/[a:controller]/[a:action]/[i:pid]/[i:pid2]/', 'Controller@Action#pid#pid2'),
		));
				
		if( is_array( App::config()->routes ) && count(App::config()->routes)>0 )
			$this->addRoutes(App::config()->routes);
		
		$match = $this->match();
		
		if( $match ) {
			//var_dump($match);die();
			if( isset( $match['params']['controller'] ) )
				$this->controller = ucwords( strtolower( $match['params']['controller'] ) ) . 'Controller';
			
			if( isset( $match['params']['action'] ) )
				$this->action = ucwords( strtolower( $match['params']['action'] ) ) . 'Action';
				
			if( isset( $match['params']['ajaxaction'] ) )
				$this->ajaxaction = ucwords( strtolower( $match['params']['ajaxaction'] ) ) . 'Ajax';
				
			if( isset( $match['params']['pid'] ) )
				$pid = intval( $match['params']['pid'] );
			
			if( isset( $match['params']['pid2'] ) )
				$pid2 = intval( $match['params']['pid2'] );
			
			if( isset( $match['params']['pstring'] ) )
				$pstring = strval( $match['params']['pstring'] );
						
			App::setController($this->controller);
			
			switch ($match['target']) {
			
				case 'Controller@Index':
				case 'Controller@Action':
					if( is_callable( array($this->controller, $this->action) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->action))
							$load->{$this->action}();
					}
					else {
						self::set404();
					}
				break;
				case 'Controller@Action#pid':
					if( is_callable( array($this->controller, $this->action) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->action))
							$load->{$this->action}($pid);
					}
					else {
						self::set404();
					}
				break;
				case 'Organization@View#Id#Subaction':
					if( isset( $match['params']['subaction'] ) )
						$subaction = strval( $match['params']['subaction'] );
						
					if( is_callable( array($this->controller, $this->action) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->action))
							$load->{$this->action}($pid,$subaction);
					}
					else {
						self::set404();
					}
				break;
				case 'Controller@Action#pid#pid2':
											
					if( is_callable( array($this->controller, $this->action) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->action))
							$load->{$this->action}($pid,$pid2);
					}
					else {
						self::set404();
					}
				break;
				case 'Controller@Action#pstring':
					if( is_callable( array($this->controller, $this->action) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->action))
							$load->{$this->action}($pstring);
					}
					else {
						self::set404();
					}
				break;
				
				case 'Ajax@CAction':
											
					if( is_callable( array($this->controller, $this->ajaxaction) ) ) {
						$load = new $this->controller();
						if($load->beforeAction($this->ajaxaction))
							$load->{$this->ajaxaction}();
					}
					else {
						self::set404();
					}
				break;
			}
			
			
		}
		else {
			$this->set404();
		}
	}
	/**
	 * TODO: Make this dynamic
	 */
	public static function set404($customHandler=false)
	{
		header("HTTP/1.0 404 Not Found");
		
		if($customHandler) {
			$route = explode("/", $customHandler);
			
			$controller = App::formatControllerName($route[0]);
			$action = App::formatActionName($route[1]);
			
			if( is_callable( array($controller, $action) ) ) {
				App::setController($controller);
				$load = new $controller();
				$load->{$action}();
			}
			else {
				self::set404(App::config()->errorHandler);
			}
		}
		else {
			self::set404(App::config()->errorHandler);
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
	public static function createUrl($route,$params=null,$ampersand='&')
	{
		$route = preg_replace('#/+#','/', "/" . $route);
		
		$myparams = "";
		if( is_array($params) && count($params)>0 ) {
			$myparams = "?" . http_build_query($params);
		}
			
		return App::baseUrl() . $route . $myparams;
	}
	
	private static function getControllerSuffix()
	{
		return 'Controller';
	}
	
	public static function getUrlReferrer()
	{
		if( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$http_referrer = strtolower($_SERVER['HTTP_REFERER']);
			$referrer = $_SERVER['HTTP_REFERER'];
			
			$internal = strpos($http_referrer, strtolower(App::config()->domainName));
			$login = strpos($http_referrer, '/login');
			$logout = strpos($http_referrer, '/logout');
			$register = strpos($http_referrer, '/register');
			
			if ($internal === false) {
				return null;
			}
			
			if( $login === false && $logout === false && $register === false ) {
				return $referrer;
			}
			else {
				return null;
			}
			
		}
		else {
			return null;
		}
	}
}