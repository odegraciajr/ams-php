<?php
/**
 * This class contains various helpful functions.
 */
 
class Helper
{
	public static function setActiveNav($newValue, $default, $print=true, $trueHtml='class="active"',$falseHtml="")
	{
		if($newValue == $default) {
			if( $print )
				echo $trueHtml;
			
			return $trueHtml;
		}
		else {
			if( $print )
				echo $falseHtml;
			
			return $falseHtml;
		}
	}

	public function getCountries()
	{
		return include COREPATH . '/data/country.php';
	}
}