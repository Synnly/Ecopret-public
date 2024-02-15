function afficherCacherMdp(){
    //Changement du type de l'input pour le mot de passe pour le voir
    document.getElementById('modifier_informations_personnelles_form_motDePasseCompte').type = (document.getElementById('modifier_informations_personnelles_form_motDePasseCompte').type == 'text') ? 'password' : 'text';

    //Changement de l'image selon si le mot de passe est visible ou non
    document.getElementById('VCmdp').src = (document.getElementById('VCmdp').src.endsWith('oeil.png')) ? '/img/cacher.png' : '/img/oeil.png';
}

function afficherAlerte(message){
    alert(message);
}