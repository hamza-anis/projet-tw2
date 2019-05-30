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

$args = new ArgSetCreateEvenement();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}
try{
  $data = new DataLayer();
  $ev = $data->createEvenement($pseudo,$args->titre,$args->categorie,$args->description,$args->lieu,$args->quand,date("Y-m-d H:i:s"));
  $data->incrementNbEventsCrees($pseudo);
  produceResult($ev);
}
catch (PDOException $e){
  produceError($e->getMessage());
}


?>
