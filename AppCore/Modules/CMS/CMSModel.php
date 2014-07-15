<?php

require_once __DIR__."/../../Model.php";

class CMSModel extends Model {

	protected static $instance = NULL;
	
	public $Model;
	
	private function __construct() {
		$this->Model = Model::getInstance();
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	/* ----------------------------------- CMSModel Methods ----------------------------------- */

	public function getMultiplePublications($publicationType, $limit, $staus = null, $parentId = null, $resume = false) {
		
		$whereArray = array("type" => $publicationType);
		
		if (isset($staus))
 			$whereArray['status'] = $staus;
 			
 		if (isset($parentId))
 			$whereArray['id_parent'] = $parentId;	
		
		$result = $this->Model->select("publication", $whereArray, array("id","date","title","content","comments_open","id_user"), array("id", $limit));
		
		if (!isset($result) || !$result)
			return false;
		
		$publications = array();
		    	
		while ($row = $result->fetch_object()) {
   	   		if ($resume) $row->content = substr($row->content, 0, 50)."...";
   	   		$publications[] = $row;
   	   	}	
			
		return $publications;
	}

	public function getPublication($publicationId) {

		$query = "SELECT publication.id, publication.date, publication.title, publication.content, publication.comments_open, publication.id_user, user.name AS author_name FROM publication LEFT JOIN user ON publication.id_user = user.id WHERE publication.id = '{$publicationId}'";
		
		$result = $this->Model->db->query($query);
		
		if (!$this->Model->issetDBR($result))
			return false;
			
		return $result->fetch_object();
	}
	
}

?>