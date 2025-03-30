<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Statistiques des étudiants</title>
    <meta name="description" content="Statistiques des étudiants sur la plateforme LeBonPlan." />
    <link rel="stylesheet" href="../../public/css/style.css" />
    <link rel="stylesheet" href="../../public/css/responsive-complete.css">
    <link rel="stylesheet" href="../../public/css/gestion.css">
    <link rel="stylesheet" href="../../public/css/stats.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="app">
        <header class="navbar">
            <div class="container">
                <a href="../../home">
                    <img src="../../public/images/logo.png" alt="D" width="150" height="170">
                </a>
                
                <nav class="navbar-nav">
                    <a href="../../home" class="nav-link">Accueil</a>
                    <a href="../../offres" class="nav-link">Emplois</a>
                    <a href="../../gestion" class="nav-link active" id="page-gestion">Gestion</a>
                    <a href="../../admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                </nav>
                <div id="user-status">
                    <a href="../../login" id="login-Bouton" class="button button-outline button-glow" style="display:none;">Connexion</a>
                    <a href="../../logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
        </header>
        
        <main>
            <section class="section">
                <div class="container">
                    <div class="stats-container">
                        <h1 class="section-title">Statistiques des étudiants</h1>
                        <a href="../../gestion?section=etudiants" class="button button-secondary">Retour à la liste</a>
                        
                        <div class="stats-overview">
                            <div class="stat-card">
                                <h3>Nombre total d'étudiants</h3>
                                <p class="stat-value"><?php echo $stats['total']; ?></p>
                            </div>
                            
                            <div class="stat-card">
                                <h3>Étudiants avec une offre assignée</h3>
                                <p class="stat-value"><?php echo $stats['avec_offre']; ?> (<?php echo $stats['total'] > 0 ? round(($stats['avec_offre'] / $stats['total']) * 100, 1) : 0; ?>%)</p>
                            </div>
                        </div>
                        
                        <div class="stats-row">
                            <div class="stats-chart">
                                <h3>Répartition par promotion</h3>
                                <canvas id="promotionChart"></canvas>
                            </div>
                            
                            <div class="stats-chart">
                                <h3>Répartition par formation</h3>
                                <canvas id="formationChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="stats-table">
                            <h3>Top étudiants par nombre de candidatures</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Nombre de candidatures</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['candidatures'] as $etudiant): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                                            <td><?php echo $etudiant['nb_candidatures']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        // Mettre à jour l'année actuelle dans le footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Graphique de répartition par promotion
        const promotionCtx = document.getElementById('promotionChart').getContext('2d');
        const promotionChart = new Chart(promotionCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($stats['par_promotion'], 'promotion')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($stats['par_promotion'], 'count')); ?>,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Graphique de répartition par formation
        const formationCtx = document.getElementById('formationChart').getContext('2d');
        const formationChart = new Chart(formationCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($stats['par_formation'], 'formation')); ?>,
                datasets: [{
                    label: 'Nombre d\'étudiants',
                    data: <?php echo json_encode(array_column($stats['par_formation'], 'count')); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>