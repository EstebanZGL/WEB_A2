/**
 * Script de gestion de la wishlist
 * Ce script gère l'affichage des boutons wishlist et l'ajout/suppression d'offres à la wishlist
 */

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'utilisateur est connecté et s'il est un étudiant
    function checkUserStatus() {
        return fetch("app/views/login/session.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des données de session');
                }
                return response.json();
            })
            .then(data => {
                console.log("Session data:", data); // Débogage
                
                // Afficher/masquer les éléments liés à la wishlist en fonction du type d'utilisateur
                const isStudent = data.logged_in && data.utilisateur === 0;
                
                // Éléments de navigation
                const wishlistLink = document.getElementById('wishlist-link');
                const mobileWishlistLink = document.getElementById('mobile-wishlist-link');
                const wishlistSection = document.getElementById('wishlist-section');
                
                if (wishlistLink) wishlistLink.style.display = isStudent ? 'inline-flex' : 'none';
                if (mobileWishlistLink) mobileWishlistLink.style.display = isStudent ? 'block' : 'none';
                if (wishlistSection) wishlistSection.style.display = isStudent ? 'block' : 'none';
                
                return data;
            })
            .catch(error => {
                console.error("Erreur lors de la vérification du statut de l'utilisateur:", error);
                return { logged_in: false, utilisateur: null };
            });
    }
    
    // Initialiser les boutons de wishlist sur la page des offres
    function initWishlistButtons() {
        document.querySelectorAll('.wishlist-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const jobId = this.getAttribute('data-job-id');
                addToWishlist(jobId, this);
            });
        });
    }
    
    // Ajouter une offre à la wishlist
    function addToWishlist(jobId, button) {
        // Vérifier d'abord si l'utilisateur est connecté et est un étudiant
        checkUserStatus().then(data => {
            if (!data.logged_in) {
                alert("Veuillez vous connecter pour ajouter des offres à votre wishlist");
                window.location.href = "login";
                return;
            }
            
            if (data.utilisateur !== 0) {
                alert("Seuls les étudiants peuvent ajouter des offres à leur wishlist");
                return;
            }
            
            // L'utilisateur est connecté et est un étudiant, procéder à l'ajout
            const formData = new FormData();
            formData.append('item_id', jobId);
            
            fetch('wishlist/add', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la requête');
                }
                return response.json();
            })
            .then(data => {
                console.log("Réponse de l'ajout à la wishlist:", data); // Débogage
                
                if (data.success) {
                    // Changer l'apparence du bouton pour indiquer que l'offre a été ajoutée
                    button.classList.add('added');
                    button.title = "Ajouté à votre wishlist";
                    
                    // Afficher un message de confirmation
                    alert(data.message);
                } else {
                    alert(data.message || "Une erreur est survenue lors de l'ajout à la wishlist");
                }
            })
            .catch(error => {
                console.error("Erreur lors de l'ajout à la wishlist:", error);
                alert("Une erreur est survenue lors de l'ajout à la wishlist");
            });
        });
    }
    
    // Supprimer une offre de la wishlist (utilisé sur la page wishlist)
    function initWishlistRemoveButtons() {
        document.querySelectorAll('.remove-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm("Êtes-vous sûr de vouloir retirer cette offre de votre wishlist ?")) {
                    e.preventDefault();
                }
            });
        });
    }
    
    // Vérifier le statut de l'utilisateur au chargement de la page
    checkUserStatus();
    
    // Initialiser les boutons si nous sommes sur la page des offres
    if (document.querySelector('.jobs-list')) {
        // Observer le conteneur des offres pour initialiser les boutons lorsque les offres sont chargées
        const jobsListObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    initWishlistButtons();
                }
            });
        });
        
        jobsListObserver.observe(document.querySelector('.jobs-list'), { childList: true });
    }
    
    // Initialiser les boutons de suppression si nous sommes sur la page wishlist
    if (document.querySelector('.wishlist-items')) {
        initWishlistRemoveButtons();
    }
    
    // Exporter les fonctions pour une utilisation externe
    window.wishlistManager = {
        checkUserStatus,
        addToWishlist,
        initWishlistButtons
    };
});