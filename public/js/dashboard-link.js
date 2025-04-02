document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'utilisateur est un étudiant pour afficher le lien vers le dashboard
    fetch("app/views/login/session.php")
        .then(response => response.json())
        .then(data => {
            console.log("Session data:", data); // Débogage
            if (data.logged_in && parseInt(data.utilisateur) === 0) {
                // L'utilisateur est un étudiant, afficher les liens dashboard
                
                // Ajouter le lien dans la navigation principale
                const navbarNav = document.querySelector('.navbar-nav');
                if (navbarNav) {
                    // Créer le lien
                    const dashboardLink = document.createElement('a');
                    dashboardLink.href = 'dashboard';
                    dashboardLink.className = 'nav-link';
                    dashboardLink.textContent = 'Tableau de bord';
                    
                    // Insérer après le lien "Emplois"
                    const emploisLink = Array.from(navbarNav.querySelectorAll('.nav-link')).find(link => link.textContent === 'Emplois');
                    if (emploisLink) {
                        navbarNav.insertBefore(dashboardLink, emploisLink.nextSibling);
                    } else {
                        navbarNav.appendChild(dashboardLink);
                    }
                }
                
                // Ajouter le lien dans le menu mobile
                const mobileNav = document.querySelector('.mobile-nav');
                if (mobileNav) {
                    // Créer le lien
                    const mobileDashboardLink = document.createElement('a');
                    mobileDashboardLink.href = 'dashboard';
                    mobileDashboardLink.className = 'mobile-nav-link';
                    mobileDashboardLink.textContent = 'Tableau de bord';
                    
                    // Insérer après le lien "Emplois"
                    const mobileEmploisLink = Array.from(mobileNav.querySelectorAll('.mobile-nav-link')).find(link => link.textContent === 'Emplois');
                    if (mobileEmploisLink) {
                        mobileNav.insertBefore(mobileDashboardLink, mobileEmploisLink.nextSibling);
                    } else {
                        mobileNav.appendChild(mobileDashboardLink);
                    }
                }
            }
        })
        .catch(error => console.error("Erreur lors de la vérification de la session:", error));
});