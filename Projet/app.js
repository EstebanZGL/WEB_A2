document.addEventListener("DOMContentLoaded", function () {
    fetch("session.php")
        .then(response => response.json())
        .then(data => {
            const loginButton = document.getElementById("login-button");
            const logoutButton = document.getElementById("logout-button");
            const welcomeMessage = document.getElementById("welcome-message");
            const pageAdmin= document.getElementById("page-admin");

            if (data.logged_in) {
                loginButton.style.display = "none";
                logoutButton.style.display = "inline-block";

                // Afficher un message de bienvenue en fonction du type d'utilisateur
                let utilisateurMessage;
                switch (data.utilisateur) {
                    case 0:
                        utilisateurMessage = "Étudiant";
                        welcomeMessage.classList.add('etudiant');
                        break;
                    case 1:
                        utilisateurMessage = "Pilote";
                        welcomeMessage.classList.add('pilote');
                        break;
                    case 2:
                        utilisateurMessage = "Admin";
                        welcomeMessage.classList.add('admin');
                        pageAdmin.style.display = "inline-block";
                        break;
                    default:
                        utilisateurMessage = "Bienvenue !";
                }

                
                welcomeMessage.textContent = utilisateurMessage; // Met à jour le message de bienvenue
                welcomeMessage.style.display = "inline-block"; // Affiche le message
            } else {
                loginButton.style.display = "inline-block";
                logoutButton.style.display = "none";
                welcomeMessage.style.display = "none"; // Cache le message de bienvenue
                pageAdmin.style.display = "none"; // Cache la page administrateur
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de la session :", error));
});
