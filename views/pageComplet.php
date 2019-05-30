<?php
spl_autoload_register(function ($className) {
    include ("lib/{$className}.class.php");
});
  session_name('projet');
  session_start();
  if (isset($_SESSION['ident'])){
      $personne = $_SESSION['ident'];
  }
  require_once(__DIR__.'/lib/fonctionsHTML.php');
  $dataPersonne ='';    // si utilisateur non authentifié, data-personne n'est pas défini
  if (isset($personne)) // l'utilisateur est authentifié
      $dataPersonne = 'data-personne="'.htmlentities(json_encode($personne)).'"'; // l'attribut data-personne contiendra l'objet personne, en JSON
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
 <meta charset="UTF-8" />
 <title>U'limeet</title>
<link rel="stylesheet" href="style/style.css" />
<script src="js/fetchUtils.js"></script>
<script src="js/action_recherche.js"></script>
<script src="js/action_updateprofil.js"></script>
<script src="js/createEvent_action.js"></script>
<script src="js/action_subscribe.js"></script>
<script src="js/action_joinEvent.js"></script>
<script src="js/action_joinEventOnSubscribed.js"></script>
<!--<script src="js/gestion_logout.js"></script>-->
</head>
<body>
<h1>Projet U'limeet</h1>
<section id="espace_fixe">
  <section id="section_events">
  <div id="maj_events">
      <label for="evenements">Evènements à venir :</label>
      <form action="services/joinEvenement.php" id='EventFeed' method="POST">
         <!-- Montrera 10 evenements avant de devoir dérouler la liste.-->
        <select id="event" name="event" size="10">
          <?php echo eventsToOptionsHTML($events); ?>
        </select>
        <button type="submit" name="valid">OK</button>
      </form>
      <div id="join"></div>
  </div>
    <!-- recherche d'évenements -->
  <div id="recherche">
      <center><form id="form_recherche" method="POST" action="services/searchEvenement.php">
      <label for="recherche">Recherche: </label>
      <input type="text" name="recherche" id="recherche"/>
      <p><label for="tri">Tri: </label>
      <select id="tri" name="tri">
         <option selected="quand" value="quand">Chronologique</option>
         <option value="quand">Date de création</option>
         <option value="nbparticipants">Popularité</option>
         <option value="categorie">Catégorie</option>
      </select></p>

    <button type="submit" id="valid_search"name="valid">OK</button></center>
  </form>
    <div id="resultat"></div>
  </div>
  </section>

</section>

<!-- ESPACE VARIABLE -->
<section id="espace_variable">
  <!--Espace deconnecte -->
  <section class="deconnecte">

</section>

<section class="connecte">
  <!-- Les abonnements d'un utilisateur -->
  <section id="mesabonnements">
    <h5>Mes abonnements</h5>
    <div class="maj_events">
      <form action="services/joinEvenement.php" method="POST" id="SubscribedFeed">
    <select id="event" name="event" size="10"> <!-- Montrera 10 evenements avant de devoir dérouler la liste.-->
      <?php
    echo eventsToOptionsHTML($abonnements);
      ?>
    </select>
    <button type="submit" name="valid">OK</button>
    </form>
    </div>
    <div id="join2"></div>
  </section>
  <!-- Les evenements crées par un utilisteur-->
  <section id="mesevents">
    <h5>Mes Evènements : </h5>
    <div class="mesEvents">
      <form action="" method="" id="">
    <select id="event" name="event" size="10"> <!-- Montrera 10 evenements avant de devoir dérouler la liste.-->
      <?php
    echo eventsToOptionsHTML($mesEvents);
      ?>
      </select>
      </form>
    </div>
  </section>
  <!-- Creer un event -->
  <section id="createEvent">
    <fieldset>
      <h4> Créer un évènement </h4>
    <form action="services/createEvenement.php" method="POST" name="create_event" id="create_event">
       <label for="titre">Titre :</label>
       <input type="text" name="titre" id="titre" required="required" autofocus/><br>
       <label for="categorie">Catégorie :</label>
       <select name="categorie" id="categorie" required="required"><br>
       <?php echo catsToOptionsHTML($cats); ?>
     </select><br>
       <label for="description">Description :</label>
       <input type="text" name="description" id="description" required="required" autofocus/><br>
       <label for="quand">Date:</label>
       <input type="date" min ="<?php echo date("Y-m-d"); ?>" name="quand" id="quand" required="required"/><br>
      <label for="lieu">Lieu:</label>
      <input type="text" name="lieu" id="lieu" required="required" /><br>
      <button type="submit" name="valid">OK</button>
    </form>
  </fieldset>
    <div id="CreateEventState"></div>
</section>
<!-- S'abonner à une catégorie ou mot-cle(s) -->
  <section id="abonnements">
     <fieldset>
      <form action="services/createAbonnement.php" method="POST" name="create_abonnement" id="create_abonnement">
         <h4>S'abonner</h4>
         <label for="categorie">Catégorie :</label>
           <select name="categorie" id="categorie" >
           <?php echo catsToOptionsHTML($cats); ?>
         </select><br>
         <label for="motcle">Mots-clés :</label>
         <input type="text" name="motcle" id="motcle" autofocus/>
        <button type="submit" name="valid">OK</button>
      </form>
       </fieldset>
      <div id='ProcessStateAbonnement'></div>
  </section>




  <!--<img id="avatar" alt="mon avatar" src="" />-->
  <div id="titre_connecte">Bonjour <?php echo $pseudo ;?></div>
  <button id="logout">Déconnexion</button>
  <div id="liste_favoris"></div>

  <!-- MAJ du profil -->
<section id="upProfil">
  <label> Mettre à jour ma description</label>
  <form action="services/upProfil.php" id="up_profil_form">
    <input type="text" name="majprofil" id="majprofil"/>
      <button type="submit" name="valid">OK</button>
  </form>
  <div id=UpdateProfil></div>
</section>
</section>
</section>
</body>
</html>
