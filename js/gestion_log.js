
window.addEventListener('load',initState);
window.addEventListener('load',initLog);

var currentUser = null; //objet "personne" de l'utilisateiur connecté

    function initState(){ // initialise l'état de la page
        if(document.body.dataset.personne){
          let personne = JSON.parse(document.body.dataset.personne);
          etatConnecte(personne);
        }
  }

    function processAnswer(answer){
      if(answer.status=="ok"){
        return answer.result;
      }else{
        throw new Error(answer.message);
      }
    }

  function initLog(){ // mise en place des gestionnaires sur le formulaire de login et le bouton logout
         //connexion
      document.forms.form_login.addEventListener('submit',sendLogin); // envoi
      //document.forms.form_login.addEventListener('input',function(){this.message.value='';}); // effacement auto du message
      document.querySelector('#logout').addEventListener('click',sendLogout);
  }

  function sendLogin(ev){ // form event listener
      ev.preventDefault();
      let url = "services/login.php?"+formDataToQueryString(new FormData(this));
      let options = {method : 'post', body : new FormData(this), credentials : 'same-origin'};
      fetchFromJson(url,options)
      .then(processAnswer)
      .then(etatConnecte);
    }
    function etatConnecte(personne){ // passe dans l'état 'connecté'
        currentUser = personne;
        // cache ou montre les éléments
        for (let elt of document.querySelectorAll('.deconnecte'))
           elt.hidden=true;
        for (let elt of document.querySelectorAll('.connecte'))
           elt.hidden=false;
          //fetchBlob('services/getAvatar.php?login='+currentUser.login)
          //.then(changeAvatar);
      }
