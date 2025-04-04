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
    
    fetch("app/views/login/session.php", {
        headers: {
            'Accept': 'application/json; charset=utf-8',
        }
    })
        .then(response => {
            return response.json();
        })
        .then(data => {
            // Récupérer les éléments du DOM avec vérification de leur existence
            const loginBouton = document.getElementById("login-Bouton");
            const logoutBouton = document.getElementById("logout-Bouton");
            const welcomeMessage = document.getElementById("welcome-message");
            const pageGestion = document.getElementById("page-gestion");
            const pageDashboard = document.getElementById("page-dashboard");
            const pageAdmin = document.getElementById("page-admin");
            const mobilePageGestion = document.getElementById("mobile-page-gestion");
            const mobilePageDashboard = document.getElementById("mobile-page-dashboard");
            const mobilePageAdmin = document.getElementById("mobile-page-admin");
            const mobileLoginBouton = document.getElementById("mobile-login-Bouton");
            const mobileLogoutBouton = document.getElementById("mobile-logout-Bouton");

            // Vérifier que les éléments essentiels existent avant de les manipuler
            if (!loginBouton || !logoutBouton || !welcomeMessage) {
                console.error("Éléments essentiels de l'interface non trouvés dans le DOM");
                return; // Sortir de la fonction si les éléments essentiels n'existent pas
            }

            if (data.logged_in) {
                loginBouton.style.display = "none";
                logoutBouton.style.display = "inline-block";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "none";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "inline-block";

                // Afficher un message de bienvenue avec le prénom de l'utilisateur
                let userTypeLabel;
                const userFirstName = data.prenom || "";
                const userType = parseInt(data.utilisateur);
                
                switch (userType) {
                    case 0: // Étudiant
                        userTypeLabel = "Étudiant";
                        welcomeMessage.classList.add('etudiant');
                        
                        // Afficher le lien vers le tableau de bord pour les étudiants
                        if (pageDashboard) pageDashboard.style.display = "inline-block";
                        if (mobilePageDashboard) mobilePageDashboard.style.display = "block";
                        
                        // Afficher la section wishlist dans la barre latérale si on est sur la page des offres
                        if (window.location.pathname.includes('offres')) {
                            const wishlistSection = document.getElementById('wishlist-section');
                            if (wishlistSection) {
                                wishlistSection.style.display = 'block';
                            }
                        }
                        break;
                    case 1:
                        userTypeLabel = "Pilote";
                        welcomeMessage.classList.add('pilote');
                        if (pageGestion) pageGestion.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        break;
                    case 2:
                        userTypeLabel = "Admin";
                        welcomeMessage.classList.add('admin');
                        if (pageGestion) pageGestion.style.display = "inline-block";
                        if (pageAdmin) pageAdmin.style.display = "inline-block";
                        if (mobilePageGestion) mobilePageGestion.style.display = "inline-block";
                        if (mobilePageAdmin) mobilePageAdmin.style.display = "inline-block";
                        break;
                    default:
                        userTypeLabel = "Bienvenue";
                }
                
                // Construire le message de bienvenue avec le prénom
                let welcomeText = userFirstName ? userFirstName : userTypeLabel;
                
                // Utiliser textContent pour afficher le texte (les caractères sont déjà correctement encodés)
                welcomeMessage.textContent = welcomeText;
                welcomeMessage.style.display = "inline-block"; // Affiche le message
            } else {
                loginBouton.style.display = "inline-block";
                logoutBouton.style.display = "none";
                if (mobileLoginBouton) mobileLoginBouton.style.display = "inline-block";
                if (mobileLogoutBouton) mobileLogoutBouton.style.display = "none";
                welcomeMessage.style.display = "none"; // Cache le message de bienvenue
                if (pageGestion) pageGestion.style.display = "none";
                if (pageAdmin) pageAdmin.style.display = "none"; // Cache la page administrateur
                if (pageDashboard) pageDashboard.style.display = "none";
                if (mobilePageGestion) mobilePageGestion.style.display = "none";
                if (mobilePageAdmin) mobilePageAdmin.style.display = "none";
                if (mobilePageDashboard) mobilePageDashboard.style.display = "none";
                
                // Masquer la section wishlist dans la barre latérale si on est sur la page des offres
                if (window.location.pathname.includes('offres')) {
                    const wishlistSection = document.getElementById('wishlist-section');
                    if (wishlistSection) {
                        wishlistSection.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de la session :", error));
});

    
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