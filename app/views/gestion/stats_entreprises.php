<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Statistiques des entreprises</title>
    <meta name="description" content="Statistiques des entreprises sur la plateforme LeBonPlan." />
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
                        <h1 class="section-title">Statistiques des entreprises</h1>
                        <a href="../../gestion?section=entreprises" class="button button-secondary">Retour à la liste</a>
                        
                        <div class="stats-overview">
                            <div class="stat-card">
                                <h3>Nombre total d'entreprises</h3>
                                <p class="stat-value"><?php echo $stats['total']; ?></p>
                            </div>
                        </div>
                        
                        <div class="stats-row">
                            <div class="stats-chart">
                                <h3>Nombre d'offres par entreprise</h3>
                                <canvas id="offresParEntrepriseChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="stats-table">
                            <h3>Entreprises les plus récentes</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Date de création</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['recentes'] as $entreprise): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($entreprise['nom']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($entreprise['date_creation'])); ?></td>
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
        
        // Graphique du nombre d'offres par entreprise
        const offresCtx = document.getElementById('offresParEntrepriseChart').getContext('2d');
        const offresChart = new Chart(offresCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($stats['offres_par_entreprise'], 'nom')); ?>,
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: <?php echo json_encode(array_column($stats['offres_par_entreprise'], 'nb_offres')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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