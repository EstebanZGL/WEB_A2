<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres de Stage - CESI LeBonPlan</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="stylesheet" href="public/css/offres.css">
    <script src="https://code.iconify.design/2/2.1.0/iconify.min.js"></script>
</head>
<body>
    <!-- En-tête -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <a href="home">
                    <img src="public/images/logo.png" alt="CESI LeBonPlan Logo">
                </a>
            </div>
            <nav class="desktop-nav">
                <ul>
                    <li><a href="home">Accueil</a></li>
                    <li><a href="offres" class="active">Offres</a></li>
                    <li><a href="gestion">Gestion</a></li>
                    <li><a href="wishlist" id="wishlist-link" style="display: none;"><span class="iconify" data-icon="mdi:heart" style="color: #ff5a5f;"></span> Wishlist</a></li>
                    <li><a href="logout" class="logout-button">Déconnexion</a></li>
                </ul>
            </nav>
            <div class="mobile-menu-button">
                <span class="iconify" data-icon="mdi:menu" width="32" height="32"></span>
            </div>
        </div>
        <nav class="mobile-nav">
            <ul>
                <li><a href="home">Accueil</a></li>
                <li><a href="offres" class="active">Offres</a></li>
                <li><a href="gestion">Gestion</a></li>
                <li><a href="wishlist" id="mobile-wishlist-link" style="display: none;"><span class="iconify" data-icon="mdi:heart" style="color: #ff5a5f;"></span> Wishlist</a></li>
                <li><a href="logout" class="logout-button">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <!-- Bannière -->
    <section class="banner">
        <div class="container">
            <h1>Trouvez votre stage idéal</h1>
            <p>Explorez nos offres de stages et trouvez celle qui correspond à vos aspirations</p>
        </div>
    </section>

    <!-- Contenu principal -->
    <main>
        <div class="container">
            <!-- Section de recherche -->
            <section class="search-section">
                <form id="search-form" class="search-form">
                    <div class="search-inputs">
                        <div class="search-group">
                            <span class="iconify search-icon" data-icon="mdi:magnify" width="24" height="24"></span>
                            <input type="text" id="job-search" placeholder="Titre du poste, mots-clés...">
                        </div>
                        <div class="search-group">
                            <span class="iconify search-icon" data-icon="mdi:map-marker" width="24" height="24"></span>
                            <input type="text" id="location-search" placeholder="Ville, entreprise...">
                        </div>
                        <button type="submit" class="search-button">Rechercher</button>
                    </div>
                </form>
            </section>

            <!-- Section des filtres actifs -->
            <section class="active-filters-section">
                <div class="active-filters-container">
                    <h2>Filtres actifs</h2>
                    <div id="active-filters" class="active-filters">
                        <!-- Les filtres actifs seront ajoutés ici dynamiquement -->
                    </div>
                    <button id="reset-all-filters" class="reset-filters-button">Réinitialiser tous les filtres</button>
                </div>
            </section>

            <!-- Section principale avec filtres et offres -->
            <section class="main-content">
                <div class="filters-column">
                    <div class="filters-header">
                        <h2>Filtres</h2>
                        <button id="clear-filters" class="clear-filters-button">Effacer les filtres</button>
                    </div>

                    <!-- Filtre par ville -->
                    <div class="filter-section">
                        <h3 class="filter-heading open" data-toggle="city-filters">Ville</h3>
                        <div id="city-filters" class="filter-options open">
                            <!-- Les options de ville seront ajoutées ici dynamiquement -->
                            <p>Chargement des villes...</p>
                        </div>
                    </div>

                    <!-- Filtre par famille d'emploi -->
                    <div class="filter-section">
                        <h3 class="filter-heading open" data-toggle="job-family-filters">Famille d'emploi</h3>
                        <div id="job-family-filters" class="filter-options open">
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="informatique" class="filter-checkbox"> Informatique & Tech
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="btp" class="filter-checkbox"> BTP & Construction
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="finance" class="filter-checkbox"> Finance & Comptabilité
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="marketing" class="filter-checkbox"> Marketing & Communication
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="sante" class="filter-checkbox"> Santé & Médical
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="job-family" value="autre" class="filter-checkbox"> Autre
                            </label>
                        </div>
                    </div>

                    <!-- Filtre par salaire -->
                    <div class="filter-section">
                        <h3 class="filter-heading open" data-toggle="salary-filters">Salaire</h3>
                        <div id="salary-filters" class="filter-options open">
                            <label class="filter-option">
                                <input type="checkbox" data-filter="salary" value="0-50000" class="filter-checkbox"> 0€ - 50 000€
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="salary" value="50000-100000" class="filter-checkbox"> 50 000€ - 100 000€
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" data-filter="salary" value="100000+" class="filter-checkbox"> 100 000€ +
                            </label>
                        </div>
                    </div>
                </div>

                <div class="jobs-column">
                    <div class="jobs-header">
                        <h2>Offres disponibles</h2>
                        <p id="jobs-count">Chargement des offres...</p>
                    </div>

                    <!-- Section Wishlist (visible uniquement pour les étudiants) -->
                    <div id="wishlist-section" class="wishlist-section" style="display: none;">
                        <p>
                            <span class="iconify" data-icon="mdi:information" width="16" height="16"></span>
                            Vous pouvez ajouter des offres à votre wishlist en cliquant sur le cœur.
                        </p>
                    </div>

                    <!-- Liste des offres -->
                    <div id="jobs-list" class="jobs-list">
                        <!-- Les offres seront ajoutées ici dynamiquement -->
                    </div>

                    <!-- Message affiché si aucune offre n'est trouvée -->
                    <div id="no-jobs-found" class="no-jobs-found hide">
                        <div class="no-jobs-message">
                            <span class="iconify" data-icon="mdi:alert-circle-outline" width="48" height="48"></span>
                            <h3>Aucune offre trouvée</h3>
                            <p>Essayez de modifier vos critères de recherche ou de réinitialiser les filtres.</p>
                            <button id="reset-filters" class="reset-filters-button">Effacer les filtres</button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="pagination">
                        <!-- Les boutons de pagination seront ajoutés ici dynamiquement -->
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Pied de page -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="public/images/logo.png" alt="CESI LeBonPlan Logo">
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h3>Navigation</h3>
                        <ul>
                            <li><a href="home">Accueil</a></li>
                            <li><a href="offres">Offres</a></li>
                            <li><a href="gestion">Gestion</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Ressources</h3>
                        <ul>
                            <li><a href="#">Aide</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h3>Légal</h3>
                        <ul>
                            <li><a href="#">Mentions légales</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Conditions d'utilisation</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <span id="current-year">2023</span> CESI LeBonPlan. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour la pagination
    let currentPage = 1;
    let totalPages = 1;
    let allJobs = []; // Stocke toutes les offres récupérées
    const jobsPerPage = 4; // Nombre d'offres par page
    
    // Fonction pour déterminer quelle image utiliser en fonction du thème de l'offre
    function getJobImage(job) {
        const title = job.titre ? job.titre.toLowerCase() : '';
        const description = job.description ? job.description.toLowerCase() : '';
        const content = title + ' ' + description;
        
        // Vérifier les mots-clés pour déterminer la catégorie
        if (content.includes('informatique') || content.includes('développeur') || 
            content.includes('programmeur') || content.includes('logiciel') || 
            content.includes('web') || content.includes('code') || 
            content.includes('développement') || content.includes('java') || 
            content.includes('python') || content.includes('php')) {
            return 'public/images/info-img.png';
        } else if (content.includes('btp') || content.includes('construction') || 
                  content.includes('bâtiment') || content.includes('chantier') || 
                  content.includes('travaux') || content.includes('génie civil')) {
            return 'public/images/btp-img.png';
        } else if (content.includes('électronique') || content.includes('électrique') || 
                  content.includes('circuit') || content.includes('composant') || 
                  content.includes('système embarqué') || content.includes('s3e')) {
            return 'public/images/s3e-img.png';
        } else if (content.includes('généraliste') || content.includes('polyvalent') || 
                  content.includes('multi-compétences') || content.includes('divers')) {
            return 'public/images/gen-img.png';
        } else {
            // Image par défaut
            return 'public/images/job-placeholder.png';
        }
    }
    
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
    
    // Charger la liste des villes disponibles
    loadCities();
    
    // Fonction pour charger les villes disponibles
    function loadCities() {
        fetch('offres/cities')
            .then(response => response.json())
            .then(cities => {
                const cityFiltersContainer = document.getElementById('city-filters');
                cityFiltersContainer.innerHTML = '';
                
                if (cities.length === 0) {
                    cityFiltersContainer.innerHTML = '<p>Aucune ville disponible</p>';
                    return;
                }
                
                cities.forEach(city => {
                    if (city && city.trim() !== '') {
                        const label = document.createElement('label');
                        label.className = 'filter-option';
                        label.innerHTML = `<input type="checkbox" data-filter="city" value="${city}" class="filter-checkbox" /> ${city}`;
                        cityFiltersContainer.appendChild(label);
                    }
                });
                
                // Ajouter les écouteurs d'événements pour les nouveaux filtres
                document.querySelectorAll('#city-filters .filter-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        currentPage = 1; // Réinitialiser à la première page lors du changement de filtre
                        applyFilters();
                    });
                });
            })
            .catch(error => {
                console.error("Erreur lors du chargement des villes:", error);
                document.getElementById('city-filters').innerHTML = '<p>Erreur lors du chargement des villes</p>';
            });
    }
        
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
        
        // Afficher un indicateur de chargement
        document.getElementById('jobs-count').textContent = "Chargement...";
        
        // Effectuer la requête AJAX
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Stocker toutes les offres
                allJobs = data;
                
                // Calculer le nombre total de pages
                totalPages = Math.ceil(allJobs.length / jobsPerPage);
                
                // Vérifier si l'utilisateur est un étudiant pour afficher les boutons wishlist
                fetch("app/views/login/session.php")
                    .then(response => response.json())
                    .then(sessionData => {
                        console.log("Session data dans loadJobs:", sessionData); // Débogage
                        if (sessionData.logged_in && parseInt(sessionData.utilisateur) === 0) {
                            displayJobsWithWishlist(getPaginatedJobs());
                        } else {
                            displayJobsWithoutWishlist(getPaginatedJobs());
                        }
                        
                        // Mettre à jour le compteur d'offres
                        document.getElementById('jobs-count').textContent = `${allJobs.length} offre(s) trouvée(s)`;
                        
                        // Afficher ou masquer le message "Aucun emploi trouvé"
                        if (allJobs.length === 0) {
                            document.getElementById('jobs-list').classList.add('hide');
                            document.getElementById('pagination').classList.add('hide');
                            document.getElementById('no-jobs-found').classList.remove('hide');
                        } else {
                            document.getElementById('jobs-list').classList.remove('hide');
                            document.getElementById('pagination').classList.remove('hide');
                            document.getElementById('no-jobs-found').classList.add('hide');
                            
                            // Générer la pagination
                            generatePagination();
                        }
                    })
                    .catch(error => {
                        console.error("Erreur lors de la vérification de la session:", error);
                        displayJobsWithoutWishlist(getPaginatedJobs());
                    });
            })
            .catch(error => {
                console.error("Erreur lors du chargement des offres:", error);
                document.getElementById('jobs-count').textContent = "Erreur lors du chargement des offres";
            });
    }
    
    // Fonction pour obtenir les offres de la page courante
    function getPaginatedJobs() {
        const startIndex = (currentPage - 1) * jobsPerPage;
        const endIndex = startIndex + jobsPerPage;
        return allJobs.slice(startIndex, endIndex);
    }
    
    // Fonction pour générer la pagination
    function generatePagination() {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';
        
        // Ne pas afficher la pagination s'il n'y a qu'une seule page
        if (totalPages <= 1) {
            return;
        }
        
        // Bouton précédent
        const prevButton = document.createElement('button');
        prevButton.className = 'pagination-button';
        prevButton.innerHTML = '<span class="iconify" data-icon="mdi:chevron-left" width="16" height="16"></span>';
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePage();
            }
        });
        paginationContainer.appendChild(prevButton);
        
        // Générer les boutons de page
        const maxVisiblePages = 5; // Nombre maximum de boutons de page à afficher
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        // Ajuster startPage si on est proche de la fin
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        // Ajouter le bouton pour la première page si nécessaire
        if (startPage > 1) {
            const firstPageButton = document.createElement('button');
            firstPageButton.className = 'pagination-button';
            firstPageButton.textContent = '1';
            firstPageButton.addEventListener('click', function() {
                currentPage = 1;
                updatePage();
            });
            paginationContainer.appendChild(firstPageButton);
            
            // Ajouter des points de suspension si nécessaire
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.className = 'pagination-ellipsis';
                paginationContainer.appendChild(ellipsis);
            }
        }
        
        // Ajouter les boutons de page
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = 'pagination-button' + (i === currentPage ? ' active' : '');
            pageButton.textContent = i;
            pageButton.addEventListener('click', function() {
                currentPage = i;
                updatePage();
            });
            paginationContainer.appendChild(pageButton);
        }
        
        // Ajouter des points de suspension et le bouton pour la dernière page si nécessaire
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.className = 'pagination-ellipsis';
                paginationContainer.appendChild(ellipsis);
            }
            
            const lastPageButton = document.createElement('button');
            lastPageButton.className = 'pagination-button';
            lastPageButton.textContent = totalPages;
            lastPageButton.addEventListener('click', function() {
                currentPage = totalPages;
                updatePage();
            });
            paginationContainer.appendChild(lastPageButton);
        }
        
        // Bouton suivant
        const nextButton = document.createElement('button');
        nextButton.className = 'pagination-button';
        nextButton.innerHTML = '<span class="iconify" data-icon="mdi:chevron-right" width="16" height="16"></span>';
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePage();
            }
        });
        paginationContainer.appendChild(nextButton);
    }
    
    // Fonction pour mettre à jour la page courante
    function updatePage() {
        // Vérifier si l'utilisateur est un étudiant pour afficher les boutons wishlist
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(sessionData => {
                if (sessionData.logged_in && parseInt(sessionData.utilisateur) === 0) {
                    displayJobsWithWishlist(getPaginatedJobs());
                } else {
                    displayJobsWithoutWishlist(getPaginatedJobs());
                }
                
                // Mettre à jour la pagination
                generatePagination();
                
                // Faire défiler vers le haut de la liste des offres
                document.getElementById('jobs-list').scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                console.error("Erreur lors de la vérification de la session:", error);
                displayJobsWithoutWishlist(getPaginatedJobs());
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
            
            // Déterminer l'image à utiliser en fonction du thème de l'offre
            const jobImage = getJobImage(job);
            
            // Créer la carte avec la nouvelle structure
            jobCard.innerHTML = `
                <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                <div class="job-card-image">
                    <img src="${jobImage}" alt="${job.titre}" class="job-image">
                </div>
                <div class="job-card-content">
                    <h3 class="job-title">${job.titre}</h3>
                    <div class="job-location">
                        <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                        <span>${job.ville || 'Non spécifiée'}</span>
                    </div>
                    <div class="job-company">
                        <span class="iconify" data-icon="mdi:office-building" width="16" height="16"></span>
                        <span>${job.entreprise || job.nom_entreprise || 'Entreprise non spécifiée'}</span>
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
            
            // Déterminer l'image à utiliser en fonction du thème de l'offre
            const jobImage = getJobImage(job);
            
            // Créer la carte avec la nouvelle structure mais sans bouton wishlist
            jobCard.innerHTML = `
                <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                <div class="job-card-image">
                    <img src="${jobImage}" alt="${job.titre}" class="job-image">
                </div>
                <div class="job-card-content">
                    <h3 class="job-title">${job.titre}</h3>
                    <div class="job-location">
                        <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                        <span>${job.ville || 'Non spécifiée'}</span>
                    </div>
                    <div class="job-company">
                        <span class="iconify" data-icon="mdi:office-building" width="16" height="16"></span>
                        <span>${job.entreprise || job.nom_entreprise || 'Entreprise non spécifiée'}</span>
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
    
    // Fonction pour appliquer les filtres
    function applyFilters() {
        const jobTitle = document.getElementById('job-search').value;
        const location = document.getElementById('location-search').value;
        
        // Récupérer les filtres actifs
        const filters = {};
        
        // Filtres de ville
        const cityFilters = Array.from(document.querySelectorAll('input[data-filter="city"]:checked')).map(input => input.value);
        if (cityFilters.length > 0) {
            filters.city = cityFilters;
        }
        
        // Filtres de famille d'emploi
        const jobFamilyFilters = Array.from(document.querySelectorAll('input[data-filter="job-family"]:checked')).map(input => input.value);
        if (jobFamilyFilters.length > 0) {
            filters.jobFamily = jobFamilyFilters;
        }
        
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
    }
    
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
        
        // Afficher les filtres de ville
        if (filters.city && filters.city.length > 0) {
            filters.city.forEach(value => {
                const filterTag = document.createElement('span');
                filterTag.className = 'filter-tag';
                filterTag.innerHTML = `
                    Ville: ${value}
                    <button class="remove-filter" data-type="city" data-value="${value}">&times;</button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            });
        }
        
        // Afficher les filtres de famille d'emploi
        if (filters.jobFamily && filters.jobFamily.length > 0) {
            filters.jobFamily.forEach(value => {
                let label = '';
                switch (value) {
                    case 'informatique':
                        label = 'Informatique & Tech';
                        break;
                    case 'btp':
                        label = 'BTP & Construction';
                        break;
                    case 'finance':
                        label = 'Finance & Comptabilité';
                        break;
                    case 'marketing':
                        label = 'Marketing & Communication';
                        break;
                    case 'sante':
                        label = 'Santé & Médical';
                        break;
                    case 'autre':
                        label = 'Autre';
                        break;
                    default:
                        label = value.charAt(0).toUpperCase() + value.slice(1);
                }
                
                const filterTag = document.createElement('span');
                filterTag.className = 'filter-tag';
                filterTag.innerHTML = `
                    Famille: ${label}
                    <button class="remove-filter" data-type="job-family" data-value="${value}">&times;</button>
                `;
                activeFiltersContainer.appendChild(filterTag);
            });
        }
        
        // Afficher les filtres de salaire
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
                } else if (type === 'city' && value) {
                    document.querySelector(`input[data-filter="city"][value="${value}"]`).checked = false;
                } else if (type === 'job-family' && value) {
                    document.querySelector(`input[data-filter="job-family"][value="${value}"]`).checked = false;
                } else if (type === 'salary' && value) {
                    document.querySelector(`input[data-filter="salary"][value="${value}"]`).checked = false;
                }
                
                // Réinitialiser à la première page lors de la suppression d'un filtre
                currentPage = 1;
                
                // Appliquer les filtres mis à jour
                applyFilters();
            });
        });
    }
    
    // Charger les offres au chargement de la page
    loadJobs();
    
    // Gérer la soumission du formulaire de recherche
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        currentPage = 1; // Réinitialiser à la première page lors d'une nouvelle recherche
        applyFilters();
    });
    
    // Ajouter des écouteurs d'événements pour les filtres
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            currentPage = 1; // Réinitialiser à la première page lors du changement de filtre
            applyFilters();
        });
    });
    
    // Gérer le clic sur le bouton "Effacer les filtres"
    document.getElementById('clear-filters').addEventListener('click', function() {
        resetAllFilters();
    });
    
    // Gérer le clic sur le bouton "Réinitialiser tous les filtres"
    document.getElementById('reset-all-filters').addEventListener('click', function() {
        resetAllFilters();
    });
    
    // Gérer le clic sur le bouton "Effacer les filtres" dans le message "Aucun emploi trouvé"
    document.getElementById('reset-filters').addEventListener('click', function() {
        resetAllFilters();
    });
    
    // Fonction pour réinitialiser tous les filtres
    function resetAllFilters() {
        // Réinitialiser le formulaire
        document.getElementById('search-form').reset();
        
        // Décocher toutes les cases à cocher
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Réinitialiser les filtres actifs
        document.getElementById('active-filters').innerHTML = '';
        
        // Réinitialiser à la première page
        currentPage = 1;
        
        // Recharger les offres sans filtres
        loadJobs();
    }
    
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

<!-- Charger les scripts externes -->
<script src="public/js/app.js"></script>
<script src="public/js/wishlist.js"></script>
</body>
</html>