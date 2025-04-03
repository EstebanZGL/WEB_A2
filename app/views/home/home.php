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
        
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .job-card-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .job-card-image {
            height: 120px;
            overflow: hidden;
        }
        
        .job-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .job-card-content {
            padding: 15px;
        }
        
        .job-title {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
        }
        
        .job-location, .job-company, .job-salary {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #b0b0b0;
        }
        
        .job-location .iconify, .job-company .iconify, .job-salary .iconify {
            margin-right: 8px;
            color: #007bff;
        }
        
        .job-card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .job-date {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #b0b0b0;
        }
        
        .job-date .iconify {
            margin-right: 5px;
            color: #007bff;
        }
        
        .no-jobs {
            text-align: center;
            padding: 20px;
            color: #b0b0b0;
        }
    </style>
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
    <script src="public/js/featured-jobs.js"></script>
</body>
</html>