/**
 * Script pour maintenir l'état des boutons wishlist
 * Ce script vérifie quelles offres sont dans la wishlist de l'utilisateur
 * et applique la classe 'added' aux boutons correspondants
 */

document.addEventListener('DOMContentLoaded', function() {
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
                // Créer un ensemble d'IDs d'offres dans la wishlist pour une recherche rapide
                const wishlistIds = new Set();
                wishlistData.forEach(item => {
                    wishlistIds.add(item.id.toString());
                });
                
                // Observer les changements dans la liste des offres
                const jobsListObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            // Parcourir tous les boutons wishlist et marquer ceux qui sont dans la wishlist
                            document.querySelectorAll('.wishlist-button').forEach(button => {
                                const jobId = button.getAttribute('data-job-id');
                                if (wishlistIds.has(jobId)) {
                                    button.classList.add('added');
                                    button.title = "Ajouté à votre wishlist";
                                }
                            });
                        }
                    });
                });
                
                // Observer le conteneur des offres
                const jobsList = document.querySelector('.jobs-list');
                if (jobsList) {
                    jobsListObserver.observe(jobsList, { childList: true, subtree: true });
                    
                    // Vérifier les boutons déjà présents
                    document.querySelectorAll('.wishlist-button').forEach(button => {
                        const jobId = button.getAttribute('data-job-id');
                        if (wishlistIds.has(jobId)) {
                            button.classList.add('added');
                            button.title = "Ajouté à votre wishlist";
                        }
                    });
                }
            })
            .catch(error => {
                console.error("Erreur lors de la récupération de la wishlist:", error);
            });
    }
    
    // Appeler la fonction au chargement de la page
    getWishlistItems();
});