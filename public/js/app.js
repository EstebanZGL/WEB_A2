document.addEventListener("DOMContentLoaded", function () {
    // Vérifier si nous avons des paramètres dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const queryParam = urlParams.get('q');
    const jobTitleParam = urlParams.get('jobTitle');
    const locationParam = urlParams.get('location');
    
    // Si nous sommes sur la page des offres et qu'il y a des paramètres de recherche
    if (window.location.pathname.includes('offres')) {
        // Charger les filtres de ville
        loadCityFilters();
        
        // Traitement du paramètre q (recherche par catégorie ou mot-clé)
        if (queryParam) {
            // Liste des termes considérés comme des catégories (filtres)
            const categoryTerms = ['technology', 'informatique', 'btp', 'marketing', 'finance'];
            // Liste des termes considérés comme des recherches de titre
            const titleSearchTerms = ['developer', 'développeur', 'designer', 'marketing', 'audit', 'remote', 'télétravail'];
            
            // Vérifier si le terme est une catégorie
            const isCategory = categoryTerms.includes(queryParam.toLowerCase());
            // Vérifier si le terme est une recherche de titre
            const isTitleSearch = titleSearchTerms.includes(queryParam.toLowerCase());
            
            if (isCategory) {
                // Appliquer le filtre de catégorie correspondant
                if (queryParam.toLowerCase() === 'technology' || queryParam.toLowerCase() === 'informatique') {
                    const techCheckbox = document.querySelector('input[data-filter="job-family"][value="informatique"]');
                    if (techCheckbox) {
                        techCheckbox.checked = true;
                    }
                } else if (queryParam.toLowerCase() === 'btp') {
                    const btpCheckbox = document.querySelector('input[data-filter="job-family"][value="btp"]');
                    if (btpCheckbox) {
                        btpCheckbox.checked = true;
                    }
                } else if (queryParam.toLowerCase() === 'marketing') {
                    const marketingCheckbox = document.querySelector('input[data-filter="job-family"][value="marketing"]');
                    if (marketingCheckbox) {
                        marketingCheckbox.checked = true;
                    }
                } else if (queryParam.toLowerCase() === 'finance') {
                    const financeCheckbox = document.querySelector('input[data-filter="job-family"][value="finance"]');
                    if (financeCheckbox) {
                        financeCheckbox.checked = true;
                    }
                }
            } else if (isTitleSearch) {
                // C'est une recherche par titre, remplir le champ de recherche
                const jobSearchInput = document.getElementById('job-search');
                if (jobSearchInput) {
                    jobSearchInput.value = queryParam;
                }
            } else {
                // Si ce n'est ni une catégorie ni une recherche par titre reconnue,
                // on considère que c'est une recherche par titre générique
                const jobSearchInput = document.getElementById('job-search');
                if (jobSearchInput) {
                    jobSearchInput.value = queryParam;
                }
            }
        }
        
        // Traitement des paramètres jobTitle et location (provenant de la page d'accueil)
        if (jobTitleParam) {
            const jobSearchInput = document.getElementById('job-search');
            if (jobSearchInput) {
                jobSearchInput.value = jobTitleParam;
            }
        }
        
        if (locationParam) {
            const locationSearchInput = document.getElementById('location-search');
            if (locationSearchInput) {
                locationSearchInput.value = locationParam;
            }
        }
        
        // Déclencher la recherche avec les filtres appliqués
        // Attendre un court instant pour s'assurer que les éléments sont chargés
        setTimeout(() => {
            // Si la fonction applyFilters existe, l'appeler
            if (typeof applyFilters === 'function') {
                applyFilters();
            } else {
                // Sinon, soumettre le formulaire de recherche
                const searchForm = document.getElementById('search-form');
                if (searchForm) {
                    const submitEvent = new Event('submit');
                    searchForm.dispatchEvent(submitEvent);
                }
            }
        }, 300);
    }
    
    fetch("app/views/login/session.php")
        .then(response => response.json())
        .then(data => {
            const loginBouton = document.getElementById("login-Bouton");
            const logoutBouton = document.getElementById("logout-Bouton");
            const welcomeMessage = document.getElementById("welcome-message");
            const pageGestion = document.getElementById("page-gestion");
            const pageAdmin= document.getElementById("page-admin");
            const mobilePageGestion = document.getElementById("mobile-page-gestion");
            const mobilePageAdmin = document.getElementById("mobile-page-admin");
            const mobileLoginBouton = document.getElementById("mobile-login-Bouton");
            const mobileLogoutBouton = document.getElementById("mobile-logout-Bouton");
            
            if (data.logged_in) {
                loginBouton.style.display = "none";
                logoutBouton.style.display = "inline-block";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "none";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "inline-block";
                
                // Afficher un message de bienvenue en fonction du type d'utilisateur
                let utilisateurMessage;
                const userType = parseInt(data.utilisateur);
                
                switch (userType) {
                    case 0: // Étudiant
                        utilisateurMessage = "Étudiant";
                        welcomeMessage.classList.add('etudiant');
                        break;
                    case 1:
                        utilisateurMessage = "Pilote";
                        welcomeMessage.classList.add('pilote');
                        pageGestion.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        break;
                    case 2:
                        utilisateurMessage = "Admin";
                        welcomeMessage.classList.add('admin');
                        pageGestion.style.display = "inline-block";
                        pageAdmin.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        if (mobilePageAdmin) mobilePageAdmin.style.display = "inline-block";
                        break;
                    default:
                        utilisateurMessage = "Bienvenue !";
                }
                
                welcomeMessage.textContent = utilisateurMessage; // Met à jour le message de bienvenue
                welcomeMessage.style.display = "inline-block"; // Affiche le message
            } else {
                loginBouton.style.display = "inline-block";
                logoutBouton.style.display = "none";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "inline-block";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "none";
                welcomeMessage.style.display = "none"; // Cache le message de bienvenue
                if (pageGestion) pageGestion.style.display = "none";
                if (pageAdmin) pageAdmin.style.display = "none"; // Cache la page administrateur
                if (mobilePageGestion) mobilePageGestion.style.display = "none";
                if (mobilePageAdmin) mobilePageAdmin.style.display = "none";
                
                // Supprimer le lien wishlist s'il existe
                removeWishlistLinks();
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de la session :", error));
    
    // Fonction pour ajouter les liens vers la wishlist
    function addWishlistLinks() {
        const navLinks = document.querySelector('.navbar-nav');
        const mobileNav = document.querySelector('.mobile-nav');
        
        // Vérifier si le lien wishlist existe déjà avant de l'ajouter dans la navigation principale
        if (navLinks && !document.querySelector('.nav-link[href="wishlist"]')) {
            const wishlistLink = document.createElement('a');
            wishlistLink.href = "wishlist";
            wishlistLink.className = "nav-link wishlist-nav-link";
            
            // Ajouter une icône de cœur avant le texte
            wishlistLink.innerHTML = '<svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> Ma Wishlist';
            navLinks.appendChild(wishlistLink);
        }
        
        // Ajouter également à la navigation mobile
        if (mobileNav && !document.querySelector('.mobile-nav-link[href="wishlist"]')) {
            const mobileWishlistLink = document.createElement('a');
            mobileWishlistLink.href = "wishlist";
            mobileWishlistLink.className = "mobile-nav-link";
            
            // Ajouter une icône de cœur avant le texte
            mobileWishlistLink.innerHTML = '<svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> Ma Wishlist';
            mobileNav.appendChild(mobileWishlistLink);
        }
    }
    
    // Fonction pour supprimer les liens vers la wishlist
    function removeWishlistLinks() {
        const wishlistLink = document.querySelector('.nav-link[href="wishlist"]');
        if (wishlistLink) wishlistLink.remove();
        
        const mobileWishlistLink = document.querySelector('.mobile-nav-link[href="wishlist"]');
        if (mobileWishlistLink) mobileWishlistLink.remove();
    }
    
    // Fonction pour charger les filtres de villes
    function loadCityFilters() {
        const cityFilterContainer = document.querySelector('.city-filters-container');
        if (!cityFilterContainer) {
            // Si le conteneur n'existe pas, utiliser l'élément avec l'ID 'city-filters'
            const cityFiltersElement = document.getElementById('city-filters');
            if (cityFiltersElement) {
                // Utiliser la fonction loadCities() déjà existante dans offres.php
                // Cette fonction fait déjà un appel à l'API pour récupérer les villes
                if (typeof loadCities === 'function') {
                    loadCities();
                } else {
                    // Si loadCities n'est pas disponible, faire l'appel directement
                    fetch('offres/cities')
                        .then(response => response.json())
                        .then(cities => {
                            if (cityFiltersElement) {
                                cityFiltersElement.innerHTML = '';
                                
                                if (cities.length === 0) {
                                    cityFiltersElement.innerHTML = '<p>Aucune ville disponible</p>';
                                    return;
                                }
                                
                                cities.forEach(city => {
                                    if (city && city.trim() !== '') {
                                        const label = document.createElement('label');
                                        label.className = 'filter-option';
                                        label.innerHTML = `<input type="checkbox" data-filter="city" value="${city}" class="filter-checkbox" /> ${city}`;
                                        cityFiltersElement.appendChild(label);
                                    }
                                });
                                
                                // Ajouter les écouteurs d'événements pour les nouveaux filtres
                                document.querySelectorAll('#city-filters .filter-checkbox').forEach(checkbox => {
                                    checkbox.addEventListener('change', function() {
                                        if (typeof applyFilters === 'function') {
                                            applyFilters();
                                        }
                                    });
                                });
                                
                                // Si un paramètre de location est présent dans l'URL, cocher la case correspondante
                                if (locationParam) {
                                    const cityCheckbox = document.querySelector(`input[data-filter="city"][value="${locationParam}"]`);
                                    if (cityCheckbox) {
                                        cityCheckbox.checked = true;
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error("Erreur lors du chargement des villes:", error);
                            if (cityFiltersElement) {
                                cityFiltersElement.innerHTML = '<p>Erreur lors du chargement des villes</p>';
                            }
                        });
                }
            }
            return;
        }
        
        // Dans le cas où .city-filters-container existe (pour une compatibilité future)
        fetch('offres/cities')
            .then(response => response.json())
            .then(cities => {
                // Vider le conteneur existant
                cityFilterContainer.innerHTML = '';
                
                // Créer un élément de titre pour la section
                const titleElement = document.createElement('h3');
                titleElement.className = 'filter-section-title';
                titleElement.textContent = 'Villes';
                cityFilterContainer.appendChild(titleElement);
                
                // Créer une liste pour les filtres de ville
                const filterList = document.createElement('ul');
                filterList.className = 'filter-list city-filter-list';
                
                // Ajouter chaque ville comme option de filtre
                cities.forEach(city => {
                    if (city && city.trim() !== '') {
                        const listItem = document.createElement('li');
                        
                        const cityFilter = document.createElement('div');
                        cityFilter.className = 'filter-item';
                        
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.id = `city-${city.toLowerCase().replace(/\s+/g, '-')}`;
                        checkbox.dataset.filter = 'city';
                        checkbox.value = city;
                        checkbox.addEventListener('change', function() {
                            if (typeof applyFilters === 'function') {
                                applyFilters();
                            }
                        });
                        
                        const label = document.createElement('label');
                        label.htmlFor = checkbox.id;
                        label.textContent = city;
                        
                        cityFilter.appendChild(checkbox);
                        cityFilter.appendChild(label);
                        listItem.appendChild(cityFilter);
                        filterList.appendChild(listItem);
                    }
                });
                
                cityFilterContainer.appendChild(filterList);
                
                // Si un paramètre de location est présent dans l'URL, cocher la case correspondante
                if (locationParam) {
                    const cityCheckbox = document.querySelector(`input[data-filter="city"][value="${locationParam}"]`);
                    if (cityCheckbox) {
                        cityCheckbox.checked = true;
                    }
                }
            })
            .catch(error => {
                console.error("Erreur lors du chargement des villes:", error);
                cityFilterContainer.innerHTML = '<p>Erreur lors du chargement des villes</p>';
            });
    }
});