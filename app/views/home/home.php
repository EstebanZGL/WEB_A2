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
    <link rel="stylesheet" href="/public/css/cookies.css">
    <!-- Ajout d'Iconify pour les icônes -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
    <link rel="stylesheet" href="/public/css/alaune.css">
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
                        <a href="offres?q=developpeur" class="popular-link">Développeur</a>
                        <a href="offres?q=designer" class="popular-link">Designer</a>
                        <a href="offres?q=technicien" class="popular-link">Technicien</a>
                        <a href="offres?q=consultant" class="popular-link">Consultant</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                    <h2 class="section-title">Offres <span class="accent-text">Mises en Avant</span></h2>
                    <div class="jobs-grid" id="featured-jobs">
                        <!-- Les offres à la une seront chargées ici via JavaScript -->
                        <div class="loading">Chargement des offres...</div>
                    </div>
                </div>
            </section>

            <section class="section section-dark">
                <div class="container">
                    <h2 class="section-title section-title-center">Parcourir par <span class="accent-text-pink">Catégorie</span></h2>
                    <div class="categories-grid">
                        <a href="offres?q=technology" class="category-card">Technologie</a>
                        <a href="offres?q=design" class="category-card">Design</a>
                        <a href="offres?q=marketing" class="category-card">Marketing</a>
                        <a href="offres?q=finance" class="category-card">Finance</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                    <div class="cta-card">
                        <h2 class="cta-title">Prêt à faire le prochain pas dans votre carrière ?</h2>
                        <p class="cta-text">Rejoignez des milliers de chercheurs d'emploi qui ont trouvé leur emploi de rêve grâce à notre plateforme.</p>
                        <a href="offres" class="button button-primary button-glow">Trouver des Emplois Maintenant</a>
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
                            <li><a href="mentions-legales" class="footer-link">Mentions Légales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>

        <!-- Composant Cookie Consent -->
        <div class="cookie-consent" id="cookieConsent">
            <div class="cookie-content">
                <div class="cookie-text">
                    <h3>🍪 Paramètres des cookies</h3>
                    <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. Ces cookies sont essentiels pour le bon fonctionnement du site et la sécurité de vos données.</p>
                </div>
                <div class="cookie-actions">
                    <button class="cookie-btn cookie-btn-accept" onclick="acceptCookies()">
                        Accepter
                    </button>
                    <button class="cookie-btn cookie-btn-decline" onclick="declineCookies()">
                        Refuser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src ="public/js/cookie.js"></script>
    <script src="public/js/mobile-menu.js"></script>      
    <script src="public/js/app.js"></script>
    <script src="public/js/offres-alaune.js"></script>
</body>
</html>