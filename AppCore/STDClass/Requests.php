<?php

class Requests {

	public static function RequestHandler($inRequest) {
		
		if (!is_array($inRequest))
			return $inRequest;

		$inRequest = preg_replace('/^[*^0-9\/]/s', '', $inRequest);
		
		//var_dump($inRequest);

		$stdObj = new stdClass();
		$stdObj = (object)$inRequest;
		
		return $stdObj;
		
	}
	
	public static function postHandler($inPost, $stripHtml = false) {
		
		if (empty($inPost))
			return false;
		
		if (!is_array($inPost))
			return $inPost;
		
		if ($stripHtml) {
			foreach ($inPost as $key => $value) 
				$inPost[$key] = htmlspecialchars($value);
		}	
			
		$stdObj = new stdClass();
		$stdObj = (object)$inPost;
		
		return $stdObj;
		
	}

}

?>