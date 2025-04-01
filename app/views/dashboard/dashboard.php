<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userData['title']; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
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
            <div class="dashboard-card">
                <h2>Activités récentes</h2>
                <div class="activity-list">
                    <!-- À implémenter avec les vraies activités -->
                    <p class="text-muted">Aucune activité récente</p>
                </div>
            </div>

            <div class="dashboard-card">
                <h2>Actions rapides</h2>
                <div class="quick-actions">
                    <?php if ($_SESSION['user_type'] === 'etudiant'): ?>
                        <a href="/offres" class="button button-primary">Voir les offres</a>
                        <a href="/wishlist" class="button button-secondary">Ma wishlist</a>
                    <?php elseif ($_SESSION['user_type'] === 'pilote'): ?>
                        <a href="/gestion/etudiants" class="button button-primary">Gérer les étudiants</a>
                        <a href="/gestion/entreprises" class="button button-secondary">Gérer les entreprises</a>
                    <?php elseif ($_SESSION['user_type'] === 'admin'): ?>
                        <a href="/admin/users" class="button button-primary">Gérer les utilisateurs</a>
                        <a href="/admin/pilotes" class="button button-secondary">Gérer les pilotes</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'app/views/partials/footer.php'; ?>
</body>
</html>