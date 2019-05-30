window.addEventListener('load',initRecherche);
window.addEventListener('load',initRecherche);

function initRecherche(){
  document.forms.form_recherche.addEventListener('submit',sendForm);
}

function sendForm(ev){ // form event listener
  ev.preventDefault();
  let url = 'services/searchEvenement.php?'+formDataToQueryString(new FormData(this));
  fetchFromJson(url)
  .then(processAnswer)
  .then(displaySearch, displayErrorEtape);
}

function processAnswer(answer){
  if (answer.status == "ok")
    return answer.result;
  else
    throw new Error(answer.message);
}
function displaySearch(evenements){
  let node;
  if (evenements.length>0) {
    node = listToTable(evenements);
  } else {
    node = document.createElement('p');
    node.textContent = 'Pas de résultats';
  }
  let cible  = document.querySelector('#resultat');
  cible.textContent='';
  cible.appendChild(node);
}


function displayErrorEtape(error){
  let p = document.createElement('p');
  p.textContent = error.message;
  let cible  = document.querySelector('#resultat');
  cible.textContent=''; // effacement
  cible.appendChild(p);
}
//----------
function listToTable(list){
  let table = document.createElement('table');
  let row = table.createTHead().insertRow();
  for (let x of Object.keys(list[0]))
    row.insertCell().textContent = x;
  let body = table.createTBody();
  for (let line of list){
    let row = body.insertRow();
    for (let x of Object.values(line))
      row.insertCell().textContent = x;
  }
  return table;
}
