document.addEventListener("DOMContentLoaded", function () {
    fetch("app/views/login/session.php")
        .then(response => response.json())
        .then(data => {
            const loginBouton = document.getElementById("login-Bouton");
            const logoutBouton = document.getElementById("logout-Bouton");
            const welcomeMessage = document.getElementById("welcome-message");
            const pageGestion = document.getElementById("page-gestion");
            const pageAdmin= document.getElementById("page-admin");
            const mobilePageGestion = document.getElementById("mobile-page-gestion");
            const mobilePageAdmin = document.getElementById("mobile-page-admin");
            const mobileLoginBouton = document.getElementById("mobile-login-Bouton");
            const mobileLogoutBouton = document.getElementById("mobile-logout-Bouton");

            if (data.logged_in) {
                loginBouton.style.display = "none";
                logoutBouton.style.display = "inline-block";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "none";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "inline-block";

                // Afficher un message de bienvenue en fonction du type d'utilisateur
                let utilisateurMessage;
                const userType = parseInt(data.utilisateur);
                
                switch (userType) {
                    case 0: // Étudiant
                        utilisateurMessage = "Étudiant";
                        welcomeMessage.classList.add('etudiant');
                        
                        // Ajouter le lien vers la wishlist uniquement pour les étudiants
                        // et seulement sur la page des offres
                        if (window.location.pathname.includes('offres')) {
                            addWishlistLinks();
                        }
                        break;
                    case 1:
                        utilisateurMessage = "Pilote";
                        welcomeMessage.classList.add('pilote');
                        pageGestion.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        break;
                    case 2:
                        utilisateurMessage = "Admin";
                        welcomeMessage.classList.add('admin');
                        pageGestion.style.display = "inline-block";
                        pageAdmin.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        if (mobilePageAdmin) mobilePageAdmin.style.display = "inline-block";
                        break;
                    default:
                        utilisateurMessage = "Bienvenue !";
                }
                
                welcomeMessage.textContent = utilisateurMessage; // Met à jour le message de bienvenue
                welcomeMessage.style.display = "inline-block"; // Affiche le message
            } else {
                loginBouton.style.display = "inline-block";
                logoutBouton.style.display = "none";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "inline-block";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "none";
                welcomeMessage.style.display = "none"; // Cache le message de bienvenue
                if (pageGestion) pageGestion.style.display = "none";
                if (pageAdmin) pageAdmin.style.display = "none"; // Cache la page administrateur
                if (mobilePageGestion) mobilePageGestion.style.display = "none";
                if (mobilePageAdmin) mobilePageAdmin.style.display = "none";
                
                // Supprimer le lien wishlist s'il existe
                removeWishlistLinks();
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de la session :", error));
    

});
