
window.addEventListener('load',initAccountState);

function initAccountState(){
  document.forms.subscribe.addEventListener('submit',getState);
}

function getState(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/createUser.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayStateAccount);

}

function displayStateAccount(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Compte crée avec succès !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#AccountState');
  cible.textContent='';
  cible.appendChild(node);
}
