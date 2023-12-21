const region = document.getElementById("region");

// Je déclare une constante correspondant à l'élément html où apparaîtront mes villes
const divRegions = document.getElementById("displayRegions");

// Je crée un écouteur afin que pour chaque input rentré dans le champs ville une requête est faite à la table ville de la BDD
region.addEventListener("input", (event) => {
  divRegions.style.display = "block";
  let string = event.target.value;
  fetch("https://127.0.0.1:8000/user/newadress/region/" + string)
    .then((response) => response.json())
    .then((json) => createDivRegion(json));
});

// Fonction qui crée des div avec la ville et le code département depuis un json et qui ajoute un évènement au click afin que ça effectue une autocomplétion
function createDivRegion(json) {
  divRegions.innerHTML = "";
  for (let i = 0; i < json.length; i++) {
    const option = document.createElement("option");
    option.value = json[i].id;
    option.textContent = json[i].region;
    divRegions.appendChild(option);
  }

  divRegions.style.display = "block";
}

const regionSelect = document.getElementById("displayRegions");
regionSelect.addEventListener("change", function () {
  const selectedIndex = this.selectedIndex;
  const selectedRegionValue = this.options[selectedIndex].value; 

  region.value = selectedRegionValue;

});
