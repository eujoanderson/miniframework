<?php

namespace MF\Model;

use App\Connection;

class Container{

  public static function getModel($modelo){

    $classe = "\\App\\Models\\".ucfirst($modelo);

    //CRIAR UMA INSTANCIA DO PDO 
    $conexao = Connection::getBanco();

    return new $classe($conexao);
    
  }
 

}

?>