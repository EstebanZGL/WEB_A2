    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si l'utilisateur est connecté et son type
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                console.log("Session data:", data);
                if (data.logged_in) {
                    if (parseInt(data.utilisateur) === 0) {
                        const dashboardLink = document.getElementById('page-dashboard');
                        const mobileDashboardLink = document.getElementById('mobile-page-dashboard');
                        
                        if (dashboardLink) dashboardLink.style.display = 'inline-flex';
                        if (mobileDashboardLink) mobileDashboardLink.style.display = 'block';
                    } else {
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
        
        // Rediriger le formulaire de recherche vers la page des offres
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const jobTitle = document.getElementById('job-search').value;
            const location = document.getElementById('location-search').value;
            
            let url = 'offres';
            if (jobTitle || location) {
                url += '?';
                if (jobTitle) {
                    url += 'jobTitle=' + encodeURIComponent(jobTitle);
                }
                if (jobTitle && location) {
                    url += '&';
                }
                if (location) {
                    url += 'location=' + encodeURIComponent(location);
                }
            }
            
            window.location.href = url;
        });

        // Gestion des cookies
        if (!localStorage.getItem('cookieConsent')) {
            setTimeout(function() {
                document.getElementById('cookieConsent').classList.add('show');
            }, 1000);
        }
    });

    function acceptCookies() {
        localStorage.setItem('cookieConsent', 'accepted');
        document.getElementById('cookieConsent').classList.remove('show');
    }

    function declineCookies() {
        localStorage.setItem('cookieConsent', 'declined');
        document.getElementById('cookieConsent').classList.remove('show');
    }