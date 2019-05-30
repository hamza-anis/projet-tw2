<?php
session_start();
session_name('projet');
set_include_path('..');
require_once('lib/common_service.php');


$args = new ArgSetAuthent();

if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
  $data = new DataLayer();
  $user = $data->authentifier($args->pseudo,$args->password);

  if($user != NULL){
    $_SESSION['ident'] = $user;
    produceResult($user);
  }else{
    produceError('Erreur');
  }
}
catch (PDOException $e){
  produceError($e->getMessage());
}


?>
