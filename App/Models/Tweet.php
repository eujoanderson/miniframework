<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model{
  private $id;
  private $id_usuario;
  private $tweet;
  private $data;

  //modificar atributos
  public function __set($atributo, $valor){
    $this->$atributo = $valor;
  }

  //recuperar atributos
  public function __get($atributo){
    return $this->$atributo;
  }

  //salvar
  public function salvar(){
    $query =  "insert into tweets(tweet, id_usuario) values(:tweet, :id_usuario);";
    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':tweet', $this->__get('tweet') );
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario') );
    $stmt->execute();

    return $this;
  }

  //recuperar
  public function getAll(){
    $query = "
    select 
      t.id, 
      t.id_usuario, 
      u.nome, 
      t.tweet, 
      DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
    from 
      tweets as t
      left join usuario as u on (t.id_usuario = u.id)
    where 
      t.id_usuario = :id_usuario
      or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
    order by
      t.data desc
    ";

    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario') );
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //recuperar com paginação
  public function getPorPagina($limit, $offset){
    $query = "
    select 
      t.id, 
      t.id_usuario, 
      u.nome, 
      t.tweet, 
      DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
    from 
      tweets as t
      left join usuario as u on (t.id_usuario = u.id)
    where 
      t.id_usuario = :id_usuario
      or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
    order by
      t.data desc
    limit
      $limit
    offset
      $offset
    ";

    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario') );
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  //recuperar total de tweets
  public function getTotalRegistros(){
    $query = "
    select 
      count(*) as total
    from 
      tweets as t
      left join usuario as u on (t.id_usuario = u.id)
    where 
      t.id_usuario = :id_usuario
      or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
    ";

    $stmt = $this->banco->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id_usuario') );
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}
