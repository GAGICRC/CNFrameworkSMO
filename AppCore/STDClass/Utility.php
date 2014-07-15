<?php 

class Utility {

	public static function startsWith($haystack, $needle) {
	    return !strncmp($haystack, $needle, strlen($needle));
	}
	
	public static function entropyGenerator($length) { 
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	
	
	public static function var_name(&$iVar, &$aDefinedVars) {	
	    foreach ($aDefinedVars as $k=>$v)
	        $aDefinedVars_0[$k] = $v;
	 
	    $iVarSave = $iVar;
	    $iVar     =!$iVar;
	 
	    $aDiffKeys = array_keys (array_diff_assoc ($aDefinedVars_0, $aDefinedVars));
	    $iVar      = $iVarSave;
	 
	    return $aDiffKeys[0];
	}
	
}

?>