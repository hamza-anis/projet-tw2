<?php
class ArgSetJoinEvent extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('event');
}
}
?>
