<?php

namespace MF\Controller;

abstract class Action{
  protected $view; //global

	public function __construct(){
		$this->view = new \stdClass(); //Instacia para o privete view criado no construct
	}

  
	protected function render($view, $layout = 'layout'){
    $this->view->page = $view;
    
    //Teste para ver se o Layout passado por parametro existe 
      if(file_exists("../App/Views/".$layout.".phtml")){
        require_once "../App/Views/".$layout.".phtml";
      }
      else{
        $this->content();
      }
    //

	}

  protected function content(){
    
		$classe = get_class($this);
		$classe = str_replace('App\\Controllers\\', '', $classe);
		
		$caminho = strtolower(str_replace('Controller', '', $classe));
	
		require_once "../App/Views/".$caminho."/".$this->view->page.".phtml";
  }

}
?>