<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Trouvez votre prochain emploi</title>
    <meta name="description" content="LeBonPlan vous aide à trouver votre prochain emploi dans la technologie, le design, le marketing et plus encore." />
    <link rel="stylesheet" href="/public/css/style.css" />
    <link rel="stylesheet" href="/public/css/responsive-complete.css">
    <link rel="stylesheet" href="/public/css/wishlist.css">
    <link rel="stylesheet" href="/public/css/cookies.css">  <!-- Ajouter cette ligne -->
    <!-- Ajout d'Iconify pour les icônes -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
    <style>
        /* Styles pour les cartes d'offres sur la page d'accueil */
        .job-card {
            cursor: pointer;
            position: relative;
            background-color: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Reste des styles... */
    </style>
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <!-- Contenu du menu mobile... -->
        </div>
        <header class="navbar">
            <!-- Contenu de l'en-tête... -->
        </header>

        <main>
            <!-- Contenu principal... -->
        </main>

        <footer class="footer">
            <!-- Contenu du pied de page... -->
        </footer>
    </div>

 <!-- À la fin du fichier, juste avant la fermeture du body -->
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si l'utilisateur est connecté et son type
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                console.log("Session data:", data); // Débogage
                if (data.logged_in) {
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
    });
    </script>
    
    <!-- Charger les scripts -->
    <script src="public/js/app.js"></script>
    <script src="public/js/offres-alaune.js"></script>
    
    <!-- Ajouter juste avant la fermeture du body -->
    <?php include __DIR__ . '/../components/cookie-consent.php'; ?>
</body>
</html>