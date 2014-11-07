<?php

class Cview
{
	protected $viewFile;
	private $_pageTitle;
	
	protected function beforeRender($view)
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
	
	public function createUrl($route,$params=array(),$ampersand='&')
	{
		return RouteManager::createUrl($route,$params,$ampersand);
	}
	
	public function redirect( $link )
	{
		RouteManager::redirect( $link );
	}
}