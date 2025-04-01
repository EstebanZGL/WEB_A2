<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userData['title']; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
    <!-- Ajout de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'app/views/partials/header.php'; ?>

    <main class="container section">
        <div class="dashboard-header">
            <h1 class="gradient-text"><?php echo $userData['title']; ?></h1>
        </div>

        <div class="dashboard-stats">
            <?php foreach ($userData['stats'] as $key => $value): ?>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-value"><?php echo $value; ?></h3>
                        <p class="stat-label"><?php echo ucfirst($key); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="dashboard-grid">
            <!-- Graphique des candidatures -->
            <div class="dashboard-card">
                <h2>État des candidatures</h2>
                <div class="chart-container">
                    <canvas id="candidaturesChart"></canvas>
                </div>
            </div>

            <!-- Liste des offres avec pagination -->
                    <?php if (isset($userData['offers']) && !empty($userData['offers'])): ?>
            <div class="dashboard-card">
                <h2>Offres disponibles</h2>
                <div class="offers-list">
                    <?php foreach ($userData['offers'] as $offer): ?>
                        <div class="offer-item">
                            <h3><?php echo htmlspecialchars($offer['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($offer['nom_entreprise']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <!-- Pagination des offres -->
                        <div class="pagination">
                            <?php
                        $total_pages = ceil($userData['total_offers'] / $userData['per_page']);
                        for ($i = 1; $i <= $total_pages; $i++):
                            ?>
                            <a href="?page=<?php echo $i; ?>" 
                               class="<?php echo ($userData['current_page'] == $i) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Wishlist avec pagination -->
            <?php if (isset($userData['wishlist'])): ?>
            <div class="dashboard-card">
                <h2>Ma Wishlist</h2>
                <div class="wishlist-items">
                    <?php if (!empty($userData['wishlist'])): ?>
                        <?php foreach ($userData['wishlist'] as $item): ?>
                            <div class="wishlist-item">
                                <h3><?php echo htmlspecialchars($item['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($item['nom_entreprise']); ?></p>
        </div>
                        <?php endforeach; ?>
                        
                        <!-- Pagination de la wishlist -->
                        <div class="pagination">
                            <?php
                            $total_wishlist_pages = ceil($userData['total_wishlist'] / $userData['per_page']);
                            for ($i = 1; $i <= $total_wishlist_pages; $i++):
                            ?>
                                <a href="?wishlist_page=<?php echo $i; ?>" 
                                   class="<?php echo ($userData['current_wishlist_page'] == $i) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucun élément dans la wishlist</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'app/views/partials/footer.php'; ?>

    <script>
        // Configuration du graphique en camembert
        const ctx = document.getElementById('candidaturesChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Acceptées', 'Refusées', 'En attente'],
                datasets: [{
                    data: [
                        <?php echo $userData['candidatures']['acceptees']; ?>,
                        <?php echo $userData['candidatures']['refusees']; ?>,
                        <?php echo $userData['candidatures']['en_attente']; ?>
                    ],
                    backgroundColor: [
                        '#4CAF50', // Vert pour acceptées
                        '#f44336', // Rouge pour refusées
                        '#FFC107'  // Jaune pour en attente
                    ]
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
    </script>
</body>
</html>