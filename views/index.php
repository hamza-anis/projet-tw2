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
<link rel="stylesheet" href="style/style_min.css" />
<script src="js/fetchUtils.js"></script>
<script src="js/gestion_log.js"></script>
<script src="js/action_createAccount.js"></script>

</head>
<body>
<h1>Projet U'limeet</h1>

  <section id="section_events">
  <div id="maj_events">
      <label for="evenements">Evènements à venir :</label>
      <form action="services/joinEvenement.php" method="POST">
         <!-- Montrera 10 evenements avant de devoir dérouler la liste.-->
        <select id="event" name="event" size="10">
          <?php echo eventsToOptionsHTML($events);  ?>
        </select>
      </form>
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
  <section class="deconnecte">
  <div id="connexion">
     <center><p>Connexion</p></center>
     <form action="services/login.php" method="POST" id="form_login" name="form_login" >
           <label for="pseudo">Pseudo :</label>
           <input type="text" name="pseudo" id="pseudo" required="" autofocus=""/>
           <label for="password">Mot de passe :</label>
           <input type="password" name="password" id="password" required="required" />
           <button type="submit" id="connect_button" name="valid">OK</button>
      </form>
    </div>
         <!-- ajouter le js -->
    <div id="createAccount">
      <h3> Créer un compte </h3>
         <form action="services/createUser.php" method="POST" id="subscribe">
            <label for="prenom">Nom :</label>
            <input type="text" name="nom" id="nom" required="required" autofocus/>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required="required" autofocus/>
            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" required="required" autofocus/>
           <label for="password">Mot de passe :</label>
           <input type="password" name="password" id="password" required="required" />
           <button type="submit" name="valid">OK</button>

         </form>
         <div id='AccountState'></div>
  </div>
  </section>
</section>

</body>
</html>
