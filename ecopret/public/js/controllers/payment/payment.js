document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le bouton de soumission par son ID
    var paymentButton = document.getElementById('payment');

    // Ajouter un gestionnaire d'événements pour le clic sur le bouton de soumission
    paymentButton.addEventListener('click', function(event) {
        // Empêcher le comportement par défaut du bouton de soumission (envoi du formulaire)
        alert('Merci d\'avoir souscrit un abonnement sur ECOPRET !');
    });
});
