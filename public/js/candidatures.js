        // Mettre à jour l'année actuelle dans le footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Fonction pour changer d'onglet
        function showTab(tabId) {
            // Masquer tous les contenus d'onglets
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });
            
            // Afficher le contenu de l'onglet sélectionné
            document.getElementById(tabId).style.display = 'block';
            
            // Mettre à jour les classes actives des onglets
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Ajouter la classe active à l'onglet cliqué
            document.querySelector(`.tab[href="#${tabId}"]`).classList.add('active');
            
            // Empêcher le comportement par défaut du lien
            return false;
        }
        
        // Fonctions pour gérer les modals de lettre de motivation
        function showLettreMotivation(candidatureId, lettreContent) {
            document.getElementById('lettre-preview').textContent = lettreContent.replace(/\\'/g, "'");
            document.getElementById('modal-backdrop').style.display = 'block';
            document.getElementById('view-lettre-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById('modal-backdrop').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Empêcher que le clic sur le contenu du modal ferme le modal
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
        
        // Fermer les modals en cliquant sur le backdrop
        document.getElementById('modal-backdrop').addEventListener('click', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
            this.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Fonction pour mettre à jour le statut d'une candidature
        function updateStatut(selectElement) {
            const candidatureId = selectElement.dataset.candidatureId;
            const nouveauStatut = selectElement.value;
            const loadingIndicator = document.getElementById('loading-' + candidatureId);
            
            // Afficher l'indicateur de chargement
            loadingIndicator.style.visibility = 'visible';
            
            // Envoi de la requête AJAX pour mettre à jour le statut
            fetch('/gestion/etudiants/candidatures/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `candidature_id=${candidatureId}&status=${nouveauStatut}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Masquer l'indicateur de chargement
                loadingIndicator.style.visibility = 'hidden';
                
                if (data.success) {
                    // Afficher un message de succès temporaire
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success';
                    successMessage.textContent = 'Statut mis à jour avec succès';
                    successMessage.style.position = 'fixed';
                    successMessage.style.bottom = '20px';
                    successMessage.style.right = '20px';
                    successMessage.style.padding = '10px 20px';
                    successMessage.style.borderRadius = '4px';
                    successMessage.style.backgroundColor = '#2ecc71';
                    successMessage.style.color = '#ffffff';
                    successMessage.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
                    successMessage.style.zIndex = '9999';
                    
                    document.body.appendChild(successMessage);
                    
                    // Faire disparaître le message après 3 secondes
                    setTimeout(() => {
                        successMessage.style.opacity = '0';
                        successMessage.style.transition = 'opacity 0.5s ease';
                        
                        setTimeout(() => {
                            document.body.removeChild(successMessage);
                        }, 500);
                    }, 3000);
                } else {
                    // En cas d'erreur, remettre la sélection précédente
                    alert('Erreur lors de la mise à jour du statut: ' + data.message);
                }
            })
            .catch(error => {
                // Masquer l'indicateur de chargement
                loadingIndicator.style.visibility = 'hidden';
                
                // Afficher l'erreur
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la mise à jour du statut.');
            });
        }