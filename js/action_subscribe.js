
window.addEventListener('load',initSub);

function initSub(){
  document.forms.create_abonnement.addEventListener('submit',getSub);
}

function getSub(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/createAbonnement.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayStateSub);

}

function displayStateSub(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Vous êtes abonné !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#ProcessStateAbonnement');
  cible.textContent='';
  cible.appendChild(node);
}
