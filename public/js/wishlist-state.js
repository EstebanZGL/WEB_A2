/**
 * Script pour maintenir l'état des boutons wishlist
 * Ce script vérifie quelles offres sont dans la wishlist de l'utilisateur
 * et applique la classe 'added' aux boutons correspondants
 */

document.addEventListener('DOMContentLoaded', function() {
    // Stockage global des IDs d'offres dans la wishlist
    let wishlistIds = new Set();
    
    // Fonction pour récupérer la liste des offres dans la wishlist de l'utilisateur
    function getWishlistItems() {
        // Vérifier si l'utilisateur est connecté et est un étudiant
        fetch("app/views/login/session.php")
            .then(response => response.json())
            .then(data => {
                if (data.logged_in && parseInt(data.utilisateur) === 0) {
                    // L'utilisateur est un étudiant connecté, récupérer sa wishlist
                    return fetch('wishlist/index', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } else {
                    // L'utilisateur n'est pas un étudiant connecté
                    throw new Error('Utilisateur non connecté ou non étudiant');
                }
            })
            .then(response => response.json())
            .then(wishlistData => {
                console.log("Wishlist data received:", wishlistData);
                
                // Créer un ensemble d'IDs d'offres dans la wishlist pour une recherche rapide
                wishlistIds.clear();
                wishlistData.forEach(item => {
                    wishlistIds.add(item.id.toString());
                });
                
                console.log("Wishlist IDs:", Array.from(wishlistIds));
                
                // Appliquer les classes aux boutons existants
                updateWishlistButtons();
                
                // Observer les changements dans la liste des offres
                setupObserver();
            })
            .catch(error => {
                console.error("Erreur lors de la récupération de la wishlist:", error);
            });
    }
    
    // Fonction pour mettre à jour l'apparence des boutons wishlist
    function updateWishlistButtons() {
        document.querySelectorAll('.wishlist-button').forEach(button => {
            const jobId = button.getAttribute('data-job-id');
            if (wishlistIds.has(jobId)) {
                button.classList.add('added');
                button.title = "Ajouté à votre wishlist";
                
                // Remplacer l'icône SVG par une version remplie
                const icon = button.querySelector('.icon');
                if (icon) {
                    icon.setAttribute('fill', 'rgba(255, 0, 0, 0.2)');
                    icon.setAttribute('stroke', '#ff0000');
                }
            } else {
                button.classList.remove('added');
                button.title = "Ajouter à ma wishlist";
                
                // Réinitialiser l'icône SVG
                const icon = button.querySelector('.icon');
                if (icon) {
                    icon.setAttribute('fill', 'none');
                    icon.setAttribute('stroke', 'currentColor');
                }
            }
        });
    }
    
    // Fonction pour configurer l'observateur de mutations
    function setupObserver() {
        // Observer les changements dans la liste des offres
        const jobsListObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Mettre à jour les boutons wishlist lorsque de nouvelles offres sont ajoutées
                    updateWishlistButtons();
                }
            });
        });
        
        // Observer le conteneur des offres
        const jobsList = document.querySelector('.jobs-list');
        if (jobsList) {
            jobsListObserver.observe(jobsList, { 
                childList: true, 
                subtree: true 
            });
        }
    }
    
    // Intercepter les clics sur les boutons wishlist pour mettre à jour l'état localement
    document.addEventListener('click', function(event) {
        // Vérifier si l'élément cliqué est un bouton wishlist
        if (event.target.closest('.wishlist-button')) {
            const button = event.target.closest('.wishlist-button');
            const jobId = button.getAttribute('data-job-id');
            
            // Si le bouton n'a pas la classe 'added', c'est qu'on ajoute l'offre à la wishlist
            if (!button.classList.contains('added')) {
                // Ajouter l'ID à notre ensemble local
                wishlistIds.add(jobId);
                
                // La classe 'added' sera ajoutée par le gestionnaire d'événements existant
            }
        }
    }, true);
    
    // Appeler la fonction au chargement de la page
    getWishlistItems();
    
    // Rafraîchir la wishlist périodiquement (toutes les 30 secondes)
    setInterval(getWishlistItems, 30000);
});