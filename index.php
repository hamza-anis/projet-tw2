<?php
spl_autoload_register(function ($className) {
    include ("lib/{$className}.class.php");
});
  session_start();
  date_default_timezone_set ('Europe/Paris');
  try{
    $data = new DataLayer();
    $events = $data->getAllEvents();
    $cats = $data->getCategories();
    if(isset($_SESSION['ident'])){
        $personne = $_SESSION['ident'];
        $dataPersonne = json_encode($personne);
        $data = json_decode($dataPersonne,true);
        $pseudo = $data["pseudo"];
        $data = new DataLayer();
        $abonnements = $data->getAbonnements($pseudo);
        $mesEvents = $data->findMesEvenements($pseudo);
        require("views/pageComplet.php");
    }else{
        require('views/index.php');
    }
}catch (PDOException $e){
  $errorMessage = $e->getMessage();
  require("views/pageErreur.php");
}
?>
