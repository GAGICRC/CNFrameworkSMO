<?php

class Model {

	protected static $instance = NULL;
	protected $db;
	protected $dbSettings;
	protected $dbCreate;
	protected $dbTableCreate;
	public $webfeelsEmail;
	public $webfeelsEmailName;
		
	private function __construct() {
		$this->dbSettings = appConfig::getDBSettings();
		
		// Criação da base de dados
		$this->dbCreate = "DROP DATABASE IF EXISTS {$this->dbSettings->sqlDB};
		CREATE DATABASE {$this->dbSettings->sqlDB} DEFAULT CHARACTER SET UTF8 DEFAULT COLLATE utf8_general_ci;
		USE {$this->dbSettings->sqlDB};
		";
		
		// Array de criação das tabelas do sistema
		$this->dbTableCreate = array();
		
		$this->dbTableCreate["user"] = "
			CREATE TABLE user (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(70) NOT NULL,
				username VARCHAR(32) DEFAULT NULL,
				passwd_hash VARCHAR(64) DEFAULT NULL,
				email VARCHAR(254) UNIQUE NOT NULL,
				id_role TINYINT(3) UNSIGNED NOT NULL,
				status TINYINT(3) UNSIGNED NOT NULL
			);
		";
		
		$this->dbTableCreate["user--data"] = '
			INSERT INTO user (name,username,passwd_hash,email,id_role,status) VALUES
				("WebFeels Default User","wfdefault","5369e656b863ec53a5a561458e48c493bba2f1d34297e06536c5fd439f5c0a49","default@webfeels.dev",1,1);
		';
		
		$this->dbTableCreate["role"] = "
			CREATE TABLE role (
				id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(15) NOT NULL
			);
		";
		
		$this->dbTableCreate["role--data"] = '
			INSERT INTO role (name) VALUES
				("Administrador"),
				("Editor"),
				("Moderador"),
				("Tradutor"),
				("Ninguém");
		';
		
		$this->dbTableCreate["feature"] = "
			CREATE TABLE feature (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(40) NOT NULL,
				description VARCHAR(50) NOT NULL
			);
			";
			
		$this->dbTableCreate["feature--data"] = "
			INSERT INTO feature (name, description) VALUES
				('homeAction','Aceder à Área de Gestão'),
				('pagesAction','Listar Páginas'),
				('pagesNewAction','Criar Páginas'),
				('pagesEditAction','Editar Páginas'),
				('pagesDraftsAction','Rascunhos de Páginas'),
				('pagesModerateAction','Moderação de Páginas'),
				('pagesArchiveAction','Arquivo de Páginas'),
				('pagesBinAction','Lixeira de Páginas'),
				('articlesAction','Listar Artigos'),
				('articlesNewAction','Criar Artigos'),
				('articlesEditAction','Editar Artigos'),
				('articlesDraftsAction','Rascunhos de Artigos'),
				('articlesArchiveAction','Arquivo de Artigos'),
				('articlesModerateAction','Moderação de Artigos'),
				('articlesBinAction','Lixeira de Artigos'),
				('commentsAction','Listar Comentários'),
				('commentsEditAction','Editar Comentários'),
				('commentsModerateAction','Moderação de Comentários'),
				('commentsBinAction','Lixeira de Comentários'),
				('usersAction','Listar Utilizadores'),
				('usersProfileAction','Ver Perfil Pessoal'),
				('usersApproveAction','Aprovar Registos de Utilizadores'),
				('usersChangeSettingsAction','Modificar Papel/Estado dos Utilizadores'),
				('usersDeleteAction','Apagar Utilizadores'),
				('usersPasswdResetAction','Redefinir a Palavra-Chave dos Utilizadores'),
				('usersWaitingAction','Listar Registos a Aguardar Aprovação'),
				('settingsAction','Definições'),
				('settingsGlobalAction','Definições Globais'),
				('settingsPermissionsAction','Listar e Modificar as Permissões dos Papéis'),
				('errorAction','Visualizar Erros'),
				('publicationToStatus1Action','Definir Publicação como Publicada'),
				('publicationToStatus2Action','Definir Publicação como Rascunho'),
				('publicationToStatus3Action','Definir Publicação como Pendente'),
				('publicationToStatus4Action','Definir Publicação como Arquivada'),
				('publicationToStatus5Action','Definir Publicação como Lixo'),
				('emptyBinAction','Esvaziar o Lixo'),
				('filesAction','Listar Ficheiros'),
				('filesUpload','Enviar Ficheiros'),
				('filesDelete','Apagar Ficheiros');
		";
		
			
		$this->dbTableCreate["role_feature"] = "
			CREATE TABLE role_feature (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				id_role TINYINT(3) UNSIGNED NOT NULL,
				id_feature INT UNSIGNED NOT NULL
			);
		";
		
		$this->dbTableCreate["role_feature--data"] = "
			INSERT INTO role_feature (id, id_role, id_feature) VALUES
				(1, 2, 1),
				(2, 3, 1),
				(3, 2, 2),
				(4, 2, 3),
				(5, 2, 4),
				(6, 2, 5),
				(7, 2, 7),
				(8, 2, 8),
				(9, 2, 9),
				(10, 2, 10),
				(11, 2, 11),
				(12, 2, 12),
				(13, 2, 13),
				(14, 2, 15),
				(15, 3, 16),
				(16, 3, 17),
				(17, 3, 18),
				(18, 3, 19),
				(19, 2, 20),
				(20, 2, 21),
				(21, 3, 31),
				(22, 2, 32),
				(23, 2, 33),
				(24, 3, 33),
				(25, 2, 34),
				(26, 3, 34),
				(27, 2, 35),
				(28, 3, 35),
				(29, 2, 37),
				(30, 2, 38),
				(31, 2, 39);
		";
		
		// Definições do Sistema...
		$this->dbTableCreate["wfoption"] = "
			CREATE TABLE wfoption (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(25) NOT NULL,
				value VARCHAR(25) NOT NULL
			);
		";
		
		$this->dbTableCreate["wfoption--data"] = '
			INSERT INTO wfoption (name, value) VALUES
				("rootAdmin", "1"),
				("defaultPublicationStatus", "3"),
				("stdPasswdLen", 15),
				("pubPeerPage", 10),
				("guestRole", 7),
				("userStatusAfterApproval", 1),
				("maxUploadSize", 20000),
				("webfeelsEmail","wefeels@webfeels.dev"),
				("sitename","WebFeels!"),
				("initPage",1),
				("defaultCommentStatus",3),
				("articlesPeerPage", 10),
				("defaultRole",4),
				("defaultRegStatus",3);
		';	
		
		// Storage
		$this->dbTableCreate["storage"] = "
			CREATE TABLE storage (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				date DATETIME NOT NULL,
				storage VARCHAR(40) NOT NULL,
				name VARCHAR(40) NOT NULL,
				size int(20) NOT NULL
			);
		";
		
		$this->dbTableCreate["storage--data"] = "
			INSERT INTO storage (date, storage, name, size) VALUES
				('2013-04-07 22:20:06', '2013040722hvFnu.png', '2013040722hvFnu', 556);
		";
		
		// Publication
		$this->dbTableCreate["publication"] = "
			CREATE TABLE publication (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				type TINYINT(2) UNSIGNED NOT NULL,
				status TINYINT(1) UNSIGNED NOT NULL,
				date DATETIME NOT NULL,
				title TEXT DEFAULT NULL,
				content LONGTEXT NOT NULL,
				id_user INT UNSIGNED NOT NULL,
				comments_open TINYINT UNSIGNED DEFAULT 0,
				id_parent INT UNSIGNED
			);
		";
		
		$this->dbTableCreate["publication--data"] = "
			INSERT INTO publication (id, type, status, date, title, content, id_user, comments_open, id_parent) VALUES
			(1, 1, 1, '2013-04-07 22:15:32', 'Home', '# Bem-Vindo(a) ao Site Oficial do WebFeels! #\r\n\r\nO **WebFeels** é um CMS, Content Management System permite aos seus utilizadores, recorrendo apenas a conhecimentos de informática na óptica do utilizador, tenham total autonomia sobre a gestão do conteúdo dispensando a assistência de terceiros e/ou linguagens de estruturação de conteúdos.\r\n\r\nDesenvolvido em Portugal pela turma de Desenvolvimento de Software e Administração de Sistemas o WebFeels apresenta-se como uma solução alternativa de elevada qualidade!\r\n\r\n![DSAS][1]\r\n\r\n  [1]: http://webfeels.me/Content/2013040922i4Rnu.jpg', 1, 0, NULL),
			(2, 1, 4, '2013-04-07 22:21:12', 'Página Buunita!', 'Esta é uma página de exemplo muito *buuunita*!', 1, 0, NULL),
			(3, 2, 4, '2013-04-07 22:21:45', 'Hello World!', 'Bem-Vindo ao mundo do blogging!', 1, 1, NULL),
			(4, 1, 1, '2013-04-09 21:45:17', 'Porquê?', 'O **WebFeels** foi desenvolvido em Portugal exclusivamente por alunos na Universidade Lusófona de Humanidades e Tecnologias. É altamente modular e pode ser aumentado/modificado a qualquer momento. Tem também implementadas tecnologias de segurança de ponta, de modo a limitar a todos os momentos qualquer probabilidade de ataque ou roubo de dados. \r\n\r\n![Made in Portugal][1]\r\n\r\nQuando falamos de segurança do nosso CMS na vertente das permissões diferencia dos outros porque podemos gerir os utilizadores na edição, envio, rascunho das publicações.\r\n<br /> <br /> \r\nPara garantir a segurança e gestão, todos os utilizadores do sistema são distinguidos por \"papéis\" únicos com permissões de acesso e utilização do sistema específicas previamente definidas por um Administrador.\r\n<br /> <br /> \r\nO WebFeels tem também uma interface única, inspirada na interface Metro (Modern) da Microsoft que fomenta uma utilização rápida, eficaz e intuitiva, com o uso de cores sólidas e contrastantes.\r\nUm dos motivos que nos levou também a fazer um CMS de raiz foi pela vertente didática, o descobrir, utilizar os conhecimentos adquiridos para termos um conhecimento geral de como é fazer um CMS.\r\n<br /> <br /> \r\n\r\n  [1]: http://webfeels.me/Content/2013040921xT32D.png', 1, 0, NULL),
			(5, 2, 1, '2013-04-09 22:00:20', 'WebFeels Actualizado!', 'O WebFeels foi hoje actualizado para a versão 127!\r\n\r\nEsta actualização visa:\r\n\r\n* Correcções menores na interface da Área de gestão;\r\n* Implementaçãoo de segurança adicional no sistema de comentários;', 1, 0, NULL),
			(6, 2, 1, '2013-04-09 22:02:27', 'A \"Forma WebFeels\"', 'O WebFeels é altamente personalizavel e pode ser fácilmente alterado para responder Ã s suas necessidades, tanto funcionais como gráficas. \r\nGraças Ã  sua componente modular, é sempre possível adicionar mais funcionalidades (ou alterar o comportamento das existentes). Tudo isto da forma *WebFeels*, a fácil!', 1, 1, NULL),
			(7, 1, 1, '2013-04-09 22:08:23', 'Em Detalhe', 'O *WebFeels* permite aos seus utilizadores, recorrendo apenas a conhecimentos de informática na óptica do utilizador, tenham total autonomia sobre a gestão do conteúdo dispensando a assistência de terceiros e/ou linguagens de estruturação de conteúdos.\r\n\r\n1. **Área Pública:** Parte da plataforma que apresenta os conteúdos a todas as pessoas que acedam aos sites;\r\n\r\n2. **Área de Gestão**: Parte interna da plataforma destinada a gerir os conteúdos e comportamento da plataforma, apenas acessí­vel a certos utilizadores;\r\n\r\n3. **Edição de Conteúdos**: Interface genérica para a criação, edição e gestão de conteúdos a ser utilizado por todos os sites baseada no standart MarkDown.\r\n\r\n4. **Diferenciação de Conteúdos**: Páginas, Artigos e Comentários.\r\n\r\n5. **Gestão de Utilizadores**: Sistema de atribuição de permissões a grupos de utilizadores altamente flexível e adaptável a qualquer situação.\r\n', 1, 0, NULL);
		";
		
		// Category // todo não implementado
		$this->dbTableCreate["category"] = "
			CREATE TABLE category (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(25) NOT NULL,
				id_parent INT UNSIGNED NOT NULL
			);
		";
		
		$this->dbTableCreate["publication_category"] = "	
			CREATE TABLE publication_category (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				id_pub INT UNSIGNED NOT NULL,
				id_cat INT UNSIGNED NOT NULL
			);
		";
		
		// Status
		$this->dbTableCreate["status"] = "
			CREATE TABLE status (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(50) NOT NULL
			);
		";
		
		$this->dbTableCreate["status--data"] = '	
			INSERT INTO status (name) VALUES 
				("__%published"), 
				("__%draft"), 
				("__%pending"), 
				("__%archived"), 
				("__%trash");
		';
		
		// Type
		$this->dbTableCreate["type"] = '
			CREATE TABLE type (
				id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
				name VARCHAR(15) NOT NULL
			);
		';
		
		$this->dbTableCreate["type--data"] = '	
			INSERT INTO type (name) VALUES
				("__%page"),
				("__%article"),
				("__%comment"),
				("__%ui_object");
		';
		
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	public function run() {
		
		@$this->db = new mysqli($this->dbSettings->sqlServer, $this->dbSettings->sqlUser, $this->dbSettings->sqlPasswd, $this->dbSettings->sqlDB);
		
		// Verificar se foi possível ligar à base de dados;
		if (mysqli_connect_errno()) {
		   	$this->dbSetup(mysqli_connect_errno());
		}
		
		// Verificar a integridade da base de dados
		$this->checkDB();
		
		// Definir as opções necessárias
		$this->webfeelsEmail = $this->defineOption("webfeelsEmail");
		$this->webfeelsEmailName = $this->defineOption("sitename");
		
		return true;
		
	}
	
	/* Incialização à base de Dados! */
	public function dbSetup($errorCode) {
				
		switch ($errorCode) {
			case 1049: {// Base de dados não existe
				//$this->createDB(); // todo
				print "A base de dados não existe.";
				exit(0);
				break;
			}	
			case 1045: {// Login incorrecto
				print "Os dados de acesso à base de dados são inválidos. Por favor reveja a sua configuração.";
				exit(0);
				break;
			}	
			case 2001: { // Erro ao abrir o socket
				print "Erro ao abrir o socket para o servidor de MySQL.";
				exit(0);
				break;
			}	
			case 2005: {// Servidor não encontrado
				print "Servidor MySQL não encontrado.";
				exit(0);
				break;
			}	
			case 2006: { // MySQL server went away!
				print "MySQL server went away!";
				exit(0);
				break;
			}	
			case 2008: { // Cliente de MySQL ficou sem memória
				print "O cliente de MySQL ficou sem memória livre. Por favor reveja a configuração do servidor.";
				exit(0);
				break;
			}	
			 default: {
			 	print "Erro relacionado com base de dados não identificado: {$errorCode}";
				exit(0);
			}
		}
		
	}
	
	public function checkDB() {
		
		$showtables = $this->db->query("SHOW TABLES;");
		
		$tables = array();
		$dbprefix = "";
		while ( $row = $showtables->fetch_object() ) {
			$dbprefix = key((array)$row);
			$tables[] = $row->$dbprefix;
		}
						
		$ok = true;
		$missingTables = array();
		foreach ($this->dbTableCreate as $key => $value) {			
			if (strpos($key, '--') !== false) continue;
			if(!in_array($key, $tables)) {
				$missingTables[] = $key;
				$ok = false;
			}	
		}
		
		// Base de dados não está ok, recriar o que falta
		if (!$ok) {
			foreach ($missingTables as $key => $table) {
				$this->recreateTable($table); // Recriar a tabla em falta
				if (array_key_exists($table."--data", $this->dbTableCreate)) {
					$this->db->query($this->dbTableCreate[$table."--data"]); // Introduzir os dados...
				}	
				
			}
		}
		
	}
	 
	public function createDB() { // todo
		
		// Criar a base de dados
		$this->db->query($this->dbCreate);
	
		// Criar as tabelas...
		foreach ($this->dbTableCreate as $currentTable) {
			$this->recreateTable($currentTable);
		}
		
		// Voltar a tentar iniciar a ligação...
		$this->run();
	}	
	
	
	/* Mapeadores de Dados */
	
	/* Camada de Abstração do SQL */
	public function select($table, $where = null, $fields = null, $orderAndLimit = null, $rawOutput = true) {
		
		$query = "";
	       
		$prefix = "SELECT ";
		if (isset($fields)) {
			foreach ($fields as $key => $value) {
				$query .= "{$prefix}`{$value}`";
				$prefix = ",";
			}
		} else
			 $query .= "{$prefix} * ";
		
		$query .= " FROM {$table}";
		
		if (isset($where)) {
			$prefix = " WHERE";
			foreach($where as $key => $value) {
			    $query .= "{$prefix} {$key} = '{$value}'";
			    $prefix = " AND ";
			}
		}
		
		if (isset($orderAndLimit)) {
			if (is_array($orderAndLimit[1]))
				$query .= " ORDER BY `{$orderAndLimit[0]}` DESC LIMIT {$orderAndLimit[1][0]},{$orderAndLimit[1][1]}";
			else
				$query .= " ORDER BY `{$orderAndLimit[0]}` DESC LIMIT {$orderAndLimit[1]}";		
		}
		$query .= ";";
				         
	   	return $this->db->query($query);
	 }
	 
	 public function selectOne($table, $where, $parameter) {
	 	
	 	$theValue = reset($where);
	 	$theKey = key($where);
	 	
	 	$result = $this->db->query("SELECT {$parameter} FROM {$table} WHERE {$theKey} LIKE '{$theValue}'");
	 		 	
	 	if (!$this->issetDBR($result))
	 		return false;  // O parâmetro não existe...
	 	
	 	$result = $result->fetch_object();
	 	return $result->$parameter;
	 }
	 
	 
	 public function insert($table, $data) {
		 $query = "INSERT INTO {$table} (";
		 $prefix = "";
		 
		 foreach($data as $key => $value) {
		     $query .= "{$prefix}{$key}";
		     $prefix = ", ";
		 }
		 
		 $query .= ") VALUES (";
		 $prefix = "";
		 
		 foreach($data as $key => $value) {
		     $query .= "{$prefix}'{$value}'";
		     $prefix = ", ";
		 }
		 
		 $query .= ");";    
		                  
		$result = $this->db->query($query);
		return ($this->db->affected_rows > 0) ? true : false;
			
	 }
  
	 public function delete($table, $where) {
		 $query = "DELETE FROM {$table}";
		 $prefix = " WHERE ";
		 
		 foreach($where as $key => $value){
		     $query .= "{$prefix}{$key}='{$value}'";
		     $prefix = " AND ";
		 }
		 
		 $query .= ";";
				 
		 $result = $this->db->query($query);
		 return ($this->db->affected_rows > 0) ? true : false;
		 
	 }
	     
	 public function exists($table, $where) {    
		 $result = $this->select($table, $where);
		 return $this->issetDBR($result);
	 }
	 
	 public function issetDBR($bdObject) {
	 	return ($bdObject->num_rows > 0) ? true : false;
	 }
	     
	 public function update($table, $set, $where) {
	     	
	     	$query = "UPDATE {$table} ";
	     	
	     	$prefix = "SET ";
	     	foreach ($set as $key => $value) {
	     		$query .= "{$prefix}{$key}='{$value}'";
	     		$prefix = ",";
	     	}
	     	
	     	$prefix = " WHERE ";
	     	foreach ($where as $key => $value) {
	     		$query .= "{$prefix}{$key}='{$value}'";
	     		$prefix = ",";
	     	}
	     		     		     	
	     	$result = $this->db->query($query);
	     	return ($this->db->affected_rows > 0) ? true : false;
	 }
	 
	/* Fim dos Mapeadores de Dados */ 	 	
	
	public function recreateTable($tableName) { // todo: verificar por erros...
		$this->db->query("DROP TABLE IF EXISTS {$tableName};");
		$this->db->query($this->dbTableCreate[$tableName]);
		return true;
	}
	
	/* Gestão de Logins */
	public function userLogin($user = null) {
		
		if ($this->checkLogin())
			return true;
		
		// Verficiar se entraram todos os dados de login
		if (!isset($user->username) || !isset($user->passwd))
			return false;
	
		// Verfificar se o utilizador existe & está activo
		if(!$this->exists("user", array("username" => $user->username, "status" => 1)))
			return false;
		
		// Verificar se as password condiz
		$rs = $this->select("user", array("username" => $user->username), array("id","passwd_hash"));
		$data = $rs->fetch_object();
		        
		if($data->passwd_hash == hash("sha256",$user->passwd)) {
		    $_SESSION['userId'] = $data->id;
		    return true;
		}
		   
		return false;
	}
	
	public function userCheckPasswd($userId, $userPasswd) {
		$storedHash = $this->selectOne("user", array("id" => $userId), "passwd_hash");
				        
		if($storedHash == hash("sha256", $userPasswd))
		    return true;
		    
		return false;
	}
	
	public function getUserStatus($userId) {
		return $this->selectOne("user", array("id" => $userId), "status");
	}
	
	public function setUserStatus($userId, $status) {
		
		if ( !($status == 1 || $status == 0) )
			return false;
					
		return $this->update("user", array("status" => $status), array("id" => $userId));
	}
	
	public function checkLogin() {
		
		if (!isset($_SESSION))
			session_start();
		
		if(isset($_SESSION['userId']))
			return true;
			
		return false;
	}
	
	
	public function userLogout() {
		session_start();
		
		$_SESSION['userId'] = null;
		unset($_SESSION['userId']);
		session_destroy();
		
		return true;
	}
	
	public function userRegister($user) {
						
		if($this->exists("user", array("email" => $user->email)))
			return 0;
			
		if (!(filter_var($user->email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $user->email)))	
			return 1;   
		          
		if($this->exists("user", array("username" => $user->username)))
			return 2;
		    
		if (!preg_match("((?=.*\d)(?=.*[a-z]).{8,20})", $user->passwd))
		    return 3;
		
		$user->passwd_hash = hash("sha256", $user->passwd);
		unset($user->passwd);
		
		if (!$this->insert("user", (array)$user))
			return 4;
		
		return 5;	
	}
	
	/* Gestão de papéis de utilizadores */
	public function userHasPermission($userId, $featureName) {
								
		// Obter o $roleId do utilizador com ID em $userId
		$roleId = $this->userGetRole($userId);
		if ($roleId === false) 
			return false;

		// Não verificar permissões do Role de Administradores (1)
		if ($roleId == 1)
 			return true;
	
		// Obter o ID da feature com nome $featureName 
		$featureId = $this->selectOne("feature", array("name" => $featureName), "id");
		if ($featureId === false)
			return false;  // A permissão não existe
		
		// Verificar se o $userId tem a feature $featureId
		if (!$this->exists("role_feature", array("id_role" => $roleId, "id_feature" => $featureId)))
			return false; // O utilizador não tem permissão

		// Chegou ao fim: O utilizador em questão têm a permissão!
		return true;

	}
	
	public function userGetRole($userId) {
		return $this->selectOne("user", array("id" => $userId), "id_role");
	}	
		
	protected function userSetRole($userId, $newRole) {
				
		// Obter o ID para o role em $newRole
		$roleId = $this->select("role", array("name" => $newRole));
		if (!isset($roleId) || !$roleId) { // todo... mudar par iissetDBR?
			return false;  // Este role não existe!!
		}
		$roleId = $roleId->fetch_object();
		
		// Tentar actualizar o id_role, devolve false no caso de nenhuma row ser afectada
		return $this->update("user", array("id_role" => $roleId->id), array("id" => $userId));
		
	}
	
	public function updatePermissions($permissions) {
		
		// Remover todas as permissões da DB!
		$this->recreateTable("role_feature");
		
		$query = "INSERT INTO role_feature (id_role, id_feature) ";
				
		$prefix = "VALUES ";		
		foreach ($permissions as $permission) {
			$permission = explode("-", $permission);
			$query .= "{$prefix}('{$permission[0]}','{$permission[1]}')";
			$prefix = ", ";
		}
		
		$query .= ";";

		$result = $this->db->query($query);
		return ($this->db->affected_rows > 0) ? true : false;
		
	}
	
	protected function roleAddPermission($roleId, $idFeature) {
		// Adicionar permissão a $idFeature ao role $roleId
		// não precisa de verificar se já existe o role, id_role + id_feature = chave unique na db
		return $this->insert("role_feature", array("id_role" => $roleId, "id_feature" => $idFeature));		
	}
	
	protected function roleRemovePermission($roleId, $idFeature) {
		return $this->delete("role_feature", array("id_role" => $roleId, "id_feature" => $idFeature));	
	}
	
	// Funções de email
	protected function sendMail($to, $subject, $message) {
		$headers = "From: {$this->webfeelsEmailName} <{$this->webfeelsEmail}>";
		return mail($to, $subject, $message, $headers);
		
	}
	
	public function defineOption($optName) {
		return $this->selectOne("wfoption", array("name" => $optName), "value");
	}
	
}

?>