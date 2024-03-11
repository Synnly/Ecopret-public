function clickphoto() {
   getTheFirstInputFree().click();
}
var inputUtiliser;
function getTheFirstInputFree(){
    var allInput = [document.getElementById("ajouter_annonce_ajouterPhoto"), document.getElementById("ajouter_annonce_ajouterPhoto2"), document.getElementById("ajouter_annonce_ajouterPhoto3")];
    var inputLibre = allInput.find(function(inp){
        console.log(inp.files[0] + "a")
        if(inp.files[0] === undefined){
            inputUtiliser = inp;
            return inp;
        }
    });
    if(inputLibre){
        return inputLibre;
    }else {
        alert("Toutes les images sont déjà utilisées.");
    }
}

function ajoutPhoto() {
    var input = inputUtiliser;
    console.log(input);
    var images = [document.getElementById("img1"), document.getElementById("img2"), document.getElementById("img3")];
    var image2 = "";
    var imageLibre = images.find(function(img) {
        var img2 = img.src;
        img2 = img2.split("/main")[1];
        if (img2 === ""){
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

function afficherAddAnnonce(){
    annonce = document.getElementById('card');
    annonce.style.display = 'block';
}

function annulerAddAnnonce(){
    annonce = document.getElementById('card');
    annonce.style.display = 'none';
    document.getElementById('img1').src = '';
    document.getElementById('img2').src = '';
    document.getElementById('img3').src = '';
    document.getElementById('ajouter_annonce_titre').value = '';
    document.getElementById('ajouter_annonce_description').value = '';
    document.getElementById('ajouter_annonce_prix').value = '';
    document.getElementById('toggle').checked = false;
}
function ValidImage(){
    extensions_admises = ['png', 'jpg', 'jpeg', ''];
    if((!extensions_admises.includes(document.getElementById("ajouter_annonce_ajouterPhoto").files[0].name.split('.').pop())) || (!extensions_admises.includes(document.getElementById("ajouter_annonce_ajouterPhoto2").files[0].name.split('.').pop())) || !extensions_admises.includes(document.getElementById("ajouter_annonce_ajouterPhoto3").files[0].name.split('.').pop()) ){
        alert("Les photos doivent avoir comme format : png, jpg, jpeg ou pdf.");
        return false;
    } else {
        return true;
    }
}

