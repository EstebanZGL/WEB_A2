<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recherche d'emplois | LeBonPlan</title>
    <meta name="description" content="Parcourez des milliers d'offres d'emploi dans la technologie, le design, le marketing et plus encore." />
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
                <a href="offres" class="mobile-nav-link active">Emplois</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                <a href="admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
                            
                            jobCard.innerHTML = `
                                <div class="job-header">
                                    <h3 class="job-title">${job.titre}</h3>
                                    <span class="job-company">${job.entreprise}</span>
                                </div>
                                <div class="job-body">
                                    <p class="job-description">${job.description}</p>
                                    <div class="job-skills">${skills}</div>
                                </div>
                                <div class="job-footer">
                                    <div class="job-meta">
                                        <span class="job-salary">${salary}/an</span>
                                        <span class="job-date">Publié le ${formattedDate}</span>
                                        <span class="job-applicants">${job.nb_postulants} postulant(s)</span>
                                    </div>
                                    <a href="#" class="button button-sm button-outline">Postuler</a>
                                </div>
                            `;
                            
                            jobsList.appendChild(jobCard);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des offres:', error);
                    document.getElementById('jobs-count').textContent = 'Erreur de chargement';
                    document.getElementById('jobs-list').innerHTML = '<div class="error">Une erreur est survenue lors du chargement des offres.</div>';
                });
        }
        // Charger les offres au chargement de la page
        loadJobs();

        // Gérer la soumission du formulaire de recherche
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchParams = {
                jobTitle: document.getElementById('job-search').value,
                location: document.getElementById('location-search').value
            };
            
            loadJobs(searchParams);
        });

        // Gérer les filtres
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const filters = {};
                
                // Récupérer les valeurs des filtres cochés
                document.querySelectorAll('.filter-checkbox:checked').forEach(checked => {
                    const filterType = checked.dataset.filter;
                    if (!filters[filterType]) {
                        filters[filterType] = [];
                    }
                    filters[filterType].push(checked.value);
                });
                
                // Récupérer les valeurs de recherche
                const searchParams = {
                    jobTitle: document.getElementById('job-search').value,
                    location: document.getElementById('location-search').value,
                    filters: filters
                };
                
                loadJobs(searchParams);
            });
        });

        // Gérer le bouton "Tout effacer"
        document.getElementById('clear-filters').addEventListener('click', function() {
            // Décocher tous les filtres
            document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Vider les champs de recherche
            document.getElementById('job-search').value = '';
            document.getElementById('location-search').value = '';
            
            // Recharger les offres sans filtres
            loadJobs();
        });

        // Gérer le bouton "Effacer les filtres" dans le message "Aucun emploi trouvé"
        document.getElementById('reset-filters').addEventListener('click', function() {
            // Décocher tous les filtres
            document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Vider les champs de recherche
            document.getElementById('job-search').value = '';
            document.getElementById('location-search').value = '';
            
            // Recharger les offres sans filtres
            loadJobs();
        });

        // Gérer l'affichage/masquage des filtres
        document.querySelectorAll('.filter-heading').forEach(heading => {
            heading.addEventListener('click', function() {
                const targetId = this.dataset.toggle;
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.classList.toggle('show');
                    this.classList.toggle('active');
                }
            });
        });
    });
    </script>
