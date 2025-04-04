        // Script pour gérer la suppression des offres de la wishlist
        document.addEventListener('DOMContentLoaded', function() {
            // Mettre à jour l'année dans le copyright
            document.getElementById('current-year').textContent = new Date().getFullYear();
            
            const removeButtons = document.querySelectorAll('.remove-wishlist');
            
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const offreId = this.getAttribute('data-id');
                    
                    fetch('wishlist/remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `offre_id=${offreId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Supprimer la ligne du tableau
                            this.closest('tr').remove();
                            
                            // Vérifier si le tableau est vide
                            const tbody = this.closest('tbody');
                            if (tbody && tbody.children.length === 0) {
                                const table = tbody.closest('table');
                                const statsTable = table.closest('.stats-table');
                                const emptyState = document.createElement('div');
                                emptyState.className = 'empty-state';
                                emptyState.innerHTML = `
                                    <span class="iconify" data-icon="mdi:heart-outline" style="font-size: 48px; color: var(--accent); margin-bottom: 1rem;"></span>
                                    <p>Vous n'avez pas encore ajouté d'offres à vos favoris.</p>
                                    <a href="offres" class="button button-primary">Parcourir les offres</a>
                                `;
                                table.remove();
                                statsTable.appendChild(emptyState);
                            }
                        } else {
                            alert('Une erreur est survenue lors de la suppression de l\'offre de la wishlist.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la communication avec le serveur.');
                    });
                });
            });
        });