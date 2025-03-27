<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recherche d'emplois | LeBonPlan</title>
    <meta name="description" content="Parcourez des milliers d'offres d'emploi dans la technologie, le design, le marketing et plus encore." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <!-- Ajout du fichier CSS pour la wishlist -->
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
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link active">Emplois</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                <a href="admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
                <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
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
                    <a href="gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                    <a href="admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                    <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
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
                        <div class="filter-group">
                            <div class="filter-heading" data-toggle="salary-filters">
                                <h3>Rémunération</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </div>
                            <div class="filter-options" id="salary-filters">
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="0-50000" class="filter-checkbox" /> 0€ - 50 000€</label>
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="50000-100000" class="filter-checkbox" /> 50 000€ - 100 000€</label>
                                <label class="filter-option"><input type="checkbox" data-filter="salary" value="100000+" class="filter-checkbox" /> 100 000€ +</label>
                            </div>
                        </div>
                        
                        <!-- Section Wishlist pour les étudiants (sera affichée/masquée via JavaScript) -->
                        <div class="filter-group" id="wishlist-section" style="display: none;">
                            <div class="filter-heading">
                                <h3>Ma Wishlist</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>
                            <div class="filter-options">
                                <a href="wishlist" class="wishlist-nav-link">
                                    <svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    Voir ma wishlist
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="jobs-content">
                        <div id="jobs-list" class="jobs-list">
                            <!-- Les offres d'emploi seront chargées ici -->
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

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si l'utilisateur est un étudiant pour afficher la section wishlist
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                if (data.logged_in && parseInt(data.utilisateur) === 0) {
                    // L'utilisateur est un étudiant, afficher la section wishlist
                    const wishlistSection = document.getElementById('wishlist-section');
                    if (wishlistSection) {
                        wishlistSection.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error("Erreur lors de la vérification de la session:", error));
            
        // Fonction pour charger les offres d'emploi
        function loadJobs(searchParams = {}) {
            // Construire l'URL avec les paramètres de recherche
            let url = 'offres/search';
            if (Object.keys(searchParams).length > 0) {
                const queryParams = new URLSearchParams();
                for (const key in searchParams) {
                    if (searchParams[key]) {
                        queryParams.append(key, searchParams[key]);
                    }
                }
                url += '?' + queryParams.toString();
            }

            // Afficher un message de chargement
            document.getElementById('jobs-count').textContent = 'Chargement...';
            document.getElementById('jobs-list').innerHTML = '<div class="loading">Chargement des offres...</div>';

            // Effectuer la requête AJAX
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const jobsList = document.getElementById('jobs-list');
                    const noJobsFound = document.getElementById('no-jobs-found');
                    const jobsCount = document.getElementById('jobs-count');

                    // Mettre à jour le compteur d'offres
                    jobsCount.textContent = data.length + ' offres trouvées';

                    // Vider la liste des offres
                    jobsList.innerHTML = '';

                    if (data.length === 0) {
                        // Afficher le message "Aucun emploi trouvé"
                        noJobsFound.classList.remove('hide');
                        jobsList.classList.add('hide');
                    } else {
                        // Cacher le message "Aucun emploi trouvé"
                        noJobsFound.classList.add('hide');
                        jobsList.classList.remove('hide');

                        // Afficher les offres
                        data.forEach(job => {
                            const jobCard = document.createElement('div');
                            jobCard.className = 'job-card';
                            
                            // Formater la date
                            const date = new Date(job.date_offre);
                            const formattedDate = date.toLocaleDateString('fr-FR', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });
                            
                            // Formater la rémunération
                            const salary = new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'EUR',
                                maximumFractionDigits: 0
                            }).format(job.remuneration);
                            
                            // Créer les compétences sous forme de badges
                            const skills = job.competences.split(',').map(skill => 
                                `<span class="job-skill">${skill.trim()}</span>`
                            ).join('');
                            
                            // Vérifier si l'utilisateur est un étudiant pour afficher le bouton wishlist
                            fetch("app/views/login/session.php")
                                .then(response => response.json())
                                .then(sessionData => {
                                    const isStudent = sessionData.logged_in && parseInt(sessionData.utilisateur) === 0;
                                    
                                    // Préparer les boutons d'action
                                    let actionButtons = `<a href="#" class="button button-sm button-outline">Postuler</a>`;
                                    
                                    // Ajouter le bouton wishlist si l'utilisateur est un étudiant
                                    if (isStudent) {
                                        actionButtons += `
                                            <form action="wishlist/add" method="POST" style="display: inline-block; margin-left: 10px;">
                                                <input type="hidden" name="item_id" value="${job.id}">
                                                <button type="submit" class="wishlist-button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width
