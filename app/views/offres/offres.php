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
    <!-- Ajout d'Iconify pour les icônes -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
    <style>
        /* Styles supplémentaires pour les cartes d'offres */
        .job-card {
            cursor: pointer;
            position: relative;
        }
        
        .job-card-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        .job-salary {
            display: inline-flex;
            align-items: center;
            width: fit-content;
        }
        
        .wishlist-button {
            position: relative;
            z-index: 2;
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
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link active">Emplois</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                <a href="admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
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
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link active">Emplois</a>
                    <a href="gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                    <a href="admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                    <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
                    <a href="wishlist" class="nav-link wishlist-icon-link" id="wishlist-link" style="display:none;" title="Ma Wishlist">
                        <span class="iconify" data-icon="mdi:heart" width="20" height="20"></span>
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
                                <span class="iconify" data-icon="mdi:chevron-down" width="16" height="16"></span>
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
                            </div>
                            <div class="filter-options">
                                <a href="wishlist" class="wishlist-nav-link">
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
                            <span class="iconify" data-icon="mdi:magnify" width="48" height="48"></span>
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
                console.log("Session data:", data); // Débogage
                if (data.logged_in && parseInt(data.utilisateur) === 0) {
                    // L'utilisateur est un étudiant, afficher la section wishlist et les liens
                    const wishlistSection = document.getElementById('wishlist-section');
                    const wishlistLink = document.getElementById('wishlist-link');
                    const mobileWishlistLink = document.getElementById('mobile-wishlist-link');
                    
                    if (wishlistSection) {
                        wishlistSection.style.display = 'block';
                    }
                    
                    if (wishlistLink) {
                        wishlistLink.style.display = 'inline-flex';
                    }
                    
                    if (mobileWishlistLink) {
                        mobileWishlistLink.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error("Erreur lors de la vérification de la session:", error));
            
        // Fonction pour charger les offres d'emploi
        function loadJobs(searchParams = {}) {
            // Construire l'URL avec les paramètres de recherche
            let url = 'offres/search';
            
            if (Object.keys(searchParams).length > 0) {
                const queryParams = [];
                
                if (searchParams.jobTitle) {
                    queryParams.push(`jobTitle=${encodeURIComponent(searchParams.jobTitle)}`);
                }
                
                if (searchParams.location) {
                    queryParams.push(`location=${encodeURIComponent(searchParams.location)}`);
                }
                
                if (searchParams.filters) {
                    for (const [filterType, values] of Object.entries(searchParams.filters)) {
                        if (Array.isArray(values) && values.length > 0) {
                            values.forEach(value => {
                                queryParams.push(`filters[${filterType}][]=${encodeURIComponent(value)}`);
                            });
                        }
                    }
                }
                
                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                }
            }
            
            // Effectuer la requête AJAX
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Vérifier si l'utilisateur est un étudiant pour afficher les boutons wishlist
                    fetch("app/views/login/session.php")
                        .then(response => response.json())
                        .then(sessionData => {
                            if (sessionData.logged_in && parseInt(sessionData.utilisateur) === 0) {
                                displayJobsWithWishlist(data);
                            } else {
                                displayJobsWithoutWishlist(data);
                            }
                            
                            // Mettre à jour le compteur d'offres
                            document.getElementById('jobs-count').textContent = `${data.length} offre(s) trouvée(s)`;
                            
                            // Afficher ou masquer le message "Aucun emploi trouvé"
                            if (data.length === 0) {
                                document.getElementById('jobs-list').classList.add('hide');
                                document.getElementById('no-jobs-found').classList.remove('hide');
                            } else {
                                document.getElementById('jobs-list').classList.remove('hide');
                                document.getElementById('no-jobs-found').classList.add('hide');
                            }
                        })
                        .catch(error => {
                            console.error("Erreur lors de la vérification de la session:", error);
                            displayJobsWithoutWishlist(data);
                        });
                })
                .catch(error => {
                    console.error("Erreur lors du chargement des offres:", error);
                    document.getElementById('jobs-count').textContent = "Erreur lors du chargement des offres";
                });
        }
        
        // Fonction pour afficher les offres avec le bouton wishlist
        function displayJobsWithWishlist(jobs) {
            const jobsList = document.getElementById('jobs-list');
            jobsList.innerHTML = '';
            
            jobs.forEach(job => {
                const jobCard = document.createElement('div');
                jobCard.className = 'job-card';
                
                // Formater la date de publication
                const datePublication = new Date(job.date_publication);
                const formattedDate = datePublication.toLocaleDateString('fr-FR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Créer la carte avec la nouvelle structure
                jobCard.innerHTML = `
                    <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                    <div class="job-card-image">
                        <img src="public/images/job-placeholder.png" alt="${job.titre}" class="job-image">
                    </div>
                    <div class="job-card-content">
                        <h3 class="job-title">${job.titre}</h3>
                        <div class="job-location">
                            <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                            <span>${job.ville || 'Non spécifiée'}</span>
                        </div>
                        <div class="job-company">
                            <span class="iconify" data-icon="mdi:office-building" width="16" height="16"></span>
                            <span>${job.entreprise}</span>
                        </div>
                        <div class="job-salary">
                            <span class="iconify" data-icon="mdi:currency-eur" width="16" height="16"></span>
                            <span>${job.remuneration}€/an</span>
                        </div>
                    </div>
                    <div class="job-card-actions">
                        <button class="wishlist-button" data-job-id="${job.id}" title="Ajouter à ma wishlist">
                            <span class="iconify" data-icon="mdi:heart-outline" width="24" height="24"></span>
                        </button>
                        <div class="job-date">
                            <span class="iconify" data-icon="mdi:calendar" width="16" height="16"></span>
                            <span>${formattedDate}</span>
                        </div>
                    </div>
                `;
                
                jobsList.appendChild(jobCard);
            });
            
            // Ajouter les écouteurs d'événements pour les boutons wishlist
            document.querySelectorAll('.wishlist-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Empêcher la propagation de l'événement pour éviter de cliquer sur la carte
                    e.stopPropagation();
                    const jobId = this.getAttribute('data-job-id');
                    addToWishlist(jobId, this);
                });
            });
        }
        
        // Fonction pour afficher les offres sans le bouton wishlist en cas d'erreur
        function displayJobsWithoutWishlist(jobs) {
            const jobsList = document.getElementById('jobs-list');
            jobsList.innerHTML = '';
            
            jobs.forEach(job => {
                const jobCard = document.createElement('div');
                jobCard.className = 'job-card';
                
                // Formater la date de publication
                const datePublication = new Date(job.date_publication);
                const formattedDate = datePublication.toLocaleDateString('fr-FR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Créer la carte avec la nouvelle structure mais sans bouton wishlist
                jobCard.innerHTML = `
                    <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                    <div class="job-card-image">
                        <img src="public/images/job-placeholder.png" alt="${job.titre}" class="job-image">
                    </div>
                    <div class="job-card-content">
                        <h3 class="job-title">${job.titre}</h3>
                        <div class="job-location">
                            <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                            <span>${job.ville || 'Non spécifiée'}</span>
                        </div>
                        <div class="job-company">
                            <span class="iconify" data-icon="mdi:office-building" width="16" height="16"></span>
                            <span>${job.entreprise}</span>
                        </div>
                        <div class="job-salary">
                            <span class="iconify" data-icon="mdi:currency-eur" width="16" height="16"></span>
                            <span>${job.remuneration}€/an</span>
                        </div>
                    </div>
                    <div class="job-card-actions">
                        <div class="job-date">
                            <span class="iconify" data-icon="mdi:calendar" width="16" height="16"></span>
                            <span>${formattedDate}</span>
                        </div>
                    </div>
                `;
                
                jobsList.appendChild(jobCard);
            });
        }
        
        // Fonction pour ajouter une offre à la wishlist
        function addToWishlist(jobId, button) {
            const formData = new FormData();
            formData.append('item_id', jobId);
            
            fetch('wishlist/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Changer l'apparence du bouton pour indiquer que l'offre a été ajoutée
                    button.classList.add('added');
                    button.title = "Ajouté à votre wishlist";
                    // Changer l'icône pour indiquer que c'est ajouté
                    button.querySelector('.iconify').setAttribute('data-icon', 'mdi:heart');
                    
                    // Afficher un message de confirmation
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error("Erreur lors de l'ajout à la wishlist:", error);
                alert("Une erreur est survenue lors de l'ajout à la wishlist");
            });
        }
        
        // Charger les offres au chargement de la page
        loadJobs();
        
        // Gérer la soumission du formulaire de recherche
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jobTitle = document.getElementById('job-search').value;
            const location = document.getElementById('location-search').value;
            
            // Récupérer les filtres actifs
            const filters = {};
            
            // Filtres de salaire
            const salaryFilters = Array.from(document.querySelectorAll('input[data-filter="salary"]:checked')).map(input => input.value);
            if (salaryFilters.length > 0) {
                filters.salary = salaryFilters;
            }
            
            // Effectuer la recherche
            loadJobs({
                jobTitle: jobTitle,
                location: location,
                filters: filters
            });
            
            // Mettre à jour les filtres actifs affichés
            updateActiveFilters(jobTitle, location, filters);
        });
        
        // Fonction pour mettre à jour l'affichage des filtres actifs
        function updateActiveFilters(jobTitle, location, filters) {
            const activeFiltersContainer = document.getElementById('active-filters');
            activeFiltersContainer.innerHTML = '';
            
            if (jobTitle) {
                const filterTag = document.createElement('span');
                filterTag.className = 'filter-tag';
                filterTag.innerHTML = `
                    Titre: ${jobTitle}
                    <button class="remove-filter" data-type="jobTitle">&times;</button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            }
            
            if (location) {
                const filterTag = document.createElement('span');
                filterTag.className = 'filter-tag';
                filterTag.innerHTML = `
                    Lieu: ${location}
                    <button class="remove-filter" data-type="location">&times;</button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            }
            
            if (filters.salary && filters.salary.length > 0) {
                filters.salary.forEach(value => {
                    let label = '';
                    if (value === '0-50000') {
                        label = '0€ - 50 000€';
                    } else if (value === '50000-100000') {
                        label = '50 000€ - 100 000€';
                    } else if (value === '100000+') {
                        label = '100 000€ +';
                    }
                    
                    const filterTag = document.createElement('span');
                    filterTag.className = 'filter-tag';
                    filterTag.innerHTML = `
                        Salaire: ${label}
                        <button class="remove-filter" data-type="salary" data-value="${value}">&times;</button>
                    `;
                    activeFiltersContainer.appendChild(filterTag);
                });
            }
            
            // Ajouter les écouteurs d'événements pour les boutons de suppression de filtres
            document.querySelectorAll('.remove-filter').forEach(button => {
                button.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    const value = this.getAttribute('data-value');
                    
                    if (type === 'jobTitle') {
                        document.getElementById('job-search').value = '';
                    } else if (type === 'location') {
                        document.getElementById('location-search').value = '';
                    } else if (type === 'salary' && value) {
                        document.querySelector(`input[data-filter="salary"][value="${value}"]`).checked = false;
                    }
                    
                    // Relancer la recherche
                    document.getElementById('search-form').dispatchEvent(new Event('submit'));
                });
            });
        }
        
        // Gérer le clic sur le bouton "Effacer les filtres"
        document.getElementById('clear-filters').addEventListener('click', function() {
            // Réinitialiser le formulaire
            document.getElementById('search-form').reset();
            
            // Réinitialiser les filtres actifs
            document.getElementById('active-filters').innerHTML = '';
            
            // Recharger les offres sans filtres
            loadJobs();
        });
        
        // Gérer le clic sur le bouton "Effacer les filtres" dans le message "Aucun emploi trouvé"
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('clear-filters').click();
        });
        
        // Gérer l'affichage/masquage des sections de filtres
        document.querySelectorAll('.filter-heading').forEach(heading => {
            heading.addEventListener('click', function() {
                const targetId = this.getAttribute('data-toggle');
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.classList.toggle('open');
                    this.classList.toggle('open');
                }
            });
        });
        
        // Mettre à jour l'année dans le copyright
        document.getElementById('current-year').textContent = new Date().getFullYear();
    });
    </script>
    
    <!-- Charger le script app.js à la fin du body -->
    <script src="public/js/app.js"></script>
</body>
</html>
