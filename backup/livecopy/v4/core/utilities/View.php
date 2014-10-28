<?php

class View
{
	protected $_file;
	protected $_layout;
	protected $_data = array();
	public $active_nav;
	
	public function __construct($file)
	{
		$this->_file = $file;
		$this->_layout = COREPATH .DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR. 'main.tpl';
		$this->_data['error'] = false;
		$this->active_nav = "dashboard";
	}
    
	public function set($key, $value)
	{
		$this->_data[$key] = $value;
	}
	
	public function get($key)
	{
   		return $this->_data[$key];
  	}
	
	public function setLayOut($value)
	{
		$this->_layout = COREPATH .DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR. $value.'.tpl';
	}
	
	public function output()
	{
		if (!is_file($this->_file))
		{
			throw new Exception("View " . $this->_file . " doesn't exist.");
		}
		
		extract($this->_data);
		ob_start();
		include($this->_layout);
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
}