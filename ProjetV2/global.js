// JavaScript source code


// page de connexion
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const messageDiv = document.getElementById('message');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Exemple de validation simple
        if (username === '' || password === '') {
            messageDiv.textContent = 'Veuillez remplir tous les champs.';
            messageDiv.style.color = 'red';
        } else if (username !== 'admin' || password !== 'password') {
            messageDiv.textContent = 'Nom d\'utilisateur ou mot de passe incorrect.';
            messageDiv.style.color = 'red';
        } else {
            messageDiv.textContent = 'Connexion réussie !';
            messageDiv.style.color = 'green';
            // Ici, vous pouvez rediriger l'utilisateur ou effectuer d'autres actions
        }
    });
});



//page des offres

function showDetails(stage) {
    const modal = document.getElementById('details-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');

    // Exemple de données des stages
    const stages = {
        stage1: {
            title: "Stage Développeur Web",
            description: "Vous serez responsable du développement et de la maintenance de nos applications web. Compétences requises : HTML, CSS, JavaScript."
        },
        stage2: {
            title: "Stage Marketing Digital",
            description: "Aidez à la mise en œuvre de stratégies de marketing digital. Compétences requises : SEO, réseaux sociaux, analyse de données."
        },
        stage3: {
            title: "Stage Data Analyst",
            description: "Analysez les données pour aider à la prise de décision stratégique. Compétences requises : Excel, SQL, analyse statistique."
        }
    };

    modalTitle.textContent = stages[stage].title;
    modalDescription.textContent = stages[stage].description;
    modal.style.display = "block"; // Affiche le modal
}

function closeModal() {
    const modal = document.getElementById('details-modal');
    modal.style.display = "none"; // Masque le modal
}

// Ferme le modal si l'utilisateur clique en dehors du contenu
window.onclick = function(event) {
    const modal = document.getElementById('details-modal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}



function apply(stage) {
    // Redirige vers la page de candidature
    window.location.href = 'candidature.html'; // Remplace 'candidature.html' par le nom de ta page de candidature
}


function editOffer(stage) {
    const newTitle = prompt("Entrez le nouveau titre de l'offre :", document.querySelector(`#${stage} h3`).innerText);
    if (newTitle) {
        document.querySelector(`#${stage} h3`).innerText = newTitle;
    }
}

function deleteOffer(stage) {
    const offer = document.getElementById(stage);
    offer.parentNode.removeChild(offer);
}

function addOffer() {
    const title = prompt("Entrez le titre de la nouvelle offre :");
    const location = prompt("Entrez le lieu de l'offre :");
    const duration = prompt("Entrez la durée de l'offre :");

    if (title && location && duration) {
        const offersContainer = document.querySelector('.offers-container');
        const newOffer = document.createElement('div');
        newOffer.className = 'offer';
        newOffer.innerHTML = `
            <h3>${title}</h3>
            <p>Lieu : ${location}</p>
            <p>Durée : ${duration}</p>
            <button class="apply" onclick="apply('${title}')">Postuler</button>
            <button class="edit" onclick="editOffer('${title}')">Modifier l'Offre</button>
            <button class="delete" onclick="deleteOffer('${title}')">Supprimer</button>
            <button onclick="showDetails('${title}')">Voir Détails</button>
        `;
        offersContainer.appendChild(newOffer);
    } else {
        alert("Tous les champs doivent être remplis.");
    }
}






// script.js

// Fonction pour récupérer les paramètres de l'URL
function getQueryParams() {
    const params = {};
    const queryString = window.location.search.substring(1);
    const regex = /([^&=]+)=([^&]*)/g;
    let match;

    while (match = regex.exec(queryString)) {
        params[decodeURIComponent(match[1])] = decodeURIComponent(match[2]);
    }
    return params;
}

// Afficher les détails de l'offre
window.onload = function() {
    const params = getQueryParams();
    if (params.title) {
        document.getElementById('offer-title').innerText = params.title;
        document.getElementById('offer-location').innerText = `Lieu : ${params.location}`;
        document.getElementById('offer-duration').innerText = `Durée : ${params.duration}`;
    }
};
