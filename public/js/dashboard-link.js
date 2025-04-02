/**
 * Script pour ajouter un lien vers le tableau de bord pour les étudiants
 * Ce script vérifie si l'utilisateur est connecté en tant qu'étudiant
 * et ajoute un lien vers le tableau de bord dans la navigation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'utilisateur est connecté en tant qu'étudiant
    fetch("app/views/login/session.php")
        .then(response => response.json())
        .then(data => {
            // Si l'utilisateur est connecté et est un étudiant (utilisateur = 0)
            if (data.logged_in && parseInt(data.utilisateur) === 0) {
                // Ajouter le lien vers le tableau de bord dans la navigation principale
                const navbarNav = document.querySelector('.navbar-nav');
                const offresLink = document.querySelector('.navbar-nav a[href="offres"]');
                
                if (navbarNav && offresLink) {
                    // Créer le lien vers le tableau de bord (sans icône)
                    const dashboardLink = document.createElement('a');
                    dashboardLink.href = 'dashboard';
                    dashboardLink.className = 'nav-link';
                    dashboardLink.textContent = 'Tableau de bord'; // Texte simple sans icône
                    
                    // Insérer le lien après le lien "Emplois"
                    navbarNav.insertBefore(dashboardLink, offresLink.nextSibling);
                }
                
                // Ajouter le lien vers le tableau de bord dans le menu mobile
                const mobileNav = document.querySelector('.mobile-nav');
                const mobileOffresLink = document.querySelector('.mobile-nav a[href="offres"]');
                
                if (mobileNav && mobileOffresLink) {
                    // Créer le lien vers le tableau de bord pour le menu mobile (sans icône)
                    const mobileDashboardLink = document.createElement('a');
                    mobileDashboardLink.href = 'dashboard';
                    mobileDashboardLink.className = 'mobile-nav-link';
                    mobileDashboardLink.textContent = 'Tableau de bord'; // Texte simple sans icône
                    
                    // Insérer le lien après le lien "Emplois" dans le menu mobile
                    mobileNav.insertBefore(mobileDashboardLink, mobileOffresLink.nextSibling);
                }
            }
        })
        .catch(error => console.error("Erreur lors de la vérification de la session:", error));
});
