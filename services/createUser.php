<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgSetCreateUser();

if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
  $data = new DataLayer();
  $data->createProfil($args->pseudo);
  $user = $data->createUser($args->nom,$args->prenom,$args->pseudo,$args->password);
  produceResult($user);
}
catch (PDOException $e){
  produceError($e->getMessage());
}


?>
