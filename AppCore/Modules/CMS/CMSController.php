<?php

require_once __DIR__."/../../Controller.php";
require_once "CMSModel.php";

class CMSController extends Controller {
		
	protected static $instance = NULL;
	protected $defaultInterface;

	private $CMSModel;
	
	private function __construct() {
		$this->defaultInterface = "CMSFrontEnd";
		$this->CMSModel = CMSModel::getInstance();	
	}
	
	public static function getInstance() {
	    if(!isset(self::$instance))
	      self::$instance = new self();
		return self::$instance;
	}
	
	public function run($params = null) {
		
		$availableInterfaces = FileSystem::listSubFolders("Modules/CMS");
		$interfaceName = $this->route($params, $availableInterfaces, $this->defaultInterface);
		
		require_once "{$interfaceName}/{$interfaceName}Controller.php";
				
		$interfaceName = $interfaceName."Controller";
		$interface = $interfaceName::getInstance();
		$interface->run($params);
				
	}
	
	/* ----------------------------------- Controller Methods ----------------------------------- */	

}

?>