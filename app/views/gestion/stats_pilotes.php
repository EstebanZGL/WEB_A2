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
            <h1>Statistiques des Pilotes</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="gestion?section=pilotes" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Nombre total de pilotes -->
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Nombre total de pilotes</h5>
                    <p class="card-text display-4"><?= $stats['total'] ?></p>
                </div>
            </div>
        </div>
        
        <!-- Répartition par département -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Répartition par département</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['par_departement'])): ?>
                        <div class="alert alert-info">Aucune donnée disponible.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Département</th>
                                        <th>Nombre de pilotes</th>
                                        <th>Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['par_departement'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['departement'] ?: 'Non défini') ?></td>
                                            <td><?= $item['count'] ?></td>
                                            <td>
                                                <?php 
                                                $percentage = ($item['count'] / $stats['total']) * 100;
                                                echo number_format($percentage, 1) . '%';
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Répartition par spécialité -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Répartition par spécialité</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['par_specialite'])): ?>
                        <div class="alert alert-info">Aucune donnée disponible.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Spécialité</th>
                                        <th>Nombre de pilotes</th>
                                        <th>Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['par_specialite'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['specialite'] ?: 'Non définie') ?></td>
                                            <td><?= $item['count'] ?></td>
                                            <td>
                                                <?php 
                                                $percentage = ($item['count'] / $stats['total']) * 100;
                                                echo number_format($percentage, 1) . '%';
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inclure le pied de page
require_once 'app/views/footer.php';
?>