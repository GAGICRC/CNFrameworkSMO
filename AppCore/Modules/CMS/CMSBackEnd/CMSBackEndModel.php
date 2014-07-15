<?php

require_once __DIR__."/../CMSModel.php";

class CMSBackEndModel extends CMSModel {
	
	protected static $instance;
	  
	public $CMSModel;
	protected $defaultPublicationStatus;
	protected $stdPasswdLen;
	
	private function __construct() {
		$this->CMSModel = CMSModel::getInstance();
		$this->defaultPublicationStatus = $this->CMSModel->Model->defineOption("defaultPublicationStatus");
		$this->stdPasswdLen = $this->CMSModel->Model->defineOption("stdPasswdLen");
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	/* ----------------------------------- Model Methods ----------------------------------- */
	
	public function getPublicationStats($types) {
		
		$storedData = $this->CMSModel->Model->db->query("SELECT COUNT(publication.id) AS count, type.name as type FROM publication LEFT JOIN type ON publication.type = type.id GROUP BY type.name");
		
		if (!$this->CMSModel->Model->issetDBR($storedData)) {
			return false;
		}
		
		$stats = array();
		while ( $row = $storedData->fetch_object() )
	   		$stats[] = $row;
		
		return $stats;
		
	}
	
	public function setPublicationContent($pubId, $pubTitle, $pubContent, $comments = null) {
		
		if (is_null($comments))
			return $this->CMSModel->Model->update("publication", array("title" => $pubTitle, "content" => $pubContent), array("id" => $pubId));
		else
			return $this->CMSModel->Model->update("publication", array("title" => $pubTitle, "content" => $pubContent, "comments_open" => $comments), array("id" => $pubId));
	}
	
	public function getPossibleStatusForPublicationsByUser($userId) {
		$possibleStatuses = $this->selectTable("status", array("id","name"));
		
		// Verificar se o utilizador tem permissão para definir em cada um dos estados
		foreach ($possibleStatuses as $statusKey => $status) {
			if (!$this->CMSModel->Model->userHasPermission($userId,"publicationToStatus{$status->id}Action"))
				unset($possibleStatuses[$statusKey]);
		}
		
		return $possibleStatuses;
	}
	
	public function userList($onlyPending = false) {		
		
		// Permite apenas devolver utilizadores com o registo pendente
		if ($onlyPending)
			$storedUsers = $this->CMSModel->Model->db->query("SELECT id, name, username, email, status, id_role FROM user WHERE status = '3'");
		else
			$storedUsers = $this->CMSModel->Model->db->query("SELECT id, name, username, email, status, id_role FROM user WHERE status != '3'");
		
		$users = array();
		while ( $row = $storedUsers->fetch_object() )
		   		$users[] = $row;

		return $users;
		
	}
	
	public function userGetInfo($userId, $fields = null) {
		$currentUser = $this->CMSModel->Model->select("user", array("id" => $userId, $fields)); // todo fix
		if (!$this->CMSModel->Model->issetDBR($currentUser))
			return false;
		
		return $currentUser->fetch_object();
	}
	
	public function userSetInfo($userId, $newInfo) {	
		return $this->CMSModel->Model->update("user", $newInfo, array("id" => $userId));
	}
	
	public function isUserDataUnique($userId, $username, $useremail) {
		$result = $this->CMSModel->Model->db->query("SELECT id FROM user WHERE id != '{$userId}' AND ( email = '{$useremail}' OR username = '{$username}')");
								
		return !$this->CMSModel->Model->issetDBR($result);
	}
	
	public function userDelete($userId) {
		return $this->CMSModel->Model->delete("user", array("id" => $userId));
	}
	
	public function userPasswdReset($userId) {
		// Obter email do utilizador
		$userEmail = $this->CMSModel->Model->selectOne("user", array("id" => $userId), "email");
		if ($userEmail === false)
			return false;
		
		// Gerar nova palavra chave
		$newPasswd = Utility::entropyGenerator($this->stdPasswdLen);
		
		// Guardar a nova senha!
		$passwd_hash = hash("sha256", $newPasswd);
		if (!$this->userSetInfo($userId, array("passwd_hash" => $passwd_hash)))
			return false;
			
		// Enviar email
		$subject = "Mova Palavra-Chave";	
		$message = "A sua nova palavra-chave de acesso ao WebFeels:\n\n   {$newPasswd}\n\nObrigado.";
			
		if (!$this->CMSModel->Model->sendMail($userEmail, $subject, $message))
			return false;
					
		return true;
	}
	
	public function selectTable($table, $fields = null) {
		
		$query = "";
		   
		$prefix = "SELECT ";
		if (!is_null($fields)) {
			foreach ($fields as $key => $value) {
				$query .= "{$prefix}`{$value}`";
				$prefix = ",";
			}
		} else
			 $query .= "{$prefix} * ";
			
		$query .= " FROM {$table}";
				
		$result = $this->CMSModel->Model->db->query($query);
		
		$tableArray = array();
		while ( $row = $result->fetch_object() )
		   		$tableArray[] = $row;
		
		return $tableArray;
		
	}
	
	public function registerActions($actions) { 
		// Apagar todas as acções
		$this->CMSModel->Model->recreateTable("feature");
		
		// Apaga todas as associações role -> feature
		$this->CMSModel->Model->recreateTable("role_feature");
		
		// Inserir novas acções
		$query = "INSERT INTO feature (name, description) ";
		$prefix = " VALUES ";
		
		foreach ($actions as $action) {
			$query .= "{$prefix}('{$action}','{$action}')";
			$prefix = ", ";
		}
				
		$query .= ";";
		$result = $this->CMSModel->Model->db->query($query);
		return ($this->CMSModel->Model->db->affected_rows > 0) ? true : false;	
	}
	    
	public function insertPublication($publication) {
		
		if (!isset($publication->type))
			return false;
		
		if (!isset($publication->date))
			$publication->date = date("Y-m-d H:i:s");
		
		if (!isset($publication->comments_open))
			$publication->comments_open = 0;
		
		if (!isset($publication->id_user))
			$publication->id_user = $_SESSION['userId']; 
			
		if (!isset($publication->status))
			$publication->status = $this->defaultPublicationStatus;
		
		$publication = (array)$publication;
		
		if ($this->CMSModel->Model->insert("publication", $publication))
			return $this->CMSModel->Model->db->insert_id;
		
		return false;
	}
	
	public function setPublicationStatus($pubicationId, $newStatus) {
		// Verifica se a publicação existe
		if (!isset($pubicationId) || !$this->CMSModel->Model->exists("publication", array("id" => $pubicationId)))
			return false;
	
		if (!$this->CMSModel->Model->update("publication", array("status" => $newStatus), array("id" => $pubicationId)))
			return false;
		
		// Obter o tipo e devolver
		$result = $this->CMSModel->Model->db->query("SELECT publication.type, type.name AS type_name FROM publication LEFT JOIN `type` ON publication.type = type.id WHERE publication.id = {$pubicationId}");
		
		$result = $result->fetch_object();		
		return $result->type_name;
	}
	
	public function deletePublications($type, $status) {
		$result = $this->CMSModel->Model->db->query("DELETE FROM publication WHERE type = {$type} AND status = {$status}");
		if ($this->CMSModel->Model->db->affected_rows < 0) 
			return false;
			
		$result = $this->CMSModel->Model->select("type", array("id" => $type), array("name"));
		$result = $result->fetch_object();
		return $result->name;
	}
	
	
	/* Amazenamento */
	public function listStoredObjects($limit = null, $type = null, $publicUploadFolder = null) {
		$theLimit = null;
		
		if (!is_null($limit))
			$theLimit = array("id", $limit);
	
		$result = $this->CMSModel->Model->select("storage", null, array("id","date","storage","name", "size"), $theLimit);
		
		if (!$this->CMSModel->Model->issetDBR($result))
			return false;
		
		$objects = array();
		    	
		while ($row = $result->fetch_object()) {
		  		if (!is_null($publicUploadFolder)) $row->storage = $publicUploadFolder.$row->storage;
		  		$objects[] = $row;
		 } 		
		return $objects;
	}
	

}

?>