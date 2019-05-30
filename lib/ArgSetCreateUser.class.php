<?php
class ArgSetCreateUser extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('pseudo');
    $this->defineNonEmptyString('prenom');
    $this->defineNonEmptyString('nom');
    $this->defineNonEmptyString('password');
  }
}
?>
