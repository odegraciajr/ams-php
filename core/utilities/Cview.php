<?php

class Cview
{
	protected $viewFile;
	protected $_scripts;
	protected $_styles;
	private $_pageTitle;
	
	public function run($actionID){
		$this->beforeAction($actionID);
	}

	protected function beforeRender($view)
	{
		return true;
	}

	protected function beforeAction($action)
	{
		return true;
	}
	
	public function getLayoutFile($layoutName)
	{
		if($layoutName===false)
			return false;
		if( $layoutFile = COREPATH .DIRECTORY_SEPARATOR.'layouts'.DIRECTORY_SEPARATOR. $layoutName.'.php' )
			return $layoutFile;
		
	}
	
	public function getViewFile($view)
	{
		if($view===false)
			return false;
		$_viewFile = COREPATH .DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR. App::getController() .DIRECTORY_SEPARATOR. $view . '.tpl';
		
		if(is_file($_viewFile))
			$this->viewFile = $_viewFile;
		else
			return false;
	}
	public function add_script($url,$footer=false,$ver=null)
  	{
  		$new_script = null;
		$position = 'head';

  		if( $footer===true )
			$position = 'footer';

		if( $ver )
			$url = '?ver=' . $ver;

  		$this->_scripts[$position][] = $url;
  	}

  	public function get_scripts($position='head')
  	{
  		if(!isset($this->_scripts[$position]))
  			return;

  		$script_part = $this->_scripts[$position];

  		if( is_array($script_part) && count($script_part)>0){
  			foreach($script_part as $url){
  				echo '<script src="'.$url.'"></script>' . "\r\n";
  			}
  		}
  	}

  	public function add_style($url,$ver=null)
  	{
  		$new_script = null;

		if( $ver )
			$url = '?ver=' . $ver;

  		$this->_styles[] = $url;
  	}

  	public function get_styles()
  	{

  		$style_part = $this->_styles;

  		if( is_array($style_part) && count($style_part)>0){
  			foreach($style_part as $url){
  				echo '<link href="'.$url.'" rel="stylesheet">' . "\r\n";
  			}
  		}
  	}

	public function render($view,$data=null,$return=false)
	{
		if ( $this->getViewFile($view) ){
			throw new Exception("View " . $view . " doesn't exist.");
		}
		
		if(is_array($data))
			extract($data,EXTR_PREFIX_SAME,'data');
		else
			$data=$data;
		ob_start();
		require($this->getLayoutFile($this->layout));
		
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
		
	}

	public function renderEnd($view,$data=null,$return=false)
	{
		if ( $this->getViewFile($view) ){
			throw new Exception("View " . $view . " doesn't exist.");
		}
		
		if(is_array($data))
			extract($data,EXTR_PREFIX_SAME,'data');
		else
			$data=$data;
		ob_start();
		require($this->getLayoutFile($this->layout));
		
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
		exit;		
	}
	
	public function loadModel($model=null)
	{
		if( $model !== null ) {
			$newModel = new $model();
			return $newModel;
		}
		else {
			$trace = debug_backtrace();
			
			
			if( isset( $trace[0]['object'] ) ) {
				$trace_class = get_class($trace[0]['object']);
				
				$newClassName = str_replace("Controller","Model",$trace_class);
				
				$newModel = new $newClassName();
				return $newModel;
				
			}
			else {
				throw new Exception('Problem loading Model.');
			}
			
		}
	}
	
	public function getPageTitle()
	{
		if($this->_pageTitle!==null)
			return $this->_pageTitle;
		else
		{
			$name=ucfirst(basename(App::getController()));
			return $this->_pageTitle= App::Tools()->sanitize_text(App::config()->name . " > " . $name);
		}
	}
	
	/**
	 * @param string $pageTitle the page title.
	 */
	public function setPageTitle($pageTitle,$useFullTitle = false)
	{
		if($useFullTitle===true) {
			$pageTitle = App::Tools()->sanitize_text($pageTitle);
		}
		else {
			
			$pageTitle = App::Tools()->sanitize_text(App::config()->name . " > " . $pageTitle);
		}
   		
		$pageTitle = str_replace(">","&raquo;",$pageTitle);
		
		$this->_pageTitle = $pageTitle;
			
  	}
	
	public function set404($customHandler=false)
	{
		RouteManager::set404($customHandler);
	}
	
	public function createUrl($route,$params=null,$ampersand='&')
	{
		return RouteManager::createUrl($route,$params,$ampersand);
	}
	
	public function redirect( $link )
	{
		RouteManager::redirect( $link );
	}
	
	public function getUrlReferrer()
	{
		return RouteManager::getUrlReferrer();
	}
	
	public function setReturnUrl($url=null)
	{
		if($url)
			App::setSession('ReturnUrl',$url);
			
		App::setSession('ReturnUrl',$_SERVER['REQUEST_URI']);
	}
	
	public function getReturnUrl()
	{
		return App::getSession('ReturnUrl');
	}
	
	public function loginGuest()
	{
		if( App::User()->isGuest ) {
			$this->setReturnUrl();
			$this->redirect($this->createUrl( '/account/login'));
		}
	}

	public function errorMessage($error=false,$type='info',$message, $print=true){
		if( !$error )
			return;

		$html = '<div class="alert alert-'.$type.'" role="alert">';
		$html .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
		$html .=$message.'</div>';

		if($print)
			echo $html;
		else
			return $html;
	}

	public function getProfileURL($user_id){
		return RouteManager::createUrl('/account/userprofile/'.$user_id);
	}
}