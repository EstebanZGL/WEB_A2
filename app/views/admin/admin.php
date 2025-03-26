<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Administrateur</title>
    <meta name="description" content="Recherchez et postulez à des milliers d'offres d'emploi dans la technologie, le design, le marketing et plus encore." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
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
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link">Emplois</a>
                <a href="gestion" class="mobile-nav-link">Gestion</a>
                <a href="admin" class="mobile-nav-link active" id="mobile-page-admin">Administrateur</a>
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
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="gestion" class="nav-link">Gestion</a>
                    <a href="admin" class="nav-link active" id="page-admin">Administrateur</a>
                </nav>
                <div id="user-status">
                    <a href="login" id="login-Bouton" class="button button-outline button-glow">Connexion</a>
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow" style="display:none;">Déconnexion</a>
                </div>
            
                <script src="public/js/app.js"></script>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>
        
        <main>
            <section class="hero">
                <div class="hero-overlay"></div>
                <div class="container">
                    <h1 class="hero-title">Bienvenue dans la <span class="gradient-text">Page Administrateur</span></h1>
                    <p class="hero-subtitle">Gérez les offres d'emploi, les utilisateurs et plus encore.</p>
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
                            <li><a href="jobs" class="footer-link">Parcourir les Emplois</a></li>
                            <li><a href="#" class="footer-link">Ressources de Carrière</a></li>
                        </ul>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Entreprise</h3>
                        <ul>
                            <li><a href="#" class="footer-link">À Propos de Nous</a></li>
                            <li><a href="#" class="footer-link">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="public/js/mobile-menu.js"></script>
    <script>
        // Synchroniser les états de connexion entre le menu mobile et le menu normal
        document.addEventListener('DOMContentLoaded', function() {
            // Synchroniser les liens de gestion et admin
            const pageGestion = document.getElementById('page-gestion');
            const mobilePageGestion = document.getElementById('mobile-page-gestion');
            const pageAdmin = document.getElementById('page-admin');
            const mobilePageAdmin = document.getElementById('mobile-page-admin');
            
            if (pageGestion && mobilePageGestion) {
                if (pageGestion.style.display !== 'none') {
                    mobilePageGestion.style.display = 'block';
                }
            }
            
            if (pageAdmin && mobilePageAdmin) {
                if (pageAdmin.style.display !== 'none') {
                    mobilePageAdmin.style.display = 'block';
                }
            }
            
            // Synchroniser les boutons de connexion/déconnexion
            const loginBtn = document.getElementById('login-Bouton');
            const mobileLoginBtn = document.getElementById('mobile-login-Bouton');
            const logoutBtn = document.getElementById('logout-Bouton');
            const mobileLogoutBtn = document.getElementById('mobile-logout-Bouton');
            
            if (loginBtn && mobileLoginBtn) {
                if (loginBtn.style.display === 'none') {
                    mobileLoginBtn.style.display = 'none';
                }
            }
            
            if (logoutBtn && mobileLogoutBtn) {
                if (logoutBtn.style.display !== 'none') {
                    mobileLogoutBtn.style.display = 'block';
                }
            }
        });
    </script>
</body>
</html>