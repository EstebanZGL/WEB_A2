<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Trouvez votre prochain emploi</title>
    <meta name="description" content="LeBonPlan vous aide à trouver votre prochain emploi dans la technologie, le design, le marketing et plus encore." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <link rel="stylesheet" href="public/css/wishlist.css">
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="home" class="mobile-nav-link active">Accueil</a>
                <a href="offres" class="mobile-nav-link">Emplois</a>
                <a href="dashboard" class="mobile-nav-link" id="mobile-page-dashboard" style="display:none;">Tableau de bord</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                
                <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
                <a href="wishlist" class="mobile-nav-link" id="mobile-wishlist-link" style="display:none;">Ma Wishlist</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="login" id="mobile-login-Bouton" class="button button-primary button-glow">Connexion</a>
                    <a href="logout" id="mobile-logout-Bouton" class="button button-primary button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
        </div>
        
        <header class="navbar">
            <div class="container">
                <img src="public/images/logo.png" alt="D" width="150" height="170">
                
                <!-- Bouton Menu Mobile -->
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="navbar-nav">
                    <a href="home" class="nav-link active">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="dashboard" class="nav-link" id="page-dashboard" style="display:none;">Tableau de bord</a>
                    <a href="gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                    
                    <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
                    <a href="wishlist" class="nav-link wishlist-icon-link" id="wishlist-link" style="display:none;" title="Ma Wishlist">
                        <svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </a>
                </nav>

                <div id="user-status">
                    <a href="login" id="login-Bouton" class="button button-outline button-glow">Connexion</a>
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>

        <main>
            <section class="hero">
                <div class="hero-overlay"></div>
                <div class="container">
                    <h1 class="hero-title">Découvrez Votre Prochaine <span class="gradient-text">Opportunité</span></h1>
                    <p class="hero-subtitle">Recherchez et postulez à des milliers d'emplois dans la technologie, le design, le marketing et plus encore.</p>
                    <form class="search-form" id="search-form">
                        <input type="text" placeholder="Titre du poste, mot-clé ou entreprise" id="job-search" class="search-input" />
                        <input type="text" placeholder="Lieu" id="location-search" class="search-input" />
                        <button type="submit" class="button button-primary button-glow">Rechercher</button>
                    </form>
                    <div class="popular-searches">
                        <span>Recherches populaires:</span>
                        <a href="jobs?q=developer" class="popular-link">Développeur</a>
                        <a href="jobs?q=designer" class="popular-link">Designer</a>
                        <a href="jobs?q=marketing" class="popular-link">Marketing</a>
                        <a href="jobs?q=remote" class="popular-link">Télétravail</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                    <h2 class="section-title">Offres <span class="accent-text">Mises en Avant</span></h2>
                    <div class="jobs-grid" id="featured-jobs">
                        <!-- Jobs will be loaded here via JavaScript -->
                    </div>
                </div>
            </section>

            <section class="section section-dark">
                <div class="container">
                    <h2 class="section-title section-title-center">Parcourir par <span class="accent-text-pink">Catégorie</span></h2>
                    <div class="categories-grid">
                        <a href="jobs?q=technology" class="category-card">Technologie</a>
                        <a href="jobs?q=design" class="category-card">Design</a>
                        <a href="jobs?q=marketing" class="category-card">Marketing</a>
                        <a href="jobs?q=finance" class="category-card">Finance</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                    <div class="cta-card">
                        <h2 class="cta-title">Prêt à faire le prochain pas dans votre carrière ?</h2>
                        <p class="cta-text">Rejoignez des milliers de chercheurs d'emploi qui ont trouvé leur emploi de rêve grâce à notre plateforme.</p>
                        <a href="jobs" class="button button-primary button-glow">Trouver des Emplois Maintenant</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="home" class="footer-logo">
                            <img src="public/images/logo.png" alt="D" width="150" height="170">
                        </a>
                        <p class="footer-tagline">Votre passerelle vers des opportunités de carrière.</p>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Pour les Chercheurs d'Emploi</h3>
                        <ul>
                            <li><a href="offres" class="footer-link">Parcourir les Emplois</a></li>
                            <li><a href="#" class="footer-link">Ressources de Carrière</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
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
                        // Afficher les liens wishlist et dashboard pour les étudiants
                        const wishlistLink = document.getElementById('wishlist-link');
                        const mobileWishlistLink = document.getElementById('mobile-wishlist-link');
                        const dashboardLink = document.getElementById('page-dashboard');
                        const mobileDashboardLink = document.getElementById('mobile-page-dashboard');
                        
                        if (wishlistLink) wishlistLink.style.display = 'inline-flex';
                        if (mobileWishlistLink) mobileWishlistLink.style.display = 'block';
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
    </script>
    
    <!-- Charger le script app.js à la fin du body -->
    <script src="public/js/app.js"></script>
</body>
</html>