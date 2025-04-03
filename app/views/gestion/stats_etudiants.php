<?php
$title = "Statistiques des étudiants";
require_once 'app/views/includes/header_gestion.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?controller=gestion&action=dashboard">Tableau de bord</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>

    <div class="row">
        <!-- Statistiques générales -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $stats['total'] ?></h4>
                    <p>Étudiants au total</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4><?= $stats['avec_offre'] ?></h4>
                    <p>Étudiants avec une offre assignée</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">
                        <?= round(($stats['avec_offre'] / $stats['total']) * 100, 2) ?>% des étudiants
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4><?= $stats['candidatures_stats']['total_candidatures'] ?? 0 ?></h4>
                    <p>Candidatures totales</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4><?= $stats['candidatures_stats']['candidatures_acceptees'] ?? 0 ?></h4>
                    <p>Candidatures acceptées</p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">
                        <?= $stats['candidatures_stats']['pourcentage_acceptees'] ?? 0 ?>% des candidatures
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistiques détaillées des candidatures -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Statistiques des candidatures
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Statut</th>
                                    <th>Nombre</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Acceptées</td>
                                    <td><?= $stats['candidatures_stats']['candidatures_acceptees'] ?? 0 ?></td>
                                    <td><?= $stats['candidatures_stats']['pourcentage_acceptees'] ?? 0 ?>%</td>
                                </tr>
                                <tr>
                                    <td>Refusées</td>
                                    <td><?= $stats['candidatures_stats']['candidatures_refusees'] ?? 0 ?></td>
                                    <td><?= $stats['candidatures_stats']['pourcentage_refusees'] ?? 0 ?>%</td>
                                </tr>
                                <tr>
                                    <td>En attente</td>
                                    <td><?= $stats['candidatures_stats']['candidatures_en_attente'] ?? 0 ?></td>
                                    <td><?= $stats['candidatures_stats']['pourcentage_en_attente'] ?? 0 ?>%</td>
                                </tr>
                                <tr>
                                    <td>En entretien</td>
                                    <td><?= $stats['candidatures_stats']['candidatures_entretien'] ?? 0 ?></td>
                                    <td><?= $stats['candidatures_stats']['pourcentage_entretien'] ?? 0 ?>%</td>
                                </tr>
                                <tr class="table-primary">
                                    <th>Total</th>
                                    <th><?= $stats['candidatures_stats']['total_candidatures'] ?? 0 ?></th>
                                    <th>100%</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <canvas id="candidaturesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par promotion -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Répartition par promotion
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Promotion</th>
                                    <th>Nombre d'étudiants</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['par_promotion'] as $promotion): ?>
                                <tr>
                                    <td><?= $promotion['promotion'] ?></td>
                                    <td><?= $promotion['count'] ?></td>
                                    <td><?= round(($promotion['count'] / $stats['total']) * 100, 2) ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-primary">
                                    <th>Total</th>
                                    <th><?= $stats['total'] ?></th>
                                    <th>100%</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <canvas id="promotionsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Répartition par formation -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Répartition par formation
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Formation</th>
                                    <th>Nombre d'étudiants</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['par_formation'] as $formation): ?>
                                <tr>
                                    <td><?= $formation['formation'] ?></td>
                                    <td><?= $formation['count'] ?></td>
                                    <td><?= round(($formation['count'] / $stats['total']) * 100, 2) ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-primary">
                                    <th>Total</th>
                                    <th><?= $stats['total'] ?></th>
                                    <th>100%</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <canvas id="formationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nombre de candidatures par étudiant -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    TOP 10 - Nombre de candidatures par étudiant
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Nombre de candidatures</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['candidatures'] as $candidature): ?>
                                <tr>
                                    <td><?= $candidature['nom'] . ' ' . $candidature['prenom'] ?></td>
                                    <td><?= $candidature['nb_candidatures'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Activité des étudiants -->
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i>
                    TOP 10 - Activité des étudiants (candidatures + wishlist)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Candidatures</th>
                                    <th>Wishlist</th>
                                    <th>Total actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['activite_etudiants'] as $activite): ?>
                                <tr>
                                    <td><?= $activite['nom'] . ' ' . $activite['prenom'] ?></td>
                                    <td><?= $activite['nb_candidatures'] ?></td>
                                    <td><?= $activite['nb_wishlist'] ?></td>
                                    <td><?= $activite['total'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des candidatures
const candidaturesCtx = document.getElementById('candidaturesChart');
new Chart(candidaturesCtx, {
    type: 'pie',
    data: {
        labels: ['Acceptées', 'Refusées', 'En attente', 'En entretien'],
        datasets: [{
            data: [
                <?= $stats['candidatures_stats']['candidatures_acceptees'] ?? 0 ?>,
                <?= $stats['candidatures_stats']['candidatures_refusees'] ?? 0 ?>,
                <?= $stats['candidatures_stats']['candidatures_en_attente'] ?? 0 ?>,
                <?= $stats['candidatures_stats']['candidatures_entretien'] ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Répartition des candidatures par statut'
            }
        }
    }
});

// Graphique des promotions
const promotionsCtx = document.getElementById('promotionsChart');
new Chart(promotionsCtx, {
    type: 'pie',
    data: {
        labels: [<?php echo implode(', ', array_map(function($item) { return "'".$item['promotion']."'"; }, $stats['par_promotion'])); ?>],
        datasets: [{
            data: [<?php echo implode(', ', array_map(function($item) { return $item['count']; }, $stats['par_promotion'])); ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Répartition des étudiants par promotion'
            }
        }
    }
});

// Graphique des formations
const formationsCtx = document.getElementById('formationsChart');
new Chart(formationsCtx, {
    type: 'pie',
    data: {
        labels: [<?php echo implode(', ', array_map(function($item) { return "'".$item['formation']."'"; }, $stats['par_formation'])); ?>],
        datasets: [{
            data: [<?php echo implode(', ', array_map(function($item) { return $item['count']; }, $stats['par_formation'])); ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Répartition des étudiants par formation'
            }
        }
    }
});
</script>

<?php require_once 'app/views/includes/footer.php'; ?>