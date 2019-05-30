<?php
class ArgumentSetRecherche extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('recherche');
    $this->defineNonEmptyString('tri');
  }
}
?>
