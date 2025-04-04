    document.addEventListener('DOMContentLoaded', function() {
    // Variables pour la pagination
    let currentPage = 1;
    let totalPages = 1;
    let allJobs = []; // Stocke toutes les offres récupérées
    const jobsPerPage = 6; // Nombre d'offres par page
    
    // Vérifier les paramètres d'URL pour pré-remplir les filtres
    const urlParams = new URLSearchParams(window.location.search);
    const jobTitleParam = urlParams.get('jobTitle');
    const locationParam = urlParams.get('location');
    const qParam = urlParams.get('q'); // Récupérer le paramètre q des liens populaires
    
    // Pré-remplir les champs de formulaire
    if (jobTitleParam) {
        document.getElementById('job-search').value = jobTitleParam;
    } else if (qParam) {
        // Si q est présent mais pas jobTitle, utiliser q à la place
        document.getElementById('job-search').value = qParam;
    }
    
    if (locationParam) {
        document.getElementById('location-search').value = locationParam;
    }
    
    // Appliquer les filtres si des paramètres sont présents dans l'URL
    if (jobTitleParam || locationParam || qParam) {
        // Un petit délai pour s'assurer que tout est chargé
        setTimeout(applyFilters, 100);
    }
        
        // Fonction pour déterminer quelle image utiliser en fonction du type de l'offre
        function getJobImage(job) {
            // Vérifier si le job a un type défini
            const jobType = job.type ? job.type.toLowerCase() : '';
            // Déterminer l'image en fonction du type d'offre
            if (jobType.includes('informatique') || jobType.includes('tech')) {
                return 'public/images/info-img.png';
            } else if (jobType.includes('btp') || jobType.includes('construction')) {
                return 'public/images/btp-img.png';
            } else if (jobType.includes('santé') || jobType.includes('médical') || jobType.includes('sante')) {
                return 'public/images/sante-img.png';
            } else if (jobType.includes('finance') || jobType.includes('comptabilité')) {
                return 'public/images/fin-img.png';
            } else if (jobType.includes('marketing') || jobType.includes('communication')) {
                return 'public/images/mark-img.png';
            } else {
                // Pour "Autre" ou si le type n'est pas reconnu
                return 'public/images/gen-img.png';
            }
        }
        
        // Vérifier si l'utilisateur est un étudiant pour afficher la section dashboard
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                console.log("Session data:", data); // Débogage
                if (data.logged_in && parseInt(data.utilisateur) === 0) {
                    const dashboardLink = document.getElementById('page-dashboard');
                    const mobileDashboardLink = document.getElementById('mobile-page-dashboard');
                    
                   
                    if (dashboardLink) {
                        dashboardLink.style.display = 'inline-flex';
                    }
                    
                    if (mobileDashboardLink) {
                        mobileDashboardLink.style.display = 'block';
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
                
                // Calculer la durée du stage
                const dateDebut = new Date(job.date_debut);
                const dateFin = new Date(job.date_fin);
                const dureeMois = job.duree_stage;
                const formattedDate = `${dateDebut.toLocaleDateString()} - ${dateFin.toLocaleDateString()}`;
                
                jobCard.innerHTML = `
                    <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                    <div class="job-card-image">
                        <img src="${getJobImage(job)}" alt="${job.titre}" class="job-image">
                    </div>
                    <div class="job-card-content">
                        <h3 class="job-title">${job.titre}</h3>
                        <div class="job-location">
                            <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                            <span>${job.lieu || 'Non spécifiée'}</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
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
                
                // Calculer la durée du stage
                const dateDebut = new Date(job.date_debut);
                const dateFin = new Date(job.date_fin);
                const dureeMois = job.duree_stage;
                const formattedDate = `${dateDebut.toLocaleDateString()} - ${dateFin.toLocaleDateString()}`;
                
                jobCard.innerHTML = `
                    <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                    <div class="job-card-image">
                        <img src="${getJobImage(job)}" alt="${job.titre}" class="job-image">
                    </div>
                    <div class="job-card-content">
                        <h3 class="job-title">${job.titre}</h3>
                        <div class="job-location">
                            <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                            <span>${job.lieu || 'Non spécifiée'}</span>
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