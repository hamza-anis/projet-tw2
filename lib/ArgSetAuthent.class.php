<?php
class ArgSetAuthent extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('pseudo');
    $this->defineNonEmptyString('password');
  }
}
?>
