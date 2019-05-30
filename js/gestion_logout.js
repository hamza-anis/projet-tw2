
window.addEventListener('load',initStateOut);
window.addEventListener('load',initLogout);

var currentUser = null; //objet "personne" de l'utilisateiur connecté

    function initStateOut(){ // initialise l'état de la page
        if(document.body.dataset.personne){
          let personne = JSON.parse(document.body.dataset.personne);
          etatConnecte(personne);
        }
  }

    function processAnswer(answer){
      if(answer.status=="ok"){
        etatDeconnecte();
      }else{
        throw new Error(answer.message);
      }
    }

  function initLogout(){ // mise en place des gestionnaires sur le formulaire de login et le bouton logout
      //deconnexion
      document.querySelector('#logout').addEventListener('click',sendLogout);
  }

  function sendLogout(ev){ // form event listener
      ev.preventDefault();
      let url = "services/logout.php?";
      fetchFromJson(url)
      .then(processAnswer);
    }
    function etatDeconnecte(){ // passe dans l'état 'connecté'
        currentUser = '';
        // cache ou montre les éléments
        for (let elt of document.querySelectorAll('.connecte'))
           elt.hidden=true;
        for (let elt of document.querySelectorAll('.deconnecte'))
           elt.hidden=false;
          //fetchBlob('services/getAvatar.php?login='+currentUser.login)
          //.then(changeAvatar);
      }
