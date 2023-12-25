// Je déclare une constante correspondant à l'élément html de l'input label
const inputLabel = document.getElementById("label");

// Je déclare une constante correspondant à l'élément html de l'input code postal
const inputZip = document.getElementById("zip_code");

// Je déclare une constante correspondant à l'élément html de l'input ville
const inputCity = document.getElementById("ville");

// Je déclare une constante correspondant à l'élément html de l'input caché villeId
const inputId = document.getElementById("villeId");

// Je déclare une constante correspondant à l'élément html où apparaîtront mes villes
const dataCities = document.getElementById("cityList");

// Je crée un écouteur afin que pour chaque input rentré dans le champs ville une requête est faite à la table ville de la BDD
inputCity.addEventListener("input", (event) => {
  dataCities.style.display = "block";
  let string = event.target.value;
  fetch("https://127.0.0.1:8000/adresse/ajax/ville/" + string)
    .then((response) => response.json())
    .then((json) => createDivCity(json));
});

// Fonction qui crée des div avec la ville et le code département depuis un json et qui ajoute un évènement au click afin que ça effectue une autocomplétion
function createDivCity(json) {
  dataCities.innerHTML = "";
  for (let i = 0; i < json.length; i++) {
    let nameCity = json[i].ville;
    let departementCity = json[i].codeDepartement;
    let idCity = json[i].id;

    // Créer une nouvelle option
    const option = document.createElement("option");
    option.value = nameCity + " " + departementCity;
    option.setAttribute("id", idCity);

    dataCities.appendChild(option);

   
  }
}


document.getElementById("ville").addEventListener("input", function (event) {
  const selectedOption = dataCities.querySelector(`option[value="${event.target.value}"]`);
  
  if (selectedOption) {
    const cityId = selectedOption.getAttribute("id");
    inputId.value = cityId;  
  } else {
    inputId.value = "";  
  }
});