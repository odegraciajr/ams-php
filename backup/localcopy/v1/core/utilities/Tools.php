<?php
class Tools{
	/**
	* This are some useful functions for the app
	*/
	public function __construct(){}
	
	public static function toJson($array,$print=true)
	{
		if($print) {
			header('Content-Type: application/json');
			echo json_encode($array);
			die();
		}
		else {
			return json_encode($array);
		}
	}
}