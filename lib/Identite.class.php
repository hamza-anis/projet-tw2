<?php
class Identite {
  public $pseudo;
  public $nom;
  public $prenom;
  public function __construct($pseudo,$nom,$prenom)
  {
    $this->pseudo = $pseudo;
    $this->nom = $nom;
    $this->prenom = $prenom;
  }
  function getPseudo(){
    return $this->pseudo;
  }
}
?>
