<?php

class FileSystem {

	public static function listSubFolders($inDir) {
		
		$inDir = __DIR__."/../".$inDir;
		$folderList;

		if ($handle = opendir($inDir)) {

		    while (false !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != "..") {
		        	$folderList[] = $entry;
		        }
		    }
		    closedir($handle);
		}
		return $folderList;

	}
	public static function listSubFiles($inDir) {
		
		$fileList;

		foreach(glob($inDir . "*.") as $filename)
		{
			  $fileList[] = $filename;
		}
		return $fileList;
	}
}

?>