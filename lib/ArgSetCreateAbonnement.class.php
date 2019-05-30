<?php
class ArgSetCreateAbonnement extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineString('categorie');
    $this->defineString('motcle');
  }
}
?>
