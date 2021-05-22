<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{
  private $id;
  private $id_usuario_autenticado;
  private $nome;
  private $email;
  private $senha;
  private $pesquisar;

  public function __get($atributo){
    return $this->$atributo;
  }

  public function __set($atributo, $valor){
    $this->$atributo = $valor;
  }

  //salvar
  public function salvar(){
    $query =  "insert into usuario(nome,email, senha)values(:nome, :email, :senha)";

    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':nome', $this->__get('nome'));
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));

    $stmt->execute();

    return $this;
  }

  //validar 
  public function validarCadastro(){
    $validado = true;

    if( strlen($this->__get('nome') ) < 3){
      $validado = false;
    }

    if( strlen($this->__get('email') ) < 5){
      $validado = false;
    }

    if( strlen($this->__get('senha') ) < 3){
      $validado = false;
    }
    return $validado;
  }


  //recuperar um usuario por email

  public function getUsuarioPorEmail(){
    $query = "select nome,email from usuario where email = :email";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function autenticar(){
    $query = "select id, nome, email from usuario where email = :email and senha = :senha";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':email', $this->__get('email'));
    $stmt->bindValue(':senha', $this->__get('senha'));
    $stmt->execute();

    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

    if($usuario['id'] != '' && $usuario['nome'] != '' ){

      $this->__set('id', $usuario['id']);
      $this->__set('nome', $usuario['nome']);

    }

    return $this;
    
  }

  public function getAll(){
    $query = "select id,nome,email, (select count(*) from usuarios_seguidores as us where us.id_usuario = :id_usuario and us.id_usuario_seguindo = usuario.id) as seguindo_sn 
      from usuario 
    where nome like :nome and id != :id_usuario";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function seguirUsuario($id_usuario_seguindo){

    $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values(:id_usuario, :id_usuario_seguindo)";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo); 
    $stmt->execute();

    return true;
  }

  public function deixarSeguirUsuario($id_usuario_seguindo){
    
    $query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo); 
    $stmt->execute();

    return true;
  }

  public function remover(){

    $query = "delete from tweets where id = :id and id_usuario = :id_usuario_autenticado";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario_autenticado', $this->__get('id_usuario_autenticado'));
    $stmt->bindValue(':id', $this->__get('id'));
    $stmt->execute();

    return true;
  }
  




  //Informações do usuario
  public function getInfoUsuario(){

    $query = 'select nome from usuario where id = :id_usuario';
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id') );
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);

  }

  //total de tweets
  public function getTotalTweets(){

    $query = 'select count(*) as total_tweets from tweets where id_usuario = :id_usuario';
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);

  }

  //total de usuario que estamos seguindo
  public function getTotalUsuarioSeguindo(){

    $query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario';
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);

  }

  //total de seguidores
  public function getTotalSeguidores(){

    $query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);

  }
}
?>