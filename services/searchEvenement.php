<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgumentSetRecherche();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
    $data = new DataLayer();
    $search = $data->searchEvenement($args->recherche,$args->tri);
    if ($search)
        produceResult($search);
    else
        produceError("Pas d'évenement {$args->recherche} trouvé.");
} catch (PDOException $e){
    produceError($e->getMessage());
}

?>
