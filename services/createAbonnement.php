<?php
    session_start();
    set_include_path('..');
    require_once('lib/common_service.php');

    $dataPersonne ='';    // si utilisateur non authentifié, data-personne n'est pas défini
    if (isset($_SESSION['ident'])){
        $personne = $_SESSION['ident'];
    }
    if (isset($personne)) // l'utilisateur est authentifié
      $dataPersonne = json_encode($personne);
      $data = json_decode($dataPersonne,true);
      $pseudo = $data["pseudo"];

    $args = new ArgSetCreateAbonnement();
    if (! $args->isValid()){
      produceError('argument(s) invalide(s)');
      return;
    }
    try{
      $data = new DataLayer();
      $ev = $data->createAbonnement($pseudo,$args->categorie,$args->motcle);
      produceResult($ev);
    }
    catch (PDOException $e){
      produceError($e->getMessage());
    }


?>
