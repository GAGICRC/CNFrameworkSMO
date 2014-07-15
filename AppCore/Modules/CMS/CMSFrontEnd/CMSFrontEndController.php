<?php

require_once __DIR__."/../CMSController.php";
require_once "CMSFrontEndModel.php";

class CMSFrontEndController extends CMSController {
	
	protected static $instance;
	
	private $CMSFrontEndModel;	
	protected $articlesPeerPage;
	protected $initPage;
	protected $defaultCommentStatus;
	
	private function __construct() {
		$this->CMSFrontEndModel = CMSFrontEndModel::getInstance();	
		$this->articlesPeerPage = $this->CMSFrontEndModel->CMSModel->Model->defineOption("articlesPeerPage");
		$this->initPage = $this->CMSFrontEndModel->CMSModel->Model->defineOption("initPage");
		$this->defaultCommentStatus = $this->CMSFrontEndModel->CMSModel->Model->defineOption("defaultCommentStatus");
	}

	public function run($params = null) {
						
			// Accção padrão se não for passado nunhum pedido
			if (empty($params[0])) {
				// Carregar a página default
				$this->pageAction(array($this->initPage));
				return;
			}
			
			// Implementar aqui o router();
			$method = $params[0]."Action";
			array_shift($params);
						
			if (method_exists($this,$method))
				$this->$method($params);
			else
				$this->errorAction(array("errorid" => "404", "errormsg" => "Página não encontrada!"));
			
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	protected function loadFullPage($path, $contentView, $data = null) {
		
			// Vista Cabeçalho
			$data['wfname'] = $this->CMSFrontEndModel->CMSModel->Model->defineOption("sitename");
			$data['navMenu'] = $this->CMSFrontEndModel->getPageMenu();
	        $this->loadSingleView($path,"header",$data);
	        
	        // Vista de Conteúdo
	        $this->loadSingleView($path, $contentView, $data);
	        
	        // Vista de Rodapé
	        $this->loadSingleView($path,"footer", $data);
	        
	        exit;
	}
	
	/* ----------------------------------- Controller Methods ----------------------------------- */
	
	protected function pageAction($params = null) {
		$result = $this->CMSFrontEndModel->CMSModel->getPublication($params[0]);
		if ($result === false)
			return $this->errorAction(array("errorid" => "404", "errormsg" => "Página não encontrada!"));
		
		if ($this->initPage == (int)$params[0])
			$isHome = true;
		else
			$isHome = false;
			
		// Converter MarkDown para HTML
		$result->content = Markdown($result->content);	
		$this->loadFullPage("CMS/CMSFrontEnd/","page", array("page" => $result, "isHome" => $isHome));
	}
	
	protected function blogAction($params = null) {
		if (is_null($params) || empty($params[0]))
			$params = array(1);
		$limit = array(((int)$params[0] * $this->articlesPeerPage - 10), ((int)$params[0] * $this->articlesPeerPage));
		
		$articles = $this->CMSFrontEndModel->CMSModel->getMultiplePublications(2, $limit, 1, null, false);
					
		foreach ($articles as $key => $article) {
			$articles[$key]->author_name = $this->CMSFrontEndModel->CMSModel->Model->selectOne("user", array("id" => $article->id_user), "name");
			$articles[$key]->content = Markdown($articles[$key]->content);
		}
		
		
		$data = array("articles" => $articles, "currentPage" => (int)$params[0]);
		$this->loadFullPage("CMS/CMSFrontEnd/","blog", $data);
	}
	
	protected function articleAction($params = null) {
		if (is_null($params) || empty($params[0]))
			$this->redir("/CMS/CMSFrontEnd/blog/");
		
		$inPost = Requests::postHandler($_POST, true);
		
		// Publicação de comentários
		if ($inPost !== false) {	
			$result = $this->CMSFrontEndModel->insertComment((int)$params[0], $inPost->name, $inPost->email, $inPost->content);
			if ($result === false)
				return $this->errorAction(array("errorid" => "31", "errormsg" => "Não foi possível inserir o seu comentário!"));
			
			if ($this->defaultCommentStatus == 3)
				$statusMsg = "O seu comentário encontra-se pendente para moderação!";
		}
		
		// Obter artigo
		$result = $this->CMSFrontEndModel->CMSModel->getPublication((int)$params[0]);
		$result->content = Markdown($result->content);
		if ($result === false)
			return $this->errorAction(array("errorid" => "404", "errormsg" => "Página não encontrada!"));
		
		// Obter comentários
		$comments = $this->CMSFrontEndModel->CMSModel->getMultiplePublications(3, 100, 1, (int)$params[0], false);
		foreach ($comments as $key => $comment) {
			$comments[$key]->author_name = $this->CMSFrontEndModel->CMSModel->Model->selectOne("user", array("id" => $comment->id_user), "name");
			$comments[$key]->author_email = strtolower($this->CMSFrontEndModel->CMSModel->Model->selectOne("user", array("id" => $comment->id_user), "email"));
		}
		
		if (isset($statusMsg))
			$this->loadFullPage("CMS/CMSFrontEnd/","article", array("article" => $result, "comments" => $comments, "statusMsg" => $statusMsg));
		
		$this->loadFullPage("CMS/CMSFrontEnd/","article", array("article" => $result, "comments" => $comments));
	}
	
	protected function errorAction($params = null) {
		$this->loadFullPage("CMS/CMSFrontEnd/","error",$params);
	}

}

?>