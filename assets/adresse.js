// Je déclare une constante correspondant à l'élément html de l'input label
const inputLabel = document.getElementById("label");

// Je déclare une constante correspondant à l'élément html de l'input code postal
const inputZip = document.getElementById("zip_code");

// Je déclare une constante correspondant à l'élément html de l'input ville
const inputCity = document.getElementById("ville");

// Je déclare une constante correspondant à l'élément html de l'input caché villeId
const inputId = document.getElementById("villeId");

const inputCpId = document.getElementById("selectedPostalCodesId");

// Je déclare une constante correspondant à l'élément html où apparaîtront mes villes
const dataCities = document.getElementById("cityList");

const codePostaux = document.getElementById("postalCodeSelect");

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
    option.setAttribute(
      "data-codes-postaux",
      JSON.stringify(json[i].codePostaux)
    );
    option.setAttribute("id", idCity);
    dataCities.appendChild(option);
  }
}
document.getElementById("ville").addEventListener("input", function (event) {
  const selectedOption = dataCities.querySelector(
    `option[value="${event.target.value}"]`
  );

  if (selectedOption) {
    const cityId = selectedOption.getAttribute("id");
    const codesPostaux = JSON.parse(
      selectedOption.getAttribute("data-codes-postaux")
    );

    inputId.value = cityId; // Stockez l'id de la ville sélectionnée dans le champ caché

    // Mettez à jour le champ de sélection des codes postaux
    const codePostauxSelect = document.getElementById("postalCodeSelect");
    codePostauxSelect.innerHTML = ""; // Réinitialise les options actuelles

    // Ajoutez une option vide en haut de la liste déroulante
    const defaultOption = document.createElement("option");
    defaultOption.value = ""; 
    defaultOption.text = "Sélectionnez un code postal"; 
    codePostauxSelect.appendChild(defaultOption);

    codesPostaux.forEach((code) => {
      const option = document.createElement("option");
      option.value = code.libelle; // Utilisez le libellé comme valeur de l'option
      option.text = code.libelle; // Affichez également le libellé pour l'utilisateur
      codePostauxSelect.appendChild(option);
    });

    // Ajoutez un écouteur d'événement pour mettre à jour l'ID du code postal lorsque la sélection change
    codePostauxSelect.addEventListener("change", function() {
      const selectedPostalCode = codesPostaux.find(
        (code) => code.libelle === this.value
      );
      if (selectedPostalCode) {
        inputCpId.value = selectedPostalCode.id; // Stockez l'id du code postal sélectionné dans le champ caché
      } else {
        inputCpId.value = ""; // Si aucun code postal n'est sélectionné, réinitialisez le champ caché
      }
    });

  } else {
    inputId.value = "";
    inputCpId.value = ""; // Réinitialisez également le champ caché pour les codes postaux si aucune ville n'est sélectionnée
  }
});
