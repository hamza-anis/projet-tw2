<?php
class ArgSetCreateEvenement extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('titre');
    $this->defineNonEmptyString('description');
    $this->defineNonEmptyString('categorie');
    $this->defineNonEmptyString('quand');
    $this->defineNonEmptyString('lieu');
  }
}
?>
