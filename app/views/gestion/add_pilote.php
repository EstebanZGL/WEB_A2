<?php
// Vérifier si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
    header("Location: login");
    exit;
}

// Inclure l'en-tête
require_once 'app/views/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Ajouter un pilote</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="gestion?section=pilotes" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations du pilote</h5>
        </div>
        <div class="card-body">
            <form action="gestion/pilotes/add" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Laissez vide pour utiliser 'changeme'">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="departement" class="form-label">Département</label>
                        <select class="form-select" id="departement" name="departement">
                            <option value="">Sélectionnez un département</option>
                            <option value="Département 1">Département 1</option>
                            <option value="Département 2">Département 2</option>
                            <option value="Département 3">Département 3</option>
                            <option value="Département 4">Département 4</option>
                            <option value="Département 5">Département 5</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="specialite" class="form-label">Spécialité</label>
                        <select class="form-select" id="specialite" name="specialite">
                            <option value="">Sélectionnez une spécialité</option>
                            <option value="Spécialité 1">Spécialité 1</option>
                            <option value="Spécialité 2">Spécialité 2</option>
                            <option value="Spécialité 3">Spécialité 3</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Inclure le pied de page
require_once 'app/views/footer.php';
?>