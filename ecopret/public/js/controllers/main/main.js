function clickphoto() {
  getTheFirstInputFree().click();
}
var inputUtiliser;
function getTheFirstInputFree() {
  var allInput = [
    document.getElementById("ajouter_annonce_ajouterPhoto"),
    document.getElementById("ajouter_annonce_ajouterPhoto2"),
    document.getElementById("ajouter_annonce_ajouterPhoto3"),
  ];
  var inputLibre = allInput.find(function (inp) {
    if (inp.files[0] === undefined) {
      inputUtiliser = inp;
      return inp;
    }
  });
  if (inputLibre) {
    return inputLibre;
  } else {
    alert("Toutes les images sont déjà utilisées.");
  }
}

function ajoutPhoto() {
  var input = inputUtiliser;
  var images = [
    document.getElementById("img1"),
    document.getElementById("img2"),
    document.getElementById("img3"),
  ];
  var image2 = "";
  var imageLibre = images.find(function (img) {
    var img2 = img.src;
    img2 = img2.split("/main")[1];
    if (img2 === "") {
      image2 = img;
      return true;
    } else {
      return false;
    }
  });

  if (imageLibre) {
    var reader = new FileReader();

    reader.onload = function (e) {
      image2.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    alert("Toutes les images sont déjà utilisées.");
  }
}

function afficherAddAnnonce() {
  annonce = document.getElementById("card");
  annonce.style.display = "block";
}

function annulerAddAnnonce() {
  annonce = document.getElementById("card");
  annonce.style.display = "none";
  document.getElementById("img1").src = "";
  document.getElementById("img2").src = "";
  document.getElementById("img3").src = "";
  document.getElementById("ajouter_annonce_titre").value = "";
  document.getElementById("ajouter_annonce_description").value = "";
  document.getElementById("ajouter_annonce_prix").value = "";
  document.getElementById("toggle").checked = false;
}
function ValidImage() {
  extensions_admises = ["png", "jpg", "jpeg", ""];
  if (
    !extensions_admises.includes(
      document
        .getElementById("ajouter_annonce_ajouterPhoto")
        .files[0].name.split(".")
        .pop()
    ) ||
    !extensions_admises.includes(
      document
        .getElementById("ajouter_annonce_ajouterPhoto2")
        .files[0].name.split(".")
        .pop()
    ) ||
    !extensions_admises.includes(
      document
        .getElementById("ajouter_annonce_ajouterPhoto3")
        .files[0].name.split(".")
        .pop()
    )
  ) {
    alert("Les photos doivent avoir comme format : png, jpg, jpeg ou pdf.");
    return false;
  } else {
    return true;
  }
}

//window.addEventListener("load", function () {});

document.addEventListener("click", function (event) {
  var menu = document.getElementById("filtreID");
  var mainElement = document.querySelector("main");
  var target = event.target;
  if (menu.contains(target)) {
    document.getElementById("rechercheOptions").style.display = "grid";
    var dateInput = document.getElementById('DateD');
    var today = new Date();
    var todayISOString = today.toISOString().split('T')[0];
    dateInput.setAttribute('min', todayISOString);
    dateInput = document.getElementById('DateF');
    today.setDate(today.getDate() + 1);
    todayISOString = today.toISOString().split('T')[0];
    dateInput.setAttribute('min', todayISOString);
    return;
  } else if (mainElement.contains(target)) {
    rechercheOptions.style.display = "none";
  }
});

async function requestPython(mot) {
  return fetch(`/pythonRequestDataBase/${mot}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur lors de la requête");
      }
      return response.json();
    })
    .then((data) => {
      return data;
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des synonymes :", error);
      return null;
    });
}

async function fetchDates(idAnnonce) {
  try {
    const response = await fetch(`/dates_annonces/${idAnnonce}`);
    if (!response.ok) {
      throw new Error("Erreur lors de la requête");
    }
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Erreur lors de la récupération des dates :", error);
    return null;
  }
}

function clearFilter(){
  document.querySelectorAll('.EmSe input[type="radio"]').forEach(input => {
    input.checked = false;
  });
  document.querySelectorAll('.cate input[type="checkbox"]').forEach(input => {
    input.checked = false;
  });
  document.querySelectorAll('.prix input[type="text"]').forEach(input => {
    input.value = '';
  });
  document.querySelectorAll('.date input[type="date"]').forEach(input => {
    input.value = '';
  });
}

function clearSearch() {
  var annonces = document.querySelectorAll(".card_list");
  annonces.forEach(function (annonce) {
  annonce.style.display = "block";
  document.getElementById("loader").style.display = "none";
  document.getElementById("rechercheOptions").style.display = "none";
  document.getElementById("recherche").value = "";
  });
  clearFilter();
}

function verifPrix() {
  var prixMin = parseInt(document.getElementById("prixMin").value);
  var prixMax = parseInt(document.getElementById("prixMax").value);
  console.log(prixMin, prixMax);
  if (isNaN(prixMin) || isNaN(prixMax)) {
    alert("Veuillez saisir uniquement des nombres");
    return false;
  }
  if (prixMin > prixMax) {
    alert("Veuillez saisir un prix minimum inférieur ou égal au prix maximum");
    return false;
  }
  return true;
}


async function filtrageAnnonces() {
  if (!verifPrix()){
    return;
  }
  document.getElementById("rechercheOptions").style.display = "none";
  document.getElementById("loader").style.display = "block";
  var annonces = document.querySelectorAll(".card_list");

  var radioButtons = document.querySelectorAll('input[name="ES"]');
  var prixMin = parseInt(document.getElementById("prixMin").value);
  var prixMax = parseInt(document.getElementById("prixMax").value);
  var categorie = document.querySelectorAll('input[name="categories"]');
  var categorieSelect = [];
  var dateDeb = document.getElementById("DateD").value;
  var dateFin = document.getElementById("DateF").value;
  if (dateFin !== "") {
    dateFin = new Date(dateFin.split("-"));
  }
  if (dateDeb !== "") {
    dateDeb = new Date(dateDeb.split("-"));
  }
  var typeChoisi = "";

  var prixDPossible = false;
  var prixFPossible = false;
  var categoriePossible = false;
  var dateDPossible = false;
  var dateFPossible = false;

  categorie.forEach((cate) => {
    if (cate.checked) {
      categorieSelect.push(cate.value.toLowerCase());
    }
  });

  for (const radioButton of radioButtons) {
    if (radioButton.checked) {
      typeChoisi = radioButton.value;
      break;
    }
  }
  prixDPossible = !isNaN(prixMin);
  prixFPossible = !isNaN(prixMax);
  categoriePossible = categorie !== "toute";
  dateDPossible = dateDeb !== "";
  dateFPossible = dateFin !== "";

  var critereInput = document
    .getElementById("recherche")
    .value.toLowerCase()
    .trim();

  if (critereInput !== "") {
    var synonymesRequest = await requestPython(critereInput);
    var synonymes = JSON.parse(synonymesRequest.output);
  }

  annonces.forEach(async function (annonce) {
    var titreAnnonce = annonce
      .querySelector("#titreAnnonce")
      .textContent.toLowerCase();
    var descAnnonce = annonce
      .querySelector("#descAnnonce")
      .textContent.toLowerCase();
    var type = annonce.querySelector("#type_annonce").textContent.toLowerCase();
    var prixAnnonce = parseInt(
      annonce
        .querySelector("#price_annonce")
        .textContent.toLowerCase()
        .split(":")[1]
        .trim()
    );
    var categorieAnnonce = annonce
      .querySelector("#categorieAnnonce")
      .textContent.toLowerCase();
    var idAnnonce = annonce.querySelector(".annonce-btn").href.split("/")[5];
    const datesAnnonce = await fetchDates(idAnnonce);
    var dates_annonces = datesAnnonce.split("|");
    var jour_annonce = [];
    for (d of dates_annonces) {
      var tab = d.split(";")[0];
      let day = tab.split("/")[0];
      let month = tab.split("/")[1];
      if(day !== "" && month !== ""){
        day = (day.length === 1) ? "0" + day : day;
        month = (month.length === 1) ? "0" + parseInt(month)-1 : month;
        jour_annonce.push(
          new Date(
            tab.split("/")[2],
            month,
            day
          )
        );
      }
      
    }

    var affichageAnnonce = true;
    var conditionRespectes = true;
    var conditionRadio = true;

    if (prixDPossible) {
      if (prixFPossible) {
        if (prixAnnonce >= prixMin && prixAnnonce <= prixMax) {
          conditionRespectes = true;
        } else {
          conditionRespectes = false;
        }
      } else {
        if (prixAnnonce >= prixMin) {
          conditionRespectes = true;
        } else {
          conditionRespectes = false;
        }
      }
    } else if (prixFPossible) {
      if (prixAnnonce <= prixMax) {
        conditionRespectes = true;
      } else {
        conditionRespectes = false;
      }
    }
    if (!conditionRespectes) {
      annonce.style.display = "none";
      return;
    }
    if (categoriePossible) {
      for (let ca of categorieSelect) {
        if (ca === categorieAnnonce) {
          conditionRespectes = true;
          break;
        } else {
          conditionRespectes = false;
        }
      }
    }
    if (!conditionRespectes) {
      annonce.style.display = "none";
      return;
    }
    if (dateDPossible) {
      if (dateFPossible) {
        if (dateDeb >= jour_annonce[0] && dateFin <= jour_annonce[jour_annonce.length -1]) {
          conditionRespectes = true;
        } else {
          conditionRespectes = false;
        }
      } else {
        if(dateDeb >= jour_annonce[0]){
          conditionRespectes = true;
        } else {
          conditionRespectes = false;
        }
      }
    }else if(dateFPossible){
      if(jour_annonce[jour_annonce.length-1] <= dateFin){
        conditionRespectes = true;
      } else {
        conditionRespectes = false;
      }
    }
    if (!conditionRespectes) {
      annonce.style.display = "none";
      return;
    }
    if (critereInput !== "") {
      if (!titreAnnonce.includes(critereInput)) {
        affichageAnnonce = false;
      } else {
        affichageAnnonce = true;
        return;
      }
      if (!descAnnonce.includes(critereInput)) {
        affichageAnnonce = false;
      } else {
        affichageAnnonce = true;
        return;
      }
    }
    if (typeChoisi !== "" && typeChoisi !== type.split(":")[1].trim()) {
      affichageAnnonce = false;
      conditionRadio = false;
    }
    if (!conditionRespectes) {
      annonce.style.display = "none";
      return;
    }
    if (
      synonymes !== undefined &&
      synonymes !== null &&
      conditionRadio &&
      conditionRespectes
    ) {
      for (let mot of synonymes) {
        if (!titreAnnonce.includes(mot)) {
          affichageAnnonce = false;
        } else {
          affichageAnnonce = true;
          break;
        }
        if (!descAnnonce.includes(mot)) {
          affichageAnnonce = false;
        } else {
          affichageAnnonce = true;
          break;
        }
      }
    }
    annonce.style.display = affichageAnnonce ? "block" : "none";
  });
  document.getElementById("loader").style.display = "none";
}
