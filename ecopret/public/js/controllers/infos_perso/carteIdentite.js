function validerCarteIdentite() {
    file = document.getElementById('informations_personnelles_carte_identite').value;
    extension_file = file.split('.').pop().toLowerCase();
    extensions_admises = ['png', 'jpg', 'jpeg', 'pdf'];

    if(!extensions_admises.includes(extension_file)){
        alert("La carte d'itentité doit avoir comme format : png, jpg, jpeg ou pdf.");
        return false;
    } else {
        return true;
    }
}