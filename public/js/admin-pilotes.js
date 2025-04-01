// Fonctions pour les modals
function openCreatePiloteModal() {
    // Réinitialiser le formulaire
    document.getElementById('addPiloteForm').reset();
    
    // Afficher le modal
    var modal = new bootstrap.Modal(document.getElementById('addPiloteModal'));
    modal.show();
}

function openEditPiloteModal(piloteId) {
    // Récupérer les données du pilote via AJAX
    fetch(`admin/getPiloteData?id=${piloteId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remplir le formulaire avec les données du pilote
                document.getElementById('editPiloteId').value = data.pilote.id;
                document.getElementById('editDepartement').value = data.pilote.departement;
                document.getElementById('editSpecialite').value = data.pilote.specialite;
                
                // Afficher les informations en lecture seule
                document.getElementById('editNom').value = data.pilote.nom;
                document.getElementById('editPrenom').value = data.pilote.prenom;
                document.getElementById('editEmail').value = data.pilote.email;
                
                // Afficher le modal
                var modal = new bootstrap.Modal(document.getElementById('editPiloteModal'));
                modal.show();
            } else {
                alert('Erreur lors de la récupération des données du pilote.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la récupération des données.');
        });
}

function confirmDeletePilote(piloteId, piloteName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le pilote ${piloteName} ? Cette action est irréversible.`)) {
        window.location.href = `admin/deletePilote?id=${piloteId}`;
    }
}

// Initialiser les événements lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les écouteurs d'événements pour les boutons
    const addPiloteBtn = document.getElementById('addPiloteBtn');
    if (addPiloteBtn) {
        addPiloteBtn.addEventListener('click', openCreatePiloteModal);
    }
    
    // Ajouter des écouteurs d'événements pour les boutons d'édition
    const editButtons = document.querySelectorAll('.edit-pilote-btn');
    if (editButtons) {
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const piloteId = this.getAttribute('data-id');
                openEditPiloteModal(piloteId);
            });
        });
    }
    
    // Ajouter des écouteurs d'événements pour les boutons de suppression
    const deleteButtons = document.querySelectorAll('.delete-pilote-btn');
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const piloteId = this.getAttribute('data-id');
                const piloteName = this.getAttribute('data-name');
                confirmDeletePilote(piloteId, piloteName);
        });
});
    }
    
    // Validation du formulaire d'ajout
    const addPiloteForm = document.getElementById('addPiloteForm');
    if (addPiloteForm) {
        addPiloteForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const nom = document.getElementById('nom').value;
            const prenom = document.getElementById('prenom').value;
            
            if (!email || !nom || !prenom) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
    
    // Validation du formulaire de modification
    const editPiloteForm = document.getElementById('editPiloteForm');
    if (editPiloteForm) {
        editPiloteForm.addEventListener('submit', function(e) {
            const departement = document.getElementById('editDepartement').value;
            const specialite = document.getElementById('editSpecialite').value;
            
            if (!departement || !specialite) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
});