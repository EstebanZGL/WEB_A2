<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Statistiques des offres</title>
    <meta name="description" content="Statistiques des offres de stage sur la plateforme LeBonPlan." />
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
                        <h1 class="section-title">Statistiques des offres</h1>
                        <a href="../../gestion?section=offres" class="button button-secondary">Retour à la liste</a>
                        
                        <div class="stats-overview">
                            <div class="stat-card">
                                <h3>Nombre total d'offres</h3>
                                <p class="stat-value"><?php echo $stats['total']; ?></p>
                            </div>
                            
                            <div class="stat-card">
                                <h3>Rémunération moyenne</h3>
                                <p class="stat-value"><?php echo number_format($stats['remuneration_moyenne'], 2, ',', ' '); ?> €</p>
                            </div>
                            
                            <div class="stat-card">
                                <h3>Durée moyenne des stages</h3>
                                <p class="stat-value"><?php echo number_format($stats['duree_moyenne'], 1); ?> mois</p>
                            </div>
                        </div>
                        
                        <div class="stats-row">
                            <div class="stats-chart">
                                <h3>Répartition par statut</h3>
                                <canvas id="statusChart"></canvas>
                            </div>
                            
                            <div class="stats-chart">
                                <h3>Top entreprises par nombre d'offres</h3>
                                <canvas id="companiesChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="stats-table">
                            <h3>Offres les plus récentes</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Entreprise</th>
                                        <th>Date de publication</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['recentes'] as $offre): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($offre['titre']); ?></td>
                                            <td><?php echo htmlspecialchars($offre['entreprise']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($offre['date_publication'])); ?></td>
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
        
        // Graphique de répartition par statut
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($stats['par_statut'], 'statut')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($stats['par_statut'], 'count')); ?>,
                    backgroundColor: [
                        '#28a745',
                        '#17a2b8',
                        '#6c757d'
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
        
        // Graphique des entreprises par nombre d'offres
        const companiesCtx = document.getElementById('companiesChart').getContext('2d');
        const companiesChart = new Chart(companiesCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($stats['par_entreprise'], 'nom')); ?>,
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: <?php echo json_encode(array_column($stats['par_entreprise'], 'count')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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