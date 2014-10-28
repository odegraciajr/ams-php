<?php
class Tools extends GUMP{
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
	
	public function sanitize_text($text)
	{
		$myText = array(
			'text' => $text
		);

		$filters = array(
			'text' => 'sanitize_string'
		);
		
		$validated = $this->filter($myText, $filters);
		
		return $validated['text'];
	}
}