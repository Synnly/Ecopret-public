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
    console.log(inp.files[0] + "a");
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
  console.log(input);
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
  var menu = document.getElementById("recherche");
  var mainElement = document.querySelector("main");
  var target = event.target;
  if (menu.contains(target)) {
    document.getElementById("rechercheOptions").style.display = "block";
    return;
  }else if (mainElement.contains(target)) {
    rechercheOptions.style.display = "none";
  }
});

async function requestPython(mot) {
    return fetch(`/pythonRequestDataBase/${mot}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la requête');
            }
            return response.json();
        })
        .then(data => {
            return data;
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des synonymes :', error);
            return null;
        });
}

async function filtrageAnnonces() {
    document.getElementById("rechercheOptions").style.display = "none";
    document.getElementById('loader').style.display = 'block';
    var annonces = document.querySelectorAll(".card_list");
    console.log(annonces)
    var critereInput = document.getElementById("recherche").value.toLowerCase();
    console.log(critereInput)
    
    if(critereInput !== ""){
        var synonymesRequest = await requestPython(critereInput);
        var synonymes = JSON.parse(synonymesRequest.output);
        console.log(synonymes);
    }
    
    if (critereInput === "" || synonymes === null) {
        annonces.forEach(function(annonce) {
            annonce.style.display = "block";
            document.getElementById('loader').style.display = 'none';
        });
        return;
    }
    
    annonces.forEach(function(annonce) {
        var titreAnnonce = annonce.querySelector("#titreAnnonce").textContent.toLowerCase(); 
        var descAnnonce = annonce.querySelector("#descAnnonce").textContent.toLowerCase(); 
        var affichageAnnonce = true;

        if (!titreAnnonce.includes(critereInput)) {
            affichageAnnonce = false;
        }else {
            affichageAnnonce = true;
            return;
        }
        if (!descAnnonce.includes(critereInput)) {
            affichageAnnonce = false;
        }else {
            affichageAnnonce = true;
            return ;
        }

        if (synonymes) {
            for (let mot of synonymes) {
                if (!titreAnnonce.includes(mot)) {
                    affichageAnnonce = false;
                    
                }else {
                    affichageAnnonce = true;
                    break;
                }
                if (!descAnnonce.includes(mot)) {
                    affichageAnnonce = false;
                    
                }else {
                    affichageAnnonce = true;
                    break;
                }
            }
        }
        annonce.style.display = affichageAnnonce ? "block" : "none";   
    });
    document.getElementById('loader').style.display = 'none';
}