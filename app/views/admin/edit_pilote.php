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
            <h1>Modifier un pilote</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="admin/pilotes" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations du pilote</h5>
        </div>
        <div class="card-body">
            <form action="admin/editPilote?id=<?= $pilote['id'] ?>" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" value="<?= htmlspecialchars($pilote['nom']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="<?= htmlspecialchars($pilote['prenom']) ?>" readonly>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($pilote['email']) ?>" readonly>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="departement" class="form-label">Département</label>
                        <select class="form-select" id="departement" name="departement">
                            <option value="">Sélectionnez un département</option>
                            <option value="Département 1" <?= $pilote['departement'] == 'Département 1' ? 'selected' : '' ?>>Département 1</option>
                            <option value="Département 2" <?= $pilote['departement'] == 'Département 2' ? 'selected' : '' ?>>Département 2</option>
                            <option value="Département 3" <?= $pilote['departement'] == 'Département 3' ? 'selected' : '' ?>>Département 3</option>
                            <option value="Département 4" <?= $pilote['departement'] == 'Département 4' ? 'selected' : '' ?>>Département 4</option>
                            <option value="Département 5" <?= $pilote['departement'] == 'Département 5' ? 'selected' : '' ?>>Département 5</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="specialite" class="form-label">Spécialité</label>
                        <select class="form-select" id="specialite" name="specialite">
                            <option value="">Sélectionnez une spécialité</option>
                            <option value="Spécialité 1" <?= $pilote['specialite'] == 'Spécialité 1' ? 'selected' : '' ?>>Spécialité 1</option>
                            <option value="Spécialité 2" <?= $pilote['specialite'] == 'Spécialité 2' ? 'selected' : '' ?>>Spécialité 2</option>
                            <option value="Spécialité 3" <?= $pilote['specialite'] == 'Spécialité 3' ? 'selected' : '' ?>>Spécialité 3</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
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