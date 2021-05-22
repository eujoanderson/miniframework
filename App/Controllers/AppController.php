<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action; // Tranzendo o namespace do diretorio informado
use MF\Model\Container;


class AppController extends Action{

  public function timeline(){

    $this->validacaoAutenticacao();

    //recuperação dos tweets
      $tweet = Container::getModel('Tweet');
      
      $tweet->__set('id_usuario', $_SESSION['id']);

      //$tweets = $tweet->getAll();


      //PAGINÇâo
        $total_registro_pagina = 7;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registro_pagina;

        /*Paginação*/ 
        $tweets = $tweet->getPorPagina($total_registro_pagina, $deslocamento );

        /*Total tweets*/ 
        $total_tweets = $tweet->getTotalRegistros();
        
        $this->view->total_de_paginas = ceil($total_tweets['total'] / $total_registro_pagina);
        $this->view->pagina_ativa = $pagina;
      //


      $this->view->tweet = $tweets;

      //Instancia do usuario para aprensentar os dados do banco de seguidores, tweets, seguindo e nome
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_usuario_seguindo = $usuario->getTotalUsuarioSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
      //
 
      $this->render('timeline');
    
  }

  public function tweet(){
    
    $this->validacaoAutenticacao();

    //logica
      $tweet = Container::getModel('Tweet');
      $tweet->__set('tweet', $_POST['tweet']);
      $tweet->__set('id_usuario', $_SESSION['id']);

      $tweet->salvar();

      header('Location: /timeline');
    

  }


  public function validacaoAutenticacao(){
    session_start();
    if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
      header('Location: /?login=erro');
    }
  }

  public function quemSeguir(){

    $this->validacaoAutenticacao();

    //Instancia do usuario para aprensentar os dados do banco de seguidores, tweets, seguindo e nome
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->info = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_usuario_seguindo = $usuario->getTotalUsuarioSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
    //
    

    //
      $pesquisar = isset($_GET['pesquisar']) ? $_GET['pesquisar'] : '';
    //
      $usuarios = array();
    //

    

    if($pesquisar != ''){
      $usuario->__set('nome', $pesquisar);
      $usuario->__set('pesquisar', $pesquisar);
      $usuario->__set('id', $_SESSION['id']);

      $usuarios = $usuario->getAll();
    }

    $this->view->usuarios = $usuarios;

    $this->render('quemSeguir');
  }//Onde pesquisar para seguir



  public function acao(){

    $this->validacaoAutenticacao();
    
    //acao
    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

    //id_usuario
    $usuario = Container::getModel('Usuario');
    $usuario->__set('id', $_SESSION['id']);

    if($acao == 'seguir'){
        // 
            $usuario->seguirUsuario($id_usuario_seguindo);
        //
    }
    else if($acao == 'deixar_de_seguir'){

        $usuario->deixarSeguirUsuario($id_usuario_seguindo);

    }

    header("Location: /quem_seguir");
      
  }

  public function remover(){

    $this->validacaoAutenticacao();

    $id = isset($_GET['id']) ? $_GET['id'] : '';

    //acao
    $usuario = Container::getModel('Usuario');
    $usuario->__set('id_usuario_autenticado', $_SESSION['id']);

    if($id != ''){
      $usuario->__set('id', $id);
      $usuarios = $usuario->remover();
    }
     
    header('Location: /timeline');

  }


}