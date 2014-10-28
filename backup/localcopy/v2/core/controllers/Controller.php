<?php

class Controller
{
	protected $_model;
	protected $_controller;
	protected $_action;
	protected $view;
	protected $_modelBaseName;
	
	public function __construct($model, $action)
	{
		$this->_controller = ucwords(__CLASS__);
		$this->_action = $action;
		$this->_modelBaseName = $model;
		$this->loadview($action);
	}
	
	protected function _setModel($modelName)
	{
		$modelName = ucwords($modelName);
		$modelName .= 'Model';
		$this->_model = new $modelName();
	}
	
	protected function _setView($viewName)
	{
		$this->loadview($viewName);
	}
	
	protected function loadview($viewName){
	
		$modelName = strtolower($this->_modelBaseName);
		$viewFile = COREPATH .DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$modelName.DIRECTORY_SEPARATOR. $viewName . '.tpl';
		
		if( is_file($viewFile) ){
			$this->view = new View($viewFile);
		}else{
			//Maybe throw some error here
		}
	}
}
