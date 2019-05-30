<?php
require_once("lib/db_parms.php");

Class DataLayer{
    private $connexion;
    public function __construct(){

            $this->connexion = new PDO(
                       DB_DSN, DB_USER, DB_PASSWORD,
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                       ]
                     );

    }
    /*
    * Ajoute un membre dans la table.
    */
    function createUser($nom,$prenom,$pseudo,$password){
      // Insertion
      $sql = <<<EOD
        INSERT INTO projet_users_identification(pseudo, password, nom, prenom)
        VALUES(:pseudo, :password, :nom, :prenom)
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':nom',$nom);
        $stmt->bindValue(':prenom',$prenom);
        $stmt->bindValue(':pseudo',$pseudo);
        $stmt->bindValue(':password',crypt($password));
        $stmt->execute();
        return $stmt->rowCount() == 1;
      }
  /*
  * Fonction de recherche des evenements.
  */
  function searchEvenement($recherche,$tri){
    $sql = <<<EOD
      select titre as "Evénement",description as "Description",ou as "Lieu",quand as "Date",
      categorie as "Catégorie"
      from events where titre like UPPER(:recherche) or titre like :recherche OR description like :recherche
      order by :tri
EOD;

    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue(':recherche',"%".$recherche."%",PDO::PARAM_STR);
    $stmt->bindValue(':tri',$tri);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  function createAbonnement($auteur,$categorie,$motcle){
    $sql = <<<EOD
      INSERT INTO abonnements(auteur, categorie,motcles)
      VALUES(:auteur, :categorie, :motcle)
EOD;
    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue(':auteur',$auteur);
    $stmt->bindValue(':categorie',$categorie);
    $stmt->bindValue(':motcle',$motcle);
    $stmt->execute();
    return $stmt->rowCount() == 1;
  }
  /*
  * Fonction de retour des evenements liées à un abonnement.
  */
  function getAbonnements($pseudo){
    $sql = <<<EOD
    SELECT titre as Event,quand as Date, events.auteur as Auteur,
    nbparticipants as "Participant(s)" from events,abonnements
where (abonnements.auteur  =:pseudo AND events.categorie = abonnements.categorie) OR (abonnements.motcles like events.description)
EOD;
    $stmt = $this->connexion->prepare($sql);
    $stmt->bindValue(':pseudo',$pseudo);
    $stmt->execute();
    while ($event = $stmt->fetch()) {
        $res[]= $event;
    }
    if (empty($res)) {
      $vide = "0";
      $res[] = $vide;
    }
    return $res;
}
  /*
  Retourne les evenements crées par un utilisteur.
  */
  function findMesEvenements($pseudo){
  $sql = <<<EOD
  SELECT titre as Event,quand as Date, auteur as Auteur,
  nbparticipants as "Participant(s)" from events
  where Auteur =:pseudo
  ORDER by datecreation desc
EOD;
  $stmt = $this->connexion->prepare($sql);
  $stmt->bindValue(':pseudo',$pseudo);
  $stmt->execute();
  while ($event = $stmt->fetch()) {
      $res[]= $event;
  }
  if (empty($res)) {
    $vide = "0";
    $res[] = $vide;
  }
  return $res;
}
  /*
  * Ajout des evenements dans la table, les titres sont ajouter en majuscule
  * pour faciliter les recherches.
  */
  function createEvenement($pseudo,$titre,$categorie,$description,$ou,$quand,$date){
    // Insertion
    $sql = <<<EOD
      INSERT INTO events(auteur, titre, categorie, description, ou ,quand ,datecreation)
      VALUES(:pseudo, UPPER(:titre), :categorie, :description,:ou,:quand,:datecreation);

EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':pseudo',$pseudo);
      $stmt->bindValue(':titre',$titre);
      $stmt->bindValue(':categorie',$categorie);
      $stmt->bindValue(':description',$description);
      $stmt->bindValue(':ou',$ou);
      $stmt->bindValue(':quand',$quand);
      $stmt->bindValue(':datecreation',$date);
      $stmt->execute();
      return $stmt->rowCount() == 1;
    }
  function incrementNbEventsCrees($pseudo){
    $sql = <<<EOD
      UPDATE projet_users SET nbevtscrees = nbevtscrees+1 where pseudo =:pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':pseudo',$pseudo);
      $stmt->execute();
      return $stmt->rowCount() == 1;
  }
    /*
    * MAJ de la description d'un utilisateur.
    */
   function UpdateProfil($pseudo,$description){
     $sql = <<<EOD
       UPDATE projet_users SET description =
       :description where pseudo = :pseudo
EOD;
       $stmt = $this->connexion->prepare($sql);
       $stmt->bindValue(':description',$description);
       $stmt->bindValue(':pseudo',$pseudo);
       $stmt->execute();
       return $stmt->rowCount() == 1;
     }

    /*
    *Cree un profil pour un utilisateur.
    */
    function createProfil($pseudo){
      $sql = <<<EOD
      INSERT INTO projet_users(pseudo, description)
      VALUES(:pseudo, 'None')
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':pseudo',$pseudo);
        $stmt->execute();
        return $stmt->rowCount() == 1;
      }

    /* Récupère les informations sur les
     * evenements déjà crées.
     */
    function getAllEvents(){
        $sql = <<<EOD
        select
        titre as Event,quand as Date, auteur as Auteur,
        nbparticipants as "Participant(s)"
        from events
        order by quand
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        while ($event = $stmt->fetch()) {
            $res[]= $event;
        }
        return $res;
    }
    /*
    Recupere les catégories crées.
    */
    function getCategories(){
      $sql = <<<EOD
     select
        *
        from catégories
        order by name
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->execute();
      $res = [];
      while ($categorie = $stmt->fetch()) {
          $res[]= $categorie;
      }
      return $res;
      // ou return $stmt->fetchAll();
  }
  /*
  * Ajoute un participant à un évènement.
  */
    function addParticipantToEvent($event){
      $sql = <<<EOD
      UPDATE events SET nbparticipants=nbparticipants+1 WHERE titre = :event;
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':event', $event);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    function IncrementParticipations($pseudo){
      $sql = <<<EOD
      UPDATE projet_users SET nbparticipations=nbparticipations+1,
                                  nbparticipants=nbparticipants+1
                                  WHERE pseudo= :pseudo
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':pseudo', $pseudo);
      $stmt->execute();
      return $stmt->fetchAll();
    }

  //Les deux foncitons précédentes auraient pu être réalisé au même moment..



    /* Récupère la liste des coureurs de l'équipe passée en paramètre
     * paramètre : nom d'une équipe
     * résultat : liste (table) de coureurs. Chauqe élément est une table associative (clés 'nom' et 'dossard')
     *   ou false si l'équipe n'existe pas
     */
    function getMembers($equipe){
        $sql = <<<EOD
       select
          nom, dossard
          from coureurs
          where equipe = :equipe
          order by dossard
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':equipe', $equipe);
        $stmt->execute();
        $res = [];
        while ($coureur = $stmt->fetch()) {
            $res[]= $coureur;
        }
        return $res;
        // ou return $stmt->fetchAll();
    }

    /* Récupère la liste des équipes
     * résultat : liste (table) d'équipes'. Chauqe élément est une table associative
     * (clés : ensemble des attributs de la table, dont 'nom', couleur' et 'directeur')
     */
    function getEquipes(){
        $sql =  "select * from equipes";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* Récupère la liste de étapes
     * résultat : liste (table) des étapes
     */
    function getEtapes(){
        $sql =  "select * from etapes order by numero";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private $profilCoureur = 'coureurs.dossard, coureurs.nom, coureurs.equipe as "équipe", coureurs.taille';
    /* Récupère la liste de coureurs
     * $equipe : si non NULL :  ne sélectionne que les coureurs de l'équipe
     * $dossard : si non NULL :  ne sélectionne que le coureur possédant ce dossard
     * résultat : liste (table) des coureurs
     */
    function getCoureurs($equipe = NULL, $dossard=NULL){
        $criteria = [];
        if (! is_null($equipe))
           $criteria['equipe'] = 'equipe = :equipe';
        if (! is_null($dossard))
           $criteria['dossard'] = 'dossard = :dossard';
        $condition = '';
        if (count($criteria)>0)
          $condition = 'where ' . implode (' and ', $criteria);
        $sql  = "select {$this->profilCoureur} from coureurs {$condition} order by dossard";
        $stmt = $this->connexion->prepare($sql);
        foreach($criteria as $nom=>$useless )
            $stmt->bindValue($nom,$$nom);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*
     * Liste des favoris d'un utilisateur
     * $user : l'utilisateur
     * $opposite : si true : renvoie la liste complémentaire (ceux qui ne sont pas favoris)
     *
     */
   function getFavoris($user, $opposite = false){
      $sql = <<<EOD
select {$this->profilCoureur}  from coureurs
join favoris on coureurs.dossard = favoris.coureur
where favoris."user" = :user
order by dossard
EOD;
      if ($opposite)
           $sql = "select {$this->profilCoureur}  from coureurs except  $sql" ;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':user',$user);
      $stmt->execute();
      return $stmt->fetchAll();
    }

    /* Ajoute une étape
     * argument : nom de l'étape
     * résultat : booléen indiquant si l'opération s'est bien déroulée
     */
    function addEtape($nom){
        $sql =  "insert into etapes(nom) values (:nom)";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':nom',$nom);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    /* Enregistre un chrono
     * arguments : étape (numero), dossard, temps
     * résultat : booléen indiquant si l'opération s'est bien déroulée
     **/
    function addChrono($etape, $dossard, $chrono = NULL){
      $sql = <<<EOD
       insert into
         releves (etape, dossard, chrono)
         values (:etape,:dossard,:chrono)
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':etape',$etape);
        $stmt->bindValue(':dossard',$dossard);
        $stmt->bindValue(':chrono',$chrono);
        $stmt->execute();
        return $stmt->rowCount() == 1;
   }

    /* Efface tous les relevés
     */
    function resetReleves(){
        $stmt = $this->connexion->query("truncate table releves");
    }

    /* Efface toutes les étapes
     */
    function resetEtapes(){
       // $this->resetReleves();
       // $stmt = $this->connexion->query("delete from etapes");
       // $stmt = $this->connexion->query("alter sequence etapes_numero_seq restart with 1");
        $stmt = $this->connexion->query("truncate table etapes restart identity cascade");
    }

    /*
     * Statistiques des résultats de la course
     */
    function getStats(){
      $avecNomV2 = <<<EOD
       select nom as "étape", etape as "numéro", min(chrono) as "temps mini",  max(chrono) as "temps maxi",  avg(chrono) as "temps moyen", count(*) as "coureurs au départ", count(chrono) as "coureurs arrivés"
         from releves
         join etapes on etape = etapes.numero
         group by etape, nom
         order by etape
EOD;

        $stmt = $this->connexion->prepare($avecNomV2);
        $stmt->execute();
        return $stmt->fetchAll();

    }
    /*
     * Tableau d'arrivée d'une étape
     * $etape : l'étape
     */
   function getArrivee($etape){
      $sql = <<<EOD
select coureurs.dossard, coureurs.nom as coureur, releves.chrono, equipes.nom as équipe , equipes.couleur as maillot
 from coureurs
 join releves on releves.dossard = coureurs.dossard
 join equipes on equipes.nom = coureurs.equipe
 where etape = :etape and chrono is not null
 order by chrono
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':etape',$etape);
      $stmt->execute();
      return $stmt->fetchAll();
   }

   /*
    * Test d'authentification
    * $pseudo, $password : authentifiants
    * résultat :
    *    Instance de Personne représentant l'utilsateur authentifié, en cas de succès
    *    NULL en cas d'échec
    */
   function authentifier($pseudo, $password){ // version password hash
        $sql = <<<EOD
        select
        pseudo, nom, prenom, password
        from "projet_users_identification"
        where pseudo = :pseudo
EOD;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->execute();
        $info = $stmt->fetch();
        if ($info && crypt($password, $info['password']) == $info['password'])
              return new Identite($info['pseudo'], $info['nom'], $info['prenom']);
        else
          return NULL;
    }

   /*
    * Récupère l'avatar d'un utilisateur
    * $login : login de l'utilisateur
    * résultat :
    *   si l'utilisateur existe : table assoc
    *    'mimetype' : mimetype de l'image
    *    'data' : flux ouvert en lecture sur les données binaires de l'image
    *     si l'utilisateur n'a pas d'avatar, 'mimetype' et 'data' valent NULL
    *   si l'utilisateur n'existe pas : le résultat vaut NULL
    */
   function getAvatar($login){
      $sql = <<<EOD
      select mimetype, avatar
      from s8_users
      where login=:login
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':login', $login);
      $stmt->bindColumn('mimetype', $mimeType);
      $stmt->bindColumn('avatar', $flow, PDO::PARAM_LOB);
      $stmt->execute();
      $res = $stmt->fetch();
      if ($res)
         return ['mimetype'=>$mimeType,'data'=>$flow];
      else
         return false;

    }

   /*
    * Supprime un favori
    */
   function removeFavori($user, $coureur){
      $sql = <<<EOD
delete from favoris
where coureur=:coureur and "user"=:user
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':coureur',$coureur);
      $stmt->bindValue(':user',$user);
      $stmt->execute();
      return $stmt->rowCount() == 1;
    }

    /*
     * Ajoute un favori
     */
   function addFavori($user, $coureur){
      $sql = <<<EOD
insert into favoris (coureur, "user") values (:coureur, :user)
EOD;
      $stmt = $this->connexion->prepare($sql);
      $stmt->bindValue(':coureur',$coureur);
      $stmt->bindValue(':user',$user);
      try {
         $stmt->execute();
         return $stmt->rowCount() == 1;
      } catch (PDOException $e) {
         if ($e->getCode()=='23505'){
            // violation de contrainte d'unicité : le couple (coureur, user) existait déjà
            // ajout impossible
          return false;
         }
         throw $e; // autre erreur : pas de traitement adéquat => on propage
      }
   }


}
?>
