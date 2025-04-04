    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier la session utilisateur
        fetch("<?php echo $basePath; ?>/app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les boutons login/logout
                const loginBtn = document.getElementById('login-Bouton');
                const logoutBtn = document.getElementById('logout-Bouton');
                const mobileLoginBtn = document.getElementById('mobile-login-Bouton');
                const mobileLogoutBtn = document.getElementById('mobile-logout-Bouton');
                
                if (data.logged_in) {
                    // Utilisateur connecté
                    if (loginBtn) loginBtn.style.display = 'none';
                    if (logoutBtn) logoutBtn.style.display = 'inline-flex';
                    if (mobileLoginBtn) mobileLoginBtn.style.display = 'none';
                    if (mobileLogoutBtn) mobileLogoutBtn.style.display = 'block';
                    
                    // Afficher le message de bienvenue
                    const welcomeMsg = document.getElementById('welcome-message');
                    if (welcomeMsg) {
                        welcomeMsg.textContent = 'Bonjour ' + data.nom + ' ' + data.prenom;
                    }
                    
                    // Pour les étudiants spécifiquement
                    if (parseInt(data.utilisateur) === 0) {
                        const dashboardLink = document.getElementById('page-dashboard');
                        const mobileDashboardLink = document.getElementById('mobile-dashboard-link');
                        
                        if (dashboardLink) dashboardLink.style.display = 'inline-flex';
                        if (mobileDashboardLink) mobileDashboardLink.style.display = 'block';
                    }
                    
                    // Pour les pilotes et administrateurs - Afficher le lien Gestion
                    if (parseInt(data.utilisateur) === 1 || parseInt(data.utilisateur) === 2) {
                        const gestionLink = document.getElementById('page-gestion');
                        const mobileGestionLink = document.getElementById('mobile-page-gestion');
                        
                        if (gestionLink) gestionLink.style.display = 'inline-flex';
                        if (mobileGestionLink) mobileGestionLink.style.display = 'block';
                    }
                    
                    // Pour les administrateurs uniquement - Afficher le lien Admin
                    if (parseInt(data.utilisateur) === 2) {
                        const adminLink = document.getElementById('page-admin');
                        const mobileAdminLink = document.getElementById('mobile-page-admin');
                        
                        if (adminLink) adminLink.style.display = 'inline-flex';
                        if (mobileAdminLink) mobileAdminLink.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error("Erreur lors de la vérification de la session:", error));
            
        // Mettre à jour l'année dans le copyright
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Faire défiler jusqu'au formulaire de candidature si le bouton est cliqué
        const btnPostuler = document.getElementById('btn-postuler');
        if (btnPostuler) {
            btnPostuler.addEventListener('click', function(e) {
                const candidatureForm = document.getElementById('candidature-form');
                if (candidatureForm) {
                    e.preventDefault();
                    candidatureForm.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }
    });