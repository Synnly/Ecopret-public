let positionWindow = 0;
var tabUtil = [];
tabUtil[0] = false;
tabUtil[1] = false;
tabUtil[2] = false;

function afficherModifierAnnonce(name, photo1, photo2, photo3, desc, prix, type, id){
    document.getElementById("allCard").style.filter = 'blur(13px)';
    document.getElementById("allCard").style.pointerEvents = 'none';
    annonce = document.getElementById('card_modif');
    annonce.style.display = 'block';
    annonce.style.position = 'absolute';
    annonce.style.top = '15%';
    annonce.style.left = '2.5%';
    annonce.style.zIndex = '999';
    document.getElementById('img1').src = (photo1.split('/picturesAnnouncement/')[1] === "") ? "" : photo1;
    document.getElementById('img2').src = (photo2.split('/picturesAnnouncement/')[1] === "") ? "" : photo2;
    document.getElementById('img3').src = (photo3.split('/picturesAnnouncement/')[1] === "") ? "" : photo3;
    if(document.getElementById('img1').src.split('/mes_annonces')[1] !== ""){
        tabUtil[0] = true;
    }
    if(document.getElementById('img2').src.split('/mes_annonces')[1] !== ""){
        tabUtil[1] = true;
    }
    if(document.getElementById('img3').src.split('/mes_annonces')[1] !== ""){
        tabUtil[2] = true;
    }
    document.getElementById('modifier_annonce_titre').value = name;
    document.getElementById('modifier_annonce_description').value = desc;
    document.getElementById('modifier_annonce_id').value = id;
    document.getElementById('modifier_annonce_prix').value = prix;
    document.getElementById('toggle_m').checked = (type == 0) ? false : true;
    positionWindow = window.scrollY || document.documentElement.scrollTop
    scrollToSlowly(0, positionWindow);
}
function scrollToSlowly(targetPosition, duration) {
    const startPosition = window.scrollY || document.documentElement.scrollTop;
    const distance = targetPosition - startPosition;
    const startTime = performance.now();

    function scrollAnimation(currentTime) {
        const elapsedTime = currentTime - startTime;
        const progress = Math.min(elapsedTime / duration, 1);
        const easeInOutCubic = progress < 0.5 ? 4 * progress ** 3 : 1 - Math.pow(-2 * progress + 2, 3) / 2;
        const newPosition = startPosition + distance * easeInOutCubic;

        window.scrollTo({
            top: newPosition,
            behavior: 'auto' 
        });

        if (progress < 1) {
            requestAnimationFrame(scrollAnimation);
        }
    }

    requestAnimationFrame(scrollAnimation);
}

function annulerModifierAnnonce(){
    document.getElementById("allCard").style.filter = '';
    document.getElementById("allCard").style.pointerEvents = 'auto';
    annonce = document.getElementById('card_modif');
    annonce.style.display = 'none';
    document.getElementById('img1').src = '';
    document.getElementById('img2').src = '';
    document.getElementById('img3').src = '';
    document.getElementById('modifier_annonce_titre').value = '';
    document.getElementById('modifier_annonce_description').value = '';
    document.getElementById('modifier_annonce_prix').value = '';
    document.getElementById('toggle_m').checked = false;
    document.getElementById("modifier_annonce_ajouterPhoto").value = '';
    document.getElementById("modifier_annonce_ajouterPhoto2").value = '';
    document.getElementById("modifier_annonce_ajouterPhoto3").value = '';
    tabUtil[0] = false;
    tabUtil[1] = false;
    tabUtil[2] = false;
    scrollToSlowly(positionWindow, positionWindow);
}

var inputUtiliser;
var img_a_changer = "";

function clickphoto(id) {
    input = document.getElementById(id); 
    if(input.src.split("/mes_annonces")[1] === ""){
        getTheFirstInputFree().click();
    }else {
        switch (id.split('img')[1]) {
            case '1':
                inputUtiliser = document.getElementById("modifier_annonce_ajouterPhoto");
                break;
            case '2':
                inputUtiliser = document.getElementById("modifier_annonce_ajouterPhoto2");
                break
            default:
                inputUtiliser = document.getElementById("modifier_annonce_ajouterPhoto3");
                break;
        }
        img_a_changer = input;
        inputUtiliser.click();
    }
 }

 function getTheFirstInputFree(){
     var allInput = [document.getElementById("modifier_annonce_ajouterPhoto"), document.getElementById("modifier_annonce_ajouterPhoto2"), document.getElementById("modifier_annonce_ajouterPhoto3")];
     i = 0;
     var inputLibre = allInput.find(function(inp){
         if(inp.files[0] === undefined && tabUtil[i] == false){
             inputUtiliser = inp;
             return inp;
         }
         i++;
     });
         return inputLibre;
 }
 
 function ajoutPhoto() {
     var input = inputUtiliser;
     console.log(input);
     var images = [document.getElementById("img1"), document.getElementById("img2"), document.getElementById("img3")];

     if(img_a_changer === ""){
        var image2 = "";
        var imageLibre = images.find(function(img) {
            var img2 = img.src;
            img2 = img2.split("/mes_annonces")[1];
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
    }else {
        var reader = new FileReader();
        
            reader.onload = function (e) {
                img_a_changer.src = e.target.result;
            };
         
            reader.readAsDataURL(input.files[0]);
    }
 }