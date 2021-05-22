<?php

namespace MF\Model;

abstract class Model{

  protected $banco;

  public function __construct(\PDO $banco){
    $this->banco = $banco;
  }
}

?>