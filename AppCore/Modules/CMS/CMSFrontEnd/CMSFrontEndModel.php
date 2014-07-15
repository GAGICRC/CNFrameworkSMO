<?php

require_once __DIR__."/../CMSModel.php";

class CMSFrontEndModel extends CMSModel {
	
	protected static $instance;
	  
	public $CMSModel;
	protected $defaultCommentStatus;
	protected $isHome;
	
	private function __construct() {
		$this->CMSModel = CMSModel::getInstance();		
		$this->defaultCommentStatus = $this->CMSModel->Model->defineOption("defaultCommentStatus");
		$this->isHome = $this->CMSModel->Model->defineOption("initPage");
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	/* ----------------------------------- Controller Methods ----------------------------------- */
	
	public function getPageMenu() {
				
		$result = $this->CMSModel->Model->select("publication", array("type" => 1, "status" => 1), array("id", "title"), array("id", 5));
		
		$menu = array();
		while ( $row = $result->fetch_object() ) {
				if ($row->id == (int)$this->isHome)
					continue;
				$menu[] = $row;
		}		
		
		return $menu;
	}
	
	public function insertComment($articleId, $name, $email, $content) {
			
		if (!(filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email)))	
			return false;
		
		// Verificar se o email já está registado
		if (!$this->CMSModel->Model->exists("user", array("email" => $email))) {
			// Registar email
			$tempUsername = "comm_".Utility::entropyGenerator("7");
			if(!$this->CMSModel->Model->insert("user", array("email" => $email, "name" => $name, "username" => $tempUsername,"id_role" => 5, "status" => 0)))
				return false;
		} 
	
		$userId = $this->CMSModel->Model->selectOne("user", array("email" => $email), "id");
		if ($userId===false)
			return false;

		// Preprar comentário
		$date = date("Y-m-d H:i:s");
		$comment = array(
			"content" => $content,
			"id_user" => $userId,
			"id_parent" => $articleId,
			"date" => $date,
			"status" => $this->defaultCommentStatus,
			"type" => 3,
		);
		
		$comment = (array)$comment;
		
		// Inserir comentário
		if ($this->CMSModel->Model->insert("publication", $comment))
			return $this->CMSModel->Model->db->insert_id;
		
		return false;
	}

}

?>