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
        <div class="col">
            <h1>Tableau de bord administrateur</h1>
            <p class="lead">Gérez les utilisateurs, les offres et les paramètres du système.</p>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text display-4"><?= $stats['totalUsers'] ?></p>
                    <a href="admin/manage" class="btn btn-light">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Offres</h5>
                    <p class="card-text display-4"><?= $stats['totalOffres'] ?></p>
                    <a href="gestion?section=offres" class="btn btn-light">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pilotes</h5>
                    <p class="card-text display-4"><?= $stats['totalPilotes'] ?></p>
                    <a href="admin/pilotes" class="btn btn-light">Gérer</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules de gestion -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Gestion des utilisateurs</h5>
                </div>
                <div class="card-body">
                    <p>Gérez les comptes utilisateurs, les rôles et les permissions.</p>
                    <div class="list-group">
                        <a href="admin/manage" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Tous les utilisateurs
                            <span class="badge bg-primary rounded-pill"><?= $stats['totalUsers'] ?></span>
                        </a>
                        <a href="admin/pilotes" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Pilotes
                            <span class="badge bg-primary rounded-pill"><?= $stats['totalPilotes'] ?></span>
                        </a>
                        <a href="gestion?section=etudiants" class="list-group-item list-group-item-action">Étudiants</a>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="admin/manage" class="btn btn-primary">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Gestion du contenu</h5>
                </div>
                <div class="card-body">
                    <p>Gérez les offres, les entreprises et autres contenus du site.</p>
                    <div class="list-group">
                        <a href="gestion?section=offres" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Offres de stage
                            <span class="badge bg-success rounded-pill"><?= $stats['totalOffres'] ?></span>
                        </a>
                        <a href="gestion?section=entreprises" class="list-group-item list-group-item-action">Entreprises</a>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="gestion" class="btn btn-success">Accéder à la gestion</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistiques et rapports -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Statistiques et rapports</h5>
                </div>
                <div class="card-body">
                    <p>Accédez aux statistiques et rapports du système.</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Statistiques des offres</h5>
                                    <p class="card-text">Consultez les statistiques des offres de stage.</p>
                                    <a href="gestion/offres/stats" class="btn btn-outline-primary btn-sm">Voir les statistiques</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Statistiques des étudiants</h5>
                                    <p class="card-text">Consultez les statistiques des étudiants.</p>
                                    <a href="gestion/etudiants/stats" class="btn btn-outline-primary btn-sm">Voir les statistiques</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Statistiques des pilotes</h5>
                                    <p class="card-text">Consultez les statistiques des pilotes.</p>
                                    <a href="admin/statsPilotes" class="btn btn-outline-primary btn-sm">Voir les statistiques</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inclure le pied de page
require_once 'app/views/footer.php';
?>