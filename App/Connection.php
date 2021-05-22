<?php

namespace App;

class Connection{

  public static function getBanco(){
    try{
      $conn = new \PDO("mysql:host=localhost;dbname=twitter_clone;chaset=utf8","root", "");

      return $conn;
      
    }
    catch(\PDOExecption $e){
      echo $e->getMessage();
    }
  }
}

?>