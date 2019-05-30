window.addEventListener('load',initEvent);


function initEvent(){
  document.forms.create_event.addEventListener('submit',getStateEvent);
}

function getStateEvent(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/createEvenement.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayState);

}
function displayState(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Evenement crée avec succès !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#CreateEventState');
  cible.textContent='';
  cible.appendChild(node);
}
