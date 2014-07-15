<?php

require_once __DIR__."/../CMSController.php";
require_once "CMSBackEndModel.php";

class CMSBackEndController extends CMSController {
	
	protected static $instance;
	
	private $CMSBackEndModel;
	private $pubPeerPage;
	private $guestRole;
	private $userStatusAfterApproval;
	private $publicationStatusTranslation;
	private $maxUploadSize;
	private $publicUploadFolder;
	private $internalUploadFolder;
	private $defaultRegStatus;
	private $defaultRole;

	private function __construct() {
		$this->CMSBackEndModel = CMSBackEndModel::getInstance();
		
		$this->pubPeerPage = $this->CMSBackEndModel->CMSModel->Model->defineOption("pubPeerPage");
		$this->guestRole = $this->CMSBackEndModel->CMSModel->Model->defineOption("guestRole");
		$this->defaultRegStatus = $this->CMSBackEndModel->CMSModel->Model->defineOption("defaultRegStatus");
		$this->defaultRole = $this->CMSBackEndModel->CMSModel->Model->defineOption("defaultRole");
		$this->userStatusAfterApproval = $this->CMSBackEndModel->CMSModel->Model->defineOption("userStatusAfterApproval");
		$this->maxUploadSize = $this->CMSBackEndModel->CMSModel->Model->defineOption("maxUploadSize");
		
		$this->publicUploadFolder = "http://" . static::$siteURL . "/Content/";
		$this->internalUploadFolder = static::$appCoreDir . "/../" . static::$publicFolder . "/Content/";
		
		$this->publicationStatusTranslation = array( // todo db
			"__%published" => "Publicado",
			"__%draft" => "Rascunho",
			"__%pending" => "Pendente para Moderação",
			"__%archived" => "Arquivado",
			"__%trash" => "Lixo",
		);
	}

	public function run($params = null) {
						
			// Reescrever as Acções Disponíveis no Sistema de Permissões
			//$this->registerActionMethods();
						
			// Accção padrão se não for passado nunhum pedido
			if (empty($params[0])) {
				$this->homeAction();
				return;
			}
			
			$method = $params[0]."Action";
			array_shift($params);
			
			if (empty($params))
				$params = null;
						
			if (method_exists($this,$method))
				$this->$method($params);
			else
				$this->errorAction(array("errorid" => "404", "errormsg" => "Página não encontrada."));
			
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	/* ----------------------------------- Controller Methods ----------------------------------- */
	
	protected function loadFullPage($path, $contentView, $data = null) {
		
			if ($this->CMSBackEndModel->CMSModel->Model->checkLogin()) {
				if ($this->CMSBackEndModel->CMSModel->Model->userGetRole($_SESSION['userId']) !== $this->guestRole) {
					$userInfo = $this->CMSBackEndModel->userGetInfo($_SESSION['userId'], array("name"));
					if (!!$userInfo)
						$data['_headerUsername'] = $userInfo->name;
				}
			}
						
			// Vista Cabeçalho
	        $this->loadSingleView($path,"header",$data);
	        
	        // Carrega o menu da esquerda se o utilizador estiver ligado
	        if ($this->CMSBackEndModel->CMSModel->Model->checkLogin())
	        	$this->loadBackEndNavigationProcess($data);
	        
	        // Vista de Conteúdo
	        $this->loadSingleView($path, $contentView, $data);
	        
	        // Vista de Rodapé
	        $this->loadSingleView($path,"footer", $data);
	        
	        exit; // Não remover!
	}
	
	protected function loadBackEndNavigationProcess($data) {
		
		// todo base de dados? Sistema de registo de menus?
		$menuItems = array( 
				array (
					"/CMSBackEnd/"		=>	"Início"
				),	
			
				array(
					"pages" 	=> "Páginas",
					""			=> "Publicadas",
					"New"		=> "Nova Página",
					"Drafts"	=> "Rascunhos",
					"Moderate"	=> "Moderar",
					"Archive"	=> "Arquivo",
					"Bin"		=> "Lixo"
				),
			
				array(
					"articles"	=> "Artigos",
					""			=> "Publicados",
					"New"		=> "Novo Artigo",
					"Drafts"	=> "Rascunhos",
					"Moderate"	=> "Moderar",
					"Archive"	=> "Arquivo",
					"Bin"		=> "Lixo"
				),
				
				array(
					"comments" 	=> "Comentários",
					""			=> "Publicados",
					"Moderate"	=> "Moderar",
					"Bin"		=> "Lixo",
				),
				
				array(
					"files"		=> "Media",
					""			=> "Todos",
					"Upload"	=> "Enviar"
				),
				
				array(
					"users"		=> "Utilizadores",
					""			=> "Todos",
					"Profile"	=> "Perfil Pessoal",
					"Waiting"	=> "Reg. Pendentes"
				),
			
				array(
					"settings"	=> "Definições",
					"Global"	=> "Globais",
					"Permissions" => "Permissões"
				)
			
		);
		
		// Remove as acçoes que o utilizador não têm permissão para ver/executar
		foreach ($menuItems as $menuItemKey => $menuItem) {
			$theValue = reset($menuItem);
			$firstKey = key($menuItem);
			
			if (!$this->CMSBackEndModel->CMSModel->Model->userHasPermission($_SESSION['userId'], $firstKey."Action") )
				unset($menuItems[$menuItemKey]);
			
			if (count($menuItem) > 1) {
				foreach ($menuItem as $subMenuKey => $subMenuItem) {
					if ($subMenuKey === $firstKey) 
						continue;
					
					if (!$this->CMSBackEndModel->CMSModel->Model->userHasPermission($_SESSION['userId'], $firstKey.$subMenuKey."Action") )
						unset($menuItems[$menuItemKey][$subMenuKey]);
				}
			}
					
		}
		
		$data['menuItems'] = $menuItems;
		$this->loadSingleView("CMS/CMSBackEnd/","leftmenu",$data);
	}
	
	// Retorna o nome do método que chamou o que chamou este!
	public function getCallerName() {
	    $e = new Exception(); // Em vez de debug_backtrace(), teoricamente mais leve.
	    $trace = $e->getTrace();
	    return $trace[2]['function'];
	}
	
	/* Responsável pela Verificação de Logins e Permissões */
	protected function loginHandler($block = true) {
		
		// Verificar se existe algum login		
		if ($this->CMSBackEndModel->CMSModel->Model->checkLogin()) {
			// Verificar se utilizador têm permissão...
			if (!$this->CMSBackEndModel->CMSModel->Model->userHasPermission($_SESSION['userId'],$this->getCallerName()) ) {
				if ($block) $this->loadFullPage("CMS/CMSBackEnd/","noPermission");
				return false;
			}
			
			return true;
		}		
		
		// Não existe nenhum login / utilizador está como Visitante
		$inPost = Requests::postHandler($_POST);
				
		// Verificar se não existe dados em post
		if (!$inPost) {
			$this->loadFullPage("CMS/CMSBackEnd/","loginMe");
			return false;
		}
		
		// Verificar se todos os campos estão preenchidos
		if (empty($inPost->username) || empty($inPost->passwd)) {
			$errorData = array("error" => "Preenchimento incorrecto dos dados de login!");
			$this->loadFullPage("CMS/CMSBackEnd/","loginMe", $errorData);
			return false;
		}
		
		// Login
		if (!$this->CMSBackEndModel->CMSModel->Model->userLogin($inPost)) { // Erro de login
			$errorData = array("error" => "Utilizador ou palavra-chave incorrectos!");
			$this->loadFullPage("CMS/CMSBackEnd/","loginMe", $errorData);
			return false;
		}
		
		// Após login verificar se o utilizador têm permissão de aceder ao método
		if (!$this->CMSBackEndModel->CMSModel->Model->userHasPermission($_SESSION['userId'],$this->getCallerName()) ) {
			$this->loadFullPage("CMS/CMSBackEnd/","noPermission");
			return false;
		}
		
		$_POST = null;
		return true;
		
	}
	
	// Regista os métodos desta página
	protected function registerActionMethods() {
		$actions = $this->listActions($this);
		if($this->CMSBackEndModel->registerActions($actions)) {
			print "Action Methods registered! Please remove this method call.";
			exit;
		}
	}
	
	// Fallback para todos os méotodos que não existam...
	public function __call($name, $arguments) {
		$this->loginHandler();
	    $this->errorAction(array("errorid" => "method_not_found"));
	}
	
	/* ----------------------------------- Content Methods ----------------------------------- */
	
	protected function logoutAction() {
		$this->CMSBackEndModel->CMSModel->Model->userLogout();
		$this->redir("/CMS/CMSBackEnd/");
	}
	
	protected function homeAction() {
		$this->loginHandler();
		
		// Obter estatísticas de publicações
		$statistics = $this->CMSBackEndModel->getPublicationStats(array(1,2,3));			
		
		// Traduzir Strings // todo mover para base de dados em sistema dinâmico
		$translation = array(
			"__%article" => "Artigo(s)",
			"__%comment" => "Comentário(s)",
			"__%page" => "Página(s)",
		);
		$data = $this->stringTranslator($statistics,$translation,"type");
						
		// Carregar página
		$data = array("stats" => $statistics);
		$this->loadFullPage("CMS/CMSBackEnd/","home", $data);
		
	}
	
	protected function stringTranslator($data, $translation, $translParam) {
		foreach ($data as $key => $value) {
			if (array_key_exists($value->$translParam,$translation))
				$value->$translParam = $translation[$value->$translParam];
		}
		return $data;
	}
	
	// Páginas
	protected function pagesAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("pages" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, $limit, 1, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","pages", $data);
	}
	
	protected function pagesNewAction($params = null) {
		$this->loginHandler();
		
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a criação de nova página standard
		if ($inPost === false) {	
			$possibleStatuses = $this->CMSBackEndModel->getPossibleStatusForPublicationsByUser($_SESSION['userId']);
			$possibleStatuses = $this->stringTranslator($possibleStatuses,$this->publicationStatusTranslation,"name"); // todo db
			$this->loadFullPage("CMS/CMSBackEnd/","pagesNew", array("possibleStatuses" => $possibleStatuses));
			return true;
		}
		
		// Verificar se todos os campos estão preenchidos
		if (empty($inPost->title) || empty($inPost->content)) {
			$this->errorAction(array("errorid" => "11", "errormsg" => "O preenchimento do título e conteúdo da página são obrigatórios!"));
			return false;
		}
		
		// Adicionar o tipo à publicação
		$inPost->type = 1;
		
		// Processar a criação da publicação
		$destination = $this->newPublicationProcess($inPost);
		if ($destination === false)
			return $this->errorAction(array("errorid" => "12", "errormsg" => "Impossível inserir página!"));
		
		$this->redir("/CMS/CMSBackEnd/{$destination}");		
		
	}
	
	protected function pagesEditAction($params) {
		$this->loginHandler();
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a editação da página...
		if ($inPost === false) {	
			if (empty($params[0]))
				return $this->errorAction(array("errorid" => "13", "errormsg" => "É necessário especificar uma página a editar."));
			
			$page = $this->CMSBackEndModel->CMSModel->getPublication((int)$params[0]);
			$this->loadFullPage("CMS/CMSBackEnd/","pagesEdit", array("page" => $page));
		}
		
		// Guardar alterações
		if (!$this->CMSBackEndModel->setPublicationContent($inPost->pageId, $inPost->title, $inPost->content))
			return $this->errorAction(array("errorid" => "14", "errormsg" => "Impossível actualizar publicação."));
		
		$this->redir("/CMS/CMSBackEnd/pages");
	}
	
	protected function newPublicationProcess($newPublication) {
		
		// Verificar se o utilizador pode publicar com este estado
		if (!$this->CMSBackEndModel->CMSModel->Model->userHasPermission($_SESSION['userId'],"publicationToStatus{$newPublication->status}Action"))
			unset($inPost->status); // Garante que o estado "default" só é definido no model.
		
		// Inserir a página
		$insertResult = $this->CMSBackEndModel->insertPublication($newPublication);
		if ($insertResult === false)
			$this->errorAction(array("errorid" => "12", "errormsg" => "Impossível inserir página!"));
		
		// Obter o nome do tipo
		$typeName = $this->CMSBackEndModel->CMSModel->Model->selectOne("type", array("id" => $newPublication->type), "name");		
		
		// Redirecionar para a vista do tipo...		
		return substr($typeName, 3) . "s";
	}
	
	
	protected function pagesDraftsAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("pages" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, $limit, 2, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","pagesDrafts", $data);
	}
	
	protected function pagesModerateAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("pages" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, $limit, 3, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","pagesModerate", $data);
	}
	
	protected function pagesArchiveAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("pages" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, $limit, 4, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","pagesArchive", $data);
	}
	
	protected function pagesBinAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("pages" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, $limit, 5, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","pagesBin", $data);
	}
	
	
	// Artigos
	protected function articlesAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("articles" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $limit, 1, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","articles", $data);
	}
	
	protected function articlesNewAction($params = null) {
		$this->loginHandler();
		
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a criação de nova página standard
		if ($inPost === false) {	
			$possibleStatuses = $this->CMSBackEndModel->getPossibleStatusForPublicationsByUser($_SESSION['userId']);
			$possibleStatuses = $this->stringTranslator($possibleStatuses,$this->publicationStatusTranslation,"name");
			$this->loadFullPage("CMS/CMSBackEnd/","articlesNew", array("possibleStatuses" => $possibleStatuses));
			return true;
		}
		
		// Verificar se todos os campos estão preenchidos
		if (empty($inPost->title) || empty($inPost->content)) {
			$this->errorAction(array("errorid" => "11", "errormsg" => "O preenchimento do título e conteúdo do artigo são obrigatórios!"));
			return false;
		}
		
		// Adicionar o tipo à publicação
		$inPost->type = 2;
		
		// Processar a criação da publicação
		$destination = $this->newPublicationProcess($inPost);
		if ($destination === false)
			return $this->errorAction(array("errorid" => "12", "errormsg" => "Impossível inserir artigo!"));
		
		$this->redir("/CMS/CMSBackEnd/{$destination}");		
		
	}
	
	protected function articlesEditAction($params) {
		$this->loginHandler();
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a editação da página...
		if ($inPost === false) {	
			if (empty($params[0]))
				return $this->errorAction(array("errorid" => "13", "errormsg" => "É necessário especificar um artigo a editar."));
			
			$article = $this->CMSBackEndModel->CMSModel->getPublication((int)$params[0]);
			$this->loadFullPage("CMS/CMSBackEnd/","articlesEdit", array("article" => $article));
		}
		
		// Guardar alterações
		if (!$this->CMSBackEndModel->setPublicationContent($inPost->articleId, $inPost->title, $inPost->content, $inPost->comments_open))
			return $this->errorAction(array("errorid" => "14", "errormsg" => "Impossível actualizar publicação."));
		
		$this->redir("/CMS/CMSBackEnd/articles");
	}
	
	protected function articlesDraftsAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("articles" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $limit, 2, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","articlesDrafts", $data);
	}
	
	protected function articlesModerateAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("articles" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $limit, 3, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","articlesModerate", $data);
	}
	
	protected function articlesArchiveAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("articles" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $limit, 4, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","articlesArchive", $data);
	}
	
	protected function articlesBinAction($params = null) {
		$this->loginHandler();
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("articles" => $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $limit, 5, null, true), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","articlesBin", $data);
	}
	
	// Comentários
	protected function commentsAction() {
		$this->loginHandler();
	
		$data = $this->commentsGetByPublicationProcess(1);		
		$data = array("comments" => $data);
		$this->loadFullPage("CMS/CMSBackEnd/","comments", $data);
	}
	
	protected function commentsEditAction($params) {
		$this->loginHandler();
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a editação da página...
		if ($inPost === false) {	
			if (empty($params[0]))
				return $this->errorAction(array("errorid" => "13", "errormsg" => "É necessário especificar um comentário a editar."));
			
			$comment = $this->CMSBackEndModel->CMSModel->getPublication((int)$params[0]);
			$this->loadFullPage("CMS/CMSBackEnd/","commentsEdit", array("comment" => $comment));
		}
		
		// Guardar alterações
		if (!$this->CMSBackEndModel->setPublicationContent($inPost->commentId, null, $inPost->content))
			return $this->errorAction(array("errorid" => "14", "errormsg" => "Impossível actualizar publicação."));
		
		$this->redir("/CMS/CMSBackEnd/comments");
	}
		
	protected function commentsModerateAction() {
		$this->loginHandler();
	
		$data = $this->commentsGetByPublicationProcess(3);
		$data = array("comments" => $data);		
		$this->loadFullPage("CMS/CMSBackEnd/","commentsModerate", $data);
	
	}
	
	protected function commentsBinAction() {
		$this->loginHandler();
		
		$data = $this->commentsGetByPublicationProcess(5);
		$data = array("comments" => $data);		
		$this->loadFullPage("CMS/CMSBackEnd/","commentsBin", $data);
	
	}
	
	// Obter comentários num dado estado divididos por publicações
	protected function commentsGetByPublicationProcess($status) {
		
		/*
			1. Listar os artigos com comentários
			2. Obter os comentários para cada artigo
			3. Devolver objecto de artigo com sub-objectos de comentários
		*/

		$data = "";
		$articles = $this->CMSBackEndModel->CMSModel->getMultiplePublications(2, $this->pubPeerPage, 1);
				
		foreach ($articles as $article) {
			$comments = $this->CMSBackEndModel->CMSModel->getMultiplePublications(3, $this->pubPeerPage, $status, $article->id, true);
			if (!$comments) continue;
			$data[] = (object)array(
				"title" => $article->title,
				"link" => "http://test/",
				"comments" => $comments);
			}
		
		return $data;
	}
	
	// Media
	protected function filesAction($params = null) {
		$this->loginHandler();
		
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->pubPeerPage - 10), ((int)$params[0] * $this->pubPeerPage));
		$data = array("files" => $this->CMSBackEndModel->listStoredObjects($limit, null, $this->publicUploadFolder), "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSBackEnd/","files", $data);
	}
	
	
	protected function filesUploadAction($params = null) {
		$this->loginHandler();
				
		if (empty($_FILES["file"])) {	
			$this->loadFullPage("CMS/CMSBackEnd/","filesUpload");
		}
				
		$denyExts = array("php", "html", "js"); // todo
		$extension = end(explode(".", $_FILES["file"]["name"]));
		
		// Verificar se a extensão é autorizada e o tamanho de upload é inferior ao permitido
		if (($_FILES["file"]["size"] > $this->maxUploadSize) && in_array($extension, $denyExts))
			return $this->errorAction(array("errorid" => "38", "errormsg" => "O ficheiro escolhido não é autorizado no sistema."));
		  
		// Verificar por erros
		if ($_FILES["file"]["error"] > 0)
			return $this->errorAction(array("errorid" => "37", "errormsg" => "Erro ao receber o ficheiro. Tente novamente!"));
		
		// Nomear o ficheiro e mover para a pasta de upload...
		$uploadDate = date("Y-m-d H:i:s");
		$fileName = date("YmdH").Utility::entropyGenerator(5);
		move_uploaded_file($_FILES["file"]["tmp_name"], $this->internalUploadFolder.$fileName.".".$extension);
		
		// Gardar na base de dados
		$this->CMSBackEndModel->CMSModel->Model->insert("storage", array("date" => $uploadDate, "storage" => $fileName.".".$extension, "name" => $fileName, "size" => ($_FILES["file"]["size"] / 1024)));
		
		$this->redir("/CMSBackEnd/files/");		
	}
	
	protected function filesDeleteAction($params) {
		
		if (is_null($params) || empty($params[0]))
			return $this->errorAction(array("errorid" => "15", "errormsg" => "É necessário específicar qual o ficheiro a apagar."));
		
		// Obter nome do ficheiro em disco
		$fileName = $this->CMSBackEndModel->CMSModel->Model->selectOne("storage", array("id" => (int)$params[0]), "storage");
		$theFile = $this->internalUploadFolder.$fileName;
			
		// Remover o ficheiro	
		if (!unlink($theFile))
			return $this->errorAction(array("errorid" => "16", "errormsg" => "Impossível apagar ficheiro."));
			
		// Apagar na db
		if (!$this->CMSBackEndModel->CMSModel->Model->delete("storage", array("id" => (int)$params[0])))
			return $this->errorAction(array("errorid" => "17", "errormsg" => "Impossível remover referência do ficheiro."));
				
		$this->redir("/CMSBackEnd/files/");
	}
	
	protected function usersAction() {
		$this->loginHandler();
		$users = $this->CMSBackEndModel->userList();
		$possibleStatuses = (object)array("Desabilitado", "Habilitado");	
		$possibleRoles = $this->CMSBackEndModel->selectTable("role");
		$data = array("users" => $users, "possibleStatuses" => $possibleStatuses, "possibleRoles" => $possibleRoles);
		$this->loadFullPage("CMS/CMSBackEnd/","users", $data);
	}
	
	protected function usersProfileAction($prams, $data = null) {
		$this->loginHandler();
		
		$inPost = Requests::postHandler($_POST);
		
		if ($inPost === false) {		
			$currentUser = $this->CMSBackEndModel->userGetInfo($_SESSION['userId'], array("name", "username", "email"));
			$data["user"] = $currentUser;
			$this->loadFullPage("CMS/CMSBackEnd/", "usersProfile", $data);
		}
		
		if (array_key_exists("personalData", (array)$inPost)) {
						
			if (empty($inPost->passwd) || empty($inPost->name) || empty($inPost->username) || empty($inPost->email)) {
				$data = array("error" => "Impossível dados pessoais verifique se preencheu todos os campos.");
				$_POST = null;
				$this->usersProfileAction(null, $data);
				return false;
			}
			
			// Verificar se a password está correcta
			if (!$this->CMSBackEndModel->CMSModel->Model->userCheckPasswd($_SESSION['userId'], $inPost->passwd)) {
				$data = array("error" => "A palavra-chave introduzida para verificar as alterações está incorrecta.");
				$_POST = null;
				$this->usersProfileAction(null, $data);
				return false;
			}
				
			// Verificar se o novo username ou email já existem na base de dados (não contado com o user actual)
			if (!$this->CMSBackEndModel->isUserDataUnique($_SESSION['userId'], $inPost->username, $inPost->email)) {
				$data = array("error" => "O nome de utilizador ou email pretendidos já estão registados no sistema.");
				$_POST = null;
				$this->usersProfileAction(null, $data);
				return false;
			}
							
			// Guardar os novos dados	
			$newUserInfo = array(
				"name" => $inPost->name,
				"username" => $inPost->username,
				"email" => $inPost->email
			);
			
			if (!$this->CMSBackEndModel->userSetInfo($_SESSION['userId'], $newUserInfo))
				$this->errorAction(array("errorid" => "66", "errormsg" => "Impossível actualizar dados o utilizador."));
			
			$this->redir("/CMS/CMSBackEnd/usersProfile");
		}
		
		if (array_key_exists("passwdChange", (array)$inPost)) {
			
			if (empty($inPost->passwd) || empty($inPost->passwdConf)) {
				$data = array("error" => "Impossível alterar palavra-chave, verifique se preencheu todos os campos.");
				$_POST = null;
				$this->usersProfileAction(null, $data);
				return false;
			}
			
			// Verificar se a password coincide com a confirmação
			if ((string)$inPost->passwd !== (string)$inPost->passwdConf) {
				$data = array("error" => "A palavra-chave não coincide com a confirmação.");
				$_POST = null;
				$this->usersProfileAction(null, $data);
				return false;
			}
			
			// Verificar a segurança da password
			if (!preg_match("((?=.*\d)(?=.*[a-z]).{8,20})", $inPost->passwd)) {
			   $data = array("error" => "A palavra-chave escolhida é insegura!");
			   $_POST = null;
			   $this->usersProfileAction(null, $data);
			   return false;
			}
			
			$newUserInfo = array("passwd_hash" => hash("sha256", $inPost->passwd));
			
			// Definir a nova password
			if (!$this->CMSBackEndModel->userSetInfo($_SESSION['userId'], $newUserInfo))
				$this->errorAction(array("errorid" => "66", "errormsg" => "Impossível actualizar dados o utilizador."));
			
			$this->redir("/CMS/CMSBackEnd/usersProfile");
		}
		
		$this->errorAction(array("errorid" => "67", "errormsg" => "Parâmetro desconhecido requisitado."));	
	}
	
	// Pseudo-Accções sobre os utilizadores // 
	protected function usersApproveAction($params) {
		$this->loginHandler();
		
		if ((int)$params[0] == $_SESSION['userId'] || empty($params[0]) )
			$this->redir("/CMS/CMSBackEnd/usersWaiting");
		
		// Alterar o estado
		$newInfo = array("status" => $this->userStatusAfterApproval);
		if (!$this->CMSBackEndModel->userSetInfo((int)$params[0], $newInfo))
			$this->errorAction(array("errorid" => "66", "errormsg" => "Impossível actualizar dados o utilizador."));
			
		$this->redir("/CMS/CMSBackEnd/usersWaiting");
	}
	
	
	protected function usersChangeSettingsAction($params) {
		$this->loginHandler();
		
		if ((int)$params[0] == $_SESSION['userId'] || !isset($params[0]) || !isset($params[1]) || !isset($params[2]))
			$this->redir("/CMS/CMSBackEnd/users");
		
		// Guardar alterações
		$this->CMSBackEndModel->userSetInfo((int)$params[0],array("id_role" => (int)$params[1], "status" => (int)$params[2]));
		$this->redir("/CMS/CMSBackEnd/users");
	}
	
	protected function usersDeleteAction($params) {
		$this->loginHandler();
		
		if ((int)$params[0] == $_SESSION['userId'] || empty($params[0]))
			$this->redir("/CMS/CMSBackEnd/users");
			
		// Apagar utilizador
		if (!$this->CMSBackEndModel->userDelete((int)$params[0]))
			$this->errorAction(array("errorid" => "34", "errormsg" => "Impossível remover utilizador"));
			
		$this->redir("/CMS/CMSBackEnd/users");
	}
	
	protected function usersPasswdResetAction($params) {
		$this->loginHandler();
		
		if ((int)$params[0] == $_SESSION['userId'] || empty($params[0]))
			$this->redir("/CMS/CMSBackEnd/users");
			
		// Apagar utilizador
		if (!$this->CMSBackEndModel->userPasswdReset((int)$params[0]))
			$this->errorAction(array("errorid" => "35", "errormsg" => "Impossível redefinir a password do utilizador."));
			
		$this->redir("/CMS/CMSBackEnd/users");
	}
	
	// Registo de utilizadores
	protected function usersRegisterAction($params) {
		
		$inPost = Requests::postHandler($_POST);
		
		if ($inPost === false)
			$this->loadFullPage("CMS/CMSBackEnd/","register");
		
		// Verificar dados
		if (empty($inPost->name) || empty($inPost->username) || empty($inPost->email) || empty($inPost->passwd) || empty($inPost->passwdConf)) {
			$data = array("error" => "Preenchimento incorrecto dos dados!");
			$this->loadFullPage("CMS/CMSBackEnd/","register",$data);
		}
		
		if ( $inPost->passwd !==  $inPost->passwdConf) {
			$data = array("error" => "A palavra-chave não coincide com a confirmação.");
			$this->loadFullPage("CMS/CMSBackEnd/","register",$data);
		}
		
		$user = array(
			"name" => $inPost->name,
			"username" => $inPost->username,
			"passwd" => $inPost->passwd,
			"email" => $inPost->email,
			"id_role" => $this->defaultRole,
			"status" => $this->defaultRegStatus
		);
		
		// Registar o utilizador
		$regStatus = $this->CMSBackEndModel->CMSModel->Model->userRegister((object)$user);
		
		$data = array();
					
		switch ((int)$regStatus) {
			case '0': {
				$data['error'] = "O email já está registado.";
				break;
			}	
			case '1': {
				$data['error'] = "O email introduzido é inválido!";
				break;
			}
			case '2': {
				$data['error'] = "O username indicado já está registado no sistema.";
				break;
			}	
			case '3': {
				$data['error'] = "A palavra-chave introduzida é pouco segura!";
				break;
			}
			case '4': {
				$data['error'] = "Impossível registar o utilizador.";
				break;
			}
			case '5': {
				$this->redir("/CMS/CMSBackEnd/");
				break;
			}	 
		}
		
		$this->loadFullPage("CMS/CMSBackEnd/", "register", $data);
	}
	
	protected function usersWaitingAction($params) {
		$this->loginHandler();
		// SELECT * FROM user WHERE status = '0' AND username != ''
		$pendingUsers = $this->CMSBackEndModel->userList(true);
		$data = array("peding" => $pendingUsers);
		$this->loadFullPage("CMS/CMSBackEnd/", "usersWaiting", $data);
	}
	
	protected function settingsAction() {
		$this->loginHandler();
		$this->redir("/CMS/CMSBackEnd/settingsGlobal/");
	}
	
	protected function settingsGlobalAction() {
		$this->loginHandler();
		
		$inPost = Requests::postHandler($_POST);
		
		// Se não exister $inPost mostra a criação de nova página standard
		if (!$inPost) {
			
			// Obter os valores actualmente guardados
			$dbOptions = $this->CMSBackEndModel->selectTable("wfoption", array("name", "value"));
			$options;
			foreach ($dbOptions as $key => $value)
				$options[$value->name] = $value->value;
			
			$data = array("options" => (object)$options);
			
			// Obter os estados possíveis para as publicações
			$possibleStatuses = $this->CMSBackEndModel->selectTable("status", array("id","name"));
			$data["possibleStatuses"] = (object)$this->stringTranslator($possibleStatuses,$this->publicationStatusTranslation,"name");	
			
			// Obter todas as páginas possíveis para a home
			$data["possiblePages"] = $this->CMSBackEndModel->CMSModel->getMultiplePublications(1, 100, 1, null, true);
					
			$this->loadFullPage("CMS/CMSBackEnd/","settingsGlobal", $data);
			return true;
		}
		
		$newOptions = array(
			"pubPeerPage" => (int)$inPost->pubPeerPage,
			"defaultPublicationStatus" => (int)$inPost->defaultPublicationStatus,
			"userStatusAfterApproval" => (int)$inPost->userStatusAfterApproval,
			"stdPasswdLen" => (int)$inPost->stdPasswdLen,
			"maxUploadSize" => $inPost->maxUploadSize,
			"maxUploadSize" => $inPost->maxUploadSize,
			"webfeelsEmail" => $inPost->webfeelsEmail,
			"sitename" => $inPost->sitename,
			"initPage" => (int)$inPost->initPage,
			"defaultCommentStatus" => $inPost->defaultCommentStatus,
			"articlesPeerPage" => (int)$inPost->articlesPeerPage,
			"defaultRegStatus" => (int)$inPost->defaultRegStatus
		);
		
		foreach ($newOptions as $name => $value) {
			$this->CMSBackEndModel->CMSModel->Model->update("wfoption", array("value" => $value), array("name" => $name));
		}
		
		$this->redir("/CMS/CMSBackEnd/settingsGlobal/");
	}
	
	protected function settingsPermissionsAction() {
		$this->loginHandler();
		$inPost = Requests::postHandler($_POST);
		
		// Se existir post processar as novas permissões.
		if ($inPost !== false) {
		
			// Actualizar permissões...			
			$sysPermission = array();
			foreach ($inPost as $key => $value) {
				$jsPermssion = explode("-", $key);
				$sysPermission[] = ($jsPermssion[0]) . "-" . ($jsPermssion[1]);
			}
			
			// Actualizar as permissões
			$this->CMSBackEndModel->CMSModel->Model->updatePermissions($sysPermission);
		}
				
		$actionsObj = $this->CMSBackEndModel->CMSModel->Model->select("feature");
		$possibleActions = array();
		while ( $action = $actionsObj->fetch_object() )
		   		$possibleActions[] = $action->description;
		
		// Obter os roles
		$rolesObj = $this->CMSBackEndModel->CMSModel->Model->select("role");
		$roles = array(); 
		$rolesObj->fetch_object(); // Descartar o role dos Administradores, não deve poder ser editado por segurança!
		while ( $role = $rolesObj->fetch_object() )
		   		$roles[] = $role->name;
		
		// Dados... 
		$permissionTable = $this->CMSBackEndModel->selectTable("role_feature");		
		
		$defPermissions = array();
		foreach ($permissionTable as $key => $perm) {
			$defPermissions[] = ($perm->id_role) . "-" . ($perm->id_feature); // Converter para permissões de JS!
		}
		
		$data = array(
			"perms"	=>	$possibleActions,
			"roles"	=>	$roles,
			"defPermissions" => $defPermissions
		);
		
		$this->loadFullPage("CMS/CMSBackEnd/","settingsPermissions", $data);
		return true;
		
		
	}
	
	
	protected function errorAction($params = null) {
		$this->loginHandler();
		$this->loadFullPage("CMS/CMSBackEnd/","error",$params);
	}
	
	
	/* Estado dos Conteúdos */
	protected function publicationToStatus1Action($params = null) { // Publicado
		$this->loginHandler();
		$this->publicationToStatusProcess($params[0],1);
	}
	
	protected function publicationToStatus2Action($params = null) { // Rascunho
		$this->loginHandler();
		$this->publicationToStatusProcess($params[0],2);
	}
	
	protected function publicationToStatus3Action($params = null) { // Pendente
		$this->loginHandler();
		$this->publicationToStatusProcess($params[0],3);
	}
	
	protected function publicationToStatus4Action($params = null) { // Arquivo
		$this->loginHandler();
		$this->publicationToStatusProcess($params[0],4);
	}
	
	protected function publicationToStatus5Action($params = null) { // Lixo
		$this->loginHandler();
		$this->publicationToStatusProcess($params[0],5);
	}
	
	protected function publicationToStatusProcess($publicationID, $status) {
		// Definir o estado da publicação
		$statResult = $this->CMSBackEndModel->setPublicationStatus($publicationID, $status);
		
		if ($statResult === false) {
			$this->errorAction(array("errorid" => "26", "errormsg" => "Não foi possível actualizar o estado da publicação."));
			return false;
		}
		
		// Redirecionar para a lista de conteúdos adequada
		$destination = substr($statResult, 3) . "s";
		$this->redir("/CMS/CMSBackEnd/{$destination}");
	}

	/* Esvaziar lixo */
	protected function emptyBinAction($params) {
		$this->loginHandler();
		$deleteResult = $this->CMSBackEndModel->deletePublications($params[0], 5);
		
		if ($deleteResult === false) {
			$this->errorAction(array("errorid" => "27", "errormsg" => "Não foi possível limpar o lixo!"));
			return false;
		}
		
		// Redirecionar para o lixo adequado // pageBinAction
		$destination = substr($deleteResult, 3) . "sBin";
		$this->redir("/CMS/CMSBackEnd/{$destination}");		
	}

}

?>
