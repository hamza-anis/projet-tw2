
window.addEventListener('load',initJoin2);

function initJoin2(){
  document.forms.SubscribedFeed.addEventListener('submit',getJoin2);
}

function getJoin2(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/joinEvenement.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayStateJoin2);

}

function displayStateJoin2(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Vous participez !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#join2');
  cible.textContent='';
  cible.appendChild(node);
}
