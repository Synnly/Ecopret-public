/*document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le bouton de soumission par son ID
    var paymentButton = document.getElementById('payment');

    // Ajouter un gestionnaire d'événements pour le clic sur le bouton de soumission
    paymentButton.addEventListener('click', function(event) {
        // Empêcher le comportement par défaut du bouton de soumission (envoi du formulaire)
        event.preventDefault();

        // Afficher la fenêtre modale
        var confirmation = confirm('Merci d\'avoir souscrit un abonnement sur ECOPRET !');

        if (confirmation) {
            // Si l'utilisateur clique sur "OK", rediriger vers la page principale
            window.location.href = "/main";
        }else {
            window.location.href = "/main";
        }
    });
});*/
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le bouton de soumission par son ID
    var paymentButton = document.getElementById('payment');

    // Ajouter un gestionnaire d'événements pour le clic sur le bouton de soumission
    paymentButton.addEventListener('click', function(event) {
        // Empêcher le comportement par défaut du bouton de soumission (envoi du formulaire)
        event.preventDefault();
        alert('Merci d\'avoir souscrit un abonnement sur ECOPRET !');
        window.location.href = "/main";

    });
});
