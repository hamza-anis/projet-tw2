<?php
session_start();
set_include_path('..');
require_once('lib/common_service.php');

  try{
  $dataPersonne ='';    // si utilisateur non authentifié, data-personne n'est pas défini
  if (isset($_SESSION['ident'])){
      $personne = $_SESSION['ident'];
      if (isset($_SERVER['HTTP_COOKIE'])) {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) {
          $parts = explode('=', $cookie);
          $name = trim($parts[0]);
          setcookie($name, '', time()-1000);
          setcookie($name, '', time()-1000, '/');
        }
      }
      session_destroy();
      produceResult("ok");
    }
}catch (PDOException $e){
  produceError($e->getMessage());
  }
?>
