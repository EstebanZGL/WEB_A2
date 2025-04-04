document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si l'utilisateur est connecté et son type
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                console.log("Session data:", data); // Débogage
                if (data.logged_in) {
                    // Afficher le bouton de déconnexion et masquer le bouton de connexion
                    document.getElementById('login-Bouton').style.display = 'none';
                    document.getElementById('logout-Bouton').style.display = 'inline-flex';
                    document.getElementById('mobile-login-Bouton').style.display = 'none';
                    document.getElementById('mobile-logout-Bouton').style.display = 'inline-flex';
                    
                    // Si l'utilisateur est un étudiant (utilisateur = 0)
                    if (parseInt(data.utilisateur) === 0) {
                        // Afficher le lien dashboard pour les étudiants
                        const dashboardLink = document.getElementById('page-dashboard');
                        const mobileDashboardLink = document.getElementById('mobile-page-dashboard');
                        
                        if (dashboardLink) dashboardLink.style.display = 'inline-flex';
                        if (mobileDashboardLink) mobileDashboardLink.style.display = 'block';
                    }
                    // Si l'utilisateur est un administrateur ou autre type d'utilisateur
                    else {
                        // Afficher les liens de gestion pour les administrateurs
                        const gestionLink = document.getElementById('page-gestion');
                        const mobileGestionLink = document.getElementById('mobile-page-gestion');
                        
                        if (gestionLink) gestionLink.style.display = 'inline-flex';
                        if (mobileGestionLink) mobileGestionLink.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error("Erreur lors de la vérification de la session:", error));
            
        // Mettre à jour l'année dans le copyright
        document.getElementById('current-year').textContent = new Date().getFullYear();
    });