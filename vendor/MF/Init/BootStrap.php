<?php

namespace MF\Init;

abstract class Bootstrap{
    
	protected $routes;

  abstract protected function initRoutes();   //contrato

	public function __construct() {
		$this->initRoutes();
		$this->run($this->getUrl());
	} //Recebe no contructor o array e a url passada pelo Cliente

	public function getRoutes() {
		return $this->routes;
	} //Retorna o atributo privado

	public function setRoutes(array $routes) {
		$this->routes = $routes;
	}//Seta o array dentro do atributo privado


	protected function run($url) {
		foreach ($this->getRoutes() as $key => $route) {
			if($url == $route['route']) {
				$classe = "App\\Controllers\\".ucfirst($route['controller']);//instancia de indexController.php

				$controller = new $classe;
				
				$action = $route['action'];

				$controller->$action();
			}
		}
	} //-|||percorrendo o array e seus respesctivos índices 
    //-||| criação de um operarador condicional onde $url == $url['route'] 
    //-||| instancia da class atráves dos namespaces

	protected function getUrl() {
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //retorna a URL parse_url(path)
	}//retorna a requisição feita através do GET da Página
    
}


?>