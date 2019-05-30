
window.addEventListener('load',initJoin);

function initJoin(){
  document.forms.EventFeed.addEventListener('submit',getJoin);
}

function getJoin(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/joinEvenement.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayStateJoin);

}

function displayStateJoin(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Vous participez !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#join');
  cible.textContent='';
  cible.appendChild(node);
}
