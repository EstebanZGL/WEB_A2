<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recherche d'emplois | LeBonPlan</title>
    <meta name="description" content="Parcourez des milliers d'offres d'emploi dans la technologie, le design, le marketing et plus encore." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css"/>
    <link rel="stylesheet" href="public/css/offres.css"/>
    <link rel="stylesheet" href="public/css/wishlist.css"/>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    

</head>
<body>
    <div id="app">
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link active">Emplois</a>
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
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link active">Emplois</a>
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
            <div class="container">
                <div class="jobs-header">
                    <h1 class="jobs-title" id="jobs-title">Tous les Emplois</h1>
                    <p class="jobs-count" id="jobs-count">Recherche en cours...</p>
                </div>
                <div class="jobs-search">
                    <form class="search-form" id="search-form">
                        <div class="search-input-group">
                            <input type="text" placeholder="Titre du poste, mot-clé ou entreprise" id="job-search" name="jobTitle" class="search-input" />
                        </div>
                        <div class="search-input-group">
                            <input type="text" placeholder="Lieu ou entreprise" id="location-search" name="location" class="search-input" />
                        </div>
                        <button type="submit" class="button button-primary button-glow">Rechercher</button>
                    </form>
                </div>
                <div class="active-filters" id="active-filters"></div>
                <div class="jobs-layout">
                    <div class="jobs-sidebar">
                        <div class="filters-header">
                            <h2 class="filters-title">Filtres</h2>
                            <button id="clear-filters" class="clear-filters-btn">Tout effacer</button>
                        </div>
                        
                        <!-- Filtre par ville -->
                        <div class="filter-group">
                            <div class="filter-heading" data-toggle="city-filters">
                                <h3>Ville</h3>
                                <span class="iconify" data-icon="mdi:chevron-down" width="16" height="16"></span>
                            </div>
                            <div class="filter-options" id="city-filters">
                                <!-- Les options seront chargées dynamiquement via JavaScript -->
                                <div id="city-filters-loading">Chargement des villes...</div>
                            </div>
                        </div>
                        
                        <!-- Filtre par famille d'emploi -->
                        <div class="filter-group">
                            <div class="filter-heading" data-toggle="job-family-filters">
                                <h3>Famille d'emploi</h3>
                                <span class="iconify" data-icon="mdi:chevron-down" width="16" height="16"></span>
                            </div>
                            <div class="filter-options" id="job-family-filters">
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="informatique" class="filter-checkbox" /> Informatique & Tech</label>
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="btp" class="filter-checkbox" /> BTP & Construction</label>
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="finance" class="filter-checkbox" /> Finance & Comptabilité</label>
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="marketing" class="filter-checkbox" /> Marketing & Communication</label>
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="sante" class="filter-checkbox" /> Santé & Médical</label>
                                <label class="filter-option"><input type="checkbox" data-filter="job-family" value="autre" class="filter-checkbox" /> Autre</label>
                            </div>
                        </div>
                        
                        <!-- Filtre par salaire -->
                        <div class="filter-group">
                            <div class="filter-heading" data-toggle="salary-filters">
                                <h3>Rémunération</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </div>
                            <div class="filter-options" id="salary-filters">
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="0-500" class="filter-checkbox" /> 0€ - 500€</label>
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="500-1000" class="filter-checkbox" /> 500€ - 1000€</label>
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="1000+" class="filter-checkbox" /> 1000€ +</label>
                            </div>
                        </div>
                        
                        <!-- Bouton pour réinitialiser tous les filtres -->
                        <button id="reset-all-filters" class="reset-filters-btn">
                            <span class="iconify" data-icon="mdi:refresh" width="16" height="16"></span> 
                            Réinitialiser tous les filtres
                        </button>
                    </div>
                    <div class="jobs-content">
                        <div id="jobs-list" class="jobs-list">
                            <!-- Les offres d'emploi seront chargées ici -->
                        </div>
                        <div id="pagination" class="pagination">
                            <!-- La pagination sera générée ici -->
                        </div>
                        <div id="no-jobs-found" class="no-jobs-found hide">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <h3>Aucun emploi trouvé</h3>
                            <p>Nous n'avons pas trouvé d'emplois correspondant à vos critères de recherche.</p>
                            <button id="reset-filters" class="button button-secondary">Effacer les filtres</button>
                        </div>
                    </div>
                </div>
            </div>
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
    </div>

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script src="public/js/offres.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>