window.addEventListener('load',initProfil);
window.addEventListener('load',initProfil);

function initProfil(){
  document.forms.up_profil_form.addEventListener('submit',getStateProfil);
}

function getStateProfil(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/upProfil.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(displayStateUpProfil);

}

function displayStateUpProfil(answer){
  let node = document.createElement('p');
  if(answer.status == "ok"){
    node.textContent = 'Profil mis à jour avec succès !';
  }else {
    node.textContent = 'Erreur detectée';
  }
  let cible  = document.querySelector('#UpdateProfil');
  cible.textContent='';
  cible.appendChild(node);
}
