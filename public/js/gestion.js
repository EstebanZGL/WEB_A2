        // Mettre à jour l'année actuelle dans le footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Fonction pour confirmer la suppression
        function confirmDelete(section, id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                window.location.href = 'gestion/' + section + '/delete?id=' + id;
            }
        }
        
        // Corriger la pagination active
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer le numéro de page actuel à partir de l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(urlParams.get('page')) || 1;
            
            // Supprimer la classe 'active' de tous les éléments de pagination
            document.querySelectorAll('.pagination-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Ajouter la classe 'active' à l'élément correspondant à la page actuelle
            const activePageItem = document.querySelector(`.pagination-item[href$="page=${currentPage}${urlParams.get('search') ? '&search=' + urlParams.get('search') : ''}"]:not([href*="page=${currentPage+1}"]):not([href*="page=${currentPage-1}"])`);
            if (activePageItem) {
                activePageItem.classList.add('active');
            }
        });