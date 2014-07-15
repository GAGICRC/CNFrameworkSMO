<?php

require_once "appConfig.php";

require_once "STDClass/Requests.php";
require_once "STDClass/FileSystem.php";
require_once "STDClass/Utility.php";
require_once "STDClass/markdown.php";

require_once "Model.php";

class Controller {

	protected static $instance = NULL;

	private $Model;
	
	protected static $appCoreDir;
	protected static $publicFolder;
	protected $defaultModule;
	protected static $siteURL;
	
	private function __construct() {
		static::$appCoreDir = __DIR__;
		static::$publicFolder = "Public";
		$this->defaultModule = "CMS";
		
		$this->Model = Model::getInstance();
		
		// Tentar incializar a ligação à base de dados
		$dbInitResult = $this->Model->run();
		
		// Não é possível aceder à base de dados ou a estrutura é incorrecta, corrigir...
		if ($dbInitResult !== true) {
			// Chamar a interface de setup();
			$this->Model->dbSetup($dbInitResult);
		}
		
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	public function run() {
		
		/* Processamento da Query de Entrada:
		
			Ex. q?= [ M / A / p1 / ...pn ]
			
			M-> Módulo
			A-> Acção
			p-> Parametro
		*/
		
		$inQuery = Requests::RequestHandler($_GET);
		
		if (isset($inQuery->s))
			static::$siteURL = $inQuery->s;
		
		if (isset($inQuery->q))
			$params = explode("/", $inQuery->q);
				
		// Listar módulos disponíveis
		$availableModules = FileSystem::listSubFolders("Modules");
		
		// Tentar routear o pedido...
		$moduleName = $this->route($params, $availableModules, $this->defaultModule);
		
		// Carregar a classe do módulo necessário!
		require_once "Modules/{$moduleName}/{$moduleName}Controller.php";
		
		// Instanciar o módulo.	
		$moduleName = $moduleName."Controller";
		//$module = new $moduleName();
		$module = $moduleName::getInstance();		
		
		$module->run($params);
	
	}
	
	/* ----------------------------------- Controller Methods ----------------------------------- */
	
	//  Routeia um parametro para o Controller/Acção apropriado(a)
	protected function route(&$params, $availableRoutes, $defaultRoute) {
		
		/*
			1. Verficiar se existe algum parâmetro definido!
			2. Verificar se a rota pedida existe:
				(true) 	=> Remover o nome da rota do Array de parâmetros ($params);
				(false) => Retornar a rota default ($defaultRoute);
			3. Devolver o nome da rota;
		*/
		
		if (empty($params[0]))
			return $defaultRoute;
		
		if (in_array($params[0], $availableRoutes, true)) {
		 	$routeName = $params[0];
		 	array_shift($params);
		} else {
		 	$routeName = $defaultRoute;
		}	
		
		return $routeName;
		
	}
	
	protected function loadSingleView($path, $view, $data = null) {
		/*
			1. Extrair os dados de entrada;
			2. Verificar se a vista existe como Módulo;
				(true) => Carregar como vista de módulo;
				(false) => Carregar vista global;
		*/
	
		$public = static::$publicFolder;
	
		if(is_array($data))
			extract($data);
			
		if ( file_exists(static::$appCoreDir . "/../{$public}/Views/Modules/{$path}/{$view}.php") )
			require static::$appCoreDir . "/../{$public}/Views/Modules/{$path}/{$view}.php";
		else
			require static::$appCoreDir . "/../{$public}/Views/Globals/{$view}.php";	
		
	}
	    
	protected function loadFullPage($path, $contentView, $data = null) {
		
			// Vista Cabeçalho
	        $this->loadSingleView($path,"header",$data);
	        
	        /*if(!empty($flash))
	            $flash->display();*/
	        
	        // Vista de Conteúdo
	        $this->loadSingleView($path, $contentView, $data);
	        
	        // Vista de Rodapé
	        $this->loadSingleView($path,"footer", $data);
	        
	        exit;
    }
		
		
	protected function redir($url) {
		header("Location: {$url}");
		exit;
	}
	
	
	public function listActions($theClass) {
	 
		$exeptions = array( // Todos os metodos que não devem ser listados
			"listActions",
			"logoutAction",
			"registerActionMethods",
			"usersRegisterAction"
		);
		
		$methods = get_class_methods($theClass);

		$actions = array();
		foreach ($methods as $method) {
			if (strstr($method, "Action") && !in_array($method, $exeptions))
				$actions[] = $method;
		}
		
		return $actions;
	}
	
	
}

?>