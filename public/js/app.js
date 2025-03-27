document.addEventListener("DOMContentLoaded", function () {
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

                // Ajout du lien vers la wishlist dans la navigation
                const navLinks = document.querySelector('.navbar-nav');
                const mobileNav = document.querySelector('.mobile-nav');
                
                // Vérifier si le lien wishlist existe déjà avant de l'ajouter
                if (navLinks && !document.querySelector('.nav-link[href="wishlist"]')) {
                    const wishlistLink = document.createElement('a');
                    wishlistLink.href = "wishlist";
                    wishlistLink.className = "nav-link";
                    wishlistLink.textContent = "Ma Wishlist";
                    navLinks.appendChild(wishlistLink);
                }
                
                // Ajouter également à la navigation mobile
                if (mobileNav && !document.querySelector('.mobile-nav-link[href="wishlist"]')) {
                    const mobileWishlistLink = document.createElement('a');
                    mobileWishlistLink.href = "wishlist";
                    mobileWishlistLink.className = "mobile-nav-link";
                    mobileWishlistLink.textContent = "Ma Wishlist";
                    mobileNav.appendChild(mobileWishlistLink);
                }
                // Afficher un message de bienvenue en fonction du type d'utilisateur
                let utilisateurMessage;
                switch (parseInt(data.utilisateur)) {
                    case 0:
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
                const wishlistLink = document.querySelector('.nav-link[href="wishlist"]');
                if (wishlistLink) wishlistLink.remove();
                
                const mobileWishlistLink = document.querySelector('.mobile-nav-link[href="wishlist"]');
                if (mobileWishlistLink) mobileWishlistLink.remove();
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de la session :", error));
});


document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("search-form");
    if (!searchForm) return; // Ne pas exécuter cette partie si on n'est pas sur la page des offres
    
    const jobSearchInput = document.getElementById("job-search");
    const locationSearchInput = document.getElementById("location-search");
    const jobListContainer = document.getElementById("jobs-list");
    const noJobsFound = document.getElementById("no-jobs-found");

    async function fetchJobs() {
        const search = jobSearchInput.value.trim();
        const location = locationSearchInput.value.trim();
        const filters = {
            jobType: [...document.querySelectorAll('input[data-filter="jobType"]:checked')].map(input => input.value),
            experienceLevel: [...document.querySelectorAll('input[data-filter="experienceLevel"]:checked')].map(input => input.value),
            salary: [...document.querySelectorAll('input[data-filter="salary"]:checked')].map(input => input.value),
        };

        const queryParams = new URLSearchParams({ search, location, filters: JSON.stringify(filters) });

        try {
            const response = await fetch(`recup_offres.php?${queryParams.toString()}`);
            const jobs = await response.json();

            jobListContainer.innerHTML = "";

            if (jobs.length === 0) {
                noJobsFound.classList.remove("hide");
                return;
            } else {
                noJobsFound.classList.add("hide");
            }

            // Vérifier si l'utilisateur est connecté pour afficher le bouton d'ajout à la wishlist
            fetch("app/views/login/session.php")
                .then(response => response.json())
                .then(sessionData => {
                    const isLoggedIn = sessionData.logged_in;
                    jobs.forEach(job => {
                        const jobElement = document.createElement("div");
                        jobElement.classList.add("job-card");
                        
                        // Préparer les boutons d'action
                        let actionButtons = `<button class="apply-button">Postuler</button>`;
                        
                        // Ajouter le bouton wishlist si l'utilisateur est connecté
                        if (isLoggedIn) {
                            actionButtons += `
                                <form action="/wishlist/add" method="POST" style="display: inline-block; margin-left: 10px;">
                                    <input type="hidden" name="item_id" value="${job.id}">
                                    <button type="submit" class="wishlist-button">Ajouter à ma wishlist</button>
                                </form>
                            `;
                        }
                        
                        jobElement.innerHTML = `
                            <div class="job-card-header">
                                <h3>${job.titre}</h3>
                                <p class="job-company">${job.entreprise}</p>
                            </div>
                            <p class="job-description">${job.description.substring(0, 150)}...</p>
                            <p class="job-skills">Compétences : ${job.competences}</p>
                            <p class="job-salary">Salaire : ${job.remuneration}€</p>
                            <p class="job-date">Publié le : ${job.date_offre}</p>
                            <p class="job-postulants">${job.nb_postulants} étudiant(s) ont postulé</p>
                            <div class="job-actions">
                                ${actionButtons}
                            </div>
                        `;
                        jobListContainer.appendChild(jobElement);
                    });
                })
                .catch(error => {
                    console.error("Erreur lors de la vérification de la session:", error);
                    // En cas d'erreur, afficher les offres sans le bouton wishlist
                    displayJobsWithoutWishlist(jobs);
                });
        } catch (error) {
            console.error("Erreur lors de la récupération des offres:", error);
        }
    }

    // Fonction pour afficher les offres sans le bouton wishlist en cas d'erreur
    function displayJobsWithoutWishlist(jobs) {
        jobs.forEach(job => {
            const jobElement = document.createElement("div");
            jobElement.classList.add("job-card");
            jobElement.innerHTML = `
                <div class="job-card-header">
                    <h3>${job.titre}</h3>
                    <p class="job-company">${job.entreprise}</p>
                </div>
                <p class="job-description">${job.description.substring(0, 150)}...</p>
                <p class="job-skills">Compétences : ${job.competences}</p>
                <p class="job-salary">Salaire : ${job.remuneration}€</p>
                <p class="job-date">Publié le : ${job.date_offre}</p>
                <p class="job-postulants">${job.nb_postulants} étudiant(s) ont postulé</p>
                <button class="apply-button">Postuler</button>
            `;
            jobListContainer.appendChild(jobElement);
        });
    }

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        fetchJobs();
    });

    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', fetchJobs);
});

    fetchJobs(); // Charger les offres au démarrage
});