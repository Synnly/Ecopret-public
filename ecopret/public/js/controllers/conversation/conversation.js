window.onload = function () {
    let chatDiv = document.getElementById('chat');

    chatDiv.top = 500; // On souhaite scroller toujours jusqu'au dernier message du chat

    let form = document.getElementById('form');
    function handleForm(event) {
        event.preventDefault(); // Empêche la page de se rafraîchir après le submit du formulaire
    }
    form.addEventListener('submit', handleForm);

    const submit = document.querySelector('button');
    submit.onclick = e => { // On change le comportement du submit
        const message = document.getElementById('message'); // Récupération du message dans l'input correspondant
        const data = { // La variable data sera envoyée au controller
            'content': message.value, // On transmet le message...
            'conv': {{ conv.id }} // ... Et la conversation correspondant
        }
        console.log(data); // Pour vérifier vos informations
        fetch('/message', { // On envoie avec un post nos datas sur le endpoint /message de notre application
            method: 'POST',
            body: JSON.stringify(data) // On envoie les data sous format JSON
        }).then((response) => {
            message.value = '';
            console.log(response);
        });
    }

    setInterval(recupererMessages, 2000)
}

async function recupererMessages(){
    fetch('/message', { // On envoie avec un post nos datas sur le endpoint /message de notre application
        method: 'GET'
    }).then((response) => {
        console.log(response);
    });
}