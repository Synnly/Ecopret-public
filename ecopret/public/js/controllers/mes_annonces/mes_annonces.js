let positionWindow = 0;

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
    document.getElementById('modifier_annonce_titre').value = name;
    document.getElementById('modifier_annonce_description').value = desc;
    console.log(id);
    document.getElementById('modifier_annonce_id').value = id;
    document.getElementById('modifier_annonce_prix').value = prix;
    document.getElementById('toggle_m').checked = (type == 0) ? false : true;
    positionWindow = window.scrollY || document.documentElement.scrollTop
    console.log(positionWindow);
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
    scrollToSlowly(positionWindow, positionWindow);
}
