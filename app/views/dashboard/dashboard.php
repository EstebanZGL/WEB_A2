<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant - LeBonPlan</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/stats.css">
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <link rel="stylesheet" href="public/css/wishlist.css">
    <link rel="stylesheet" href="public/css/dashboard.css">
    
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link">Emplois</a>
                <a href="dashboard" class="mobile-nav-link active">Tableau de bord</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="logout" class="button button-primary button-glow">Déconnexion</a>
                </div>
            </div>
        </div>
        
        <header class="navbar">
            <div class="container">
                <img src="public/images/logo.png" alt="D" width="150" height="170">
                
                <!-- Bouton Menu Mobile -->
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="navbar-nav">
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="dashboard" class="nav-link active">Tableau de bord</a>
                </nav>

                <div id="user-status">
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message etudiant"></span>
        </header>
    
        <div class="container dashboard-container">
            <div class="dashboard-header">
                <h1>Tableau de bord étudiant</h1>
            </div>
            
            <!-- Statistiques des candidatures -->
            <div class="stats-overview">
                <div class="stat-card">
                    <h3>Total des candidatures</h3>
                    <p class="stat-value"><?= $stats['total'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>En attente</h3>
                    <p class="stat-value"><?= $stats['en_attente'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Acceptées</h3>
                    <p class="stat-value"><?= $stats['acceptees'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Refusées</h3>
                    <p class="stat-value"><?= $stats['refusees'] ?></p>
                </div>
            </div>
            
            <!-- Section Candidatures -->
            <div class="dashboard-section">
                <div class="stats-table">
                    <h3>
                        <span class="iconify" data-icon="mdi:file-document"></span>
                        Mes candidatures
                    </h3>
                    
                    <?php if (empty($candidatures)): ?>
                    <div class="empty-state">
                        <span class="iconify" data-icon="mdi:file-document-outline" style="font-size: 48px; color: var(--primary); margin-bottom: 1rem;"></span>
                        <p>Vous n'avez pas encore postulé à des offres de stage.</p>
                        <a href="offres" class="button button-primary">Parcourir les offres</a>
                    </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Type</th>
                                <th>Lieu</th>
                                <th>Date de candidature</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $candidature): ?>
                            <tr>
                                <td><?= htmlspecialchars($candidature['offre_titre']) ?></td>
                                <td><?= htmlspecialchars($candidature['entreprise_nom']) ?></td>
                                <td><?= htmlspecialchars($candidature['offre_type']) ?></td>
                                <td><?= htmlspecialchars($candidature['offre_lieu']) ?></td>
                                <td><?= date('d/m/Y', strtotime($candidature['date_candidature'])) ?></td>
                                <td>
                                    <span class="candidature-status status-<?= strtolower($candidature['statut']) ?>">
                                        <?= htmlspecialchars($candidature['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="offres/details/<?= $candidature['offre_id'] ?>" class="action-button" title="Voir les détails">
                                        <span class="iconify" data-icon="mdi:eye"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Section Wishlist -->
            <div class="dashboard-section">
                <div class="stats-table">
                    <h3>
                        <span class="iconify" data-icon="mdi:heart"></span>
                        Offres en favoris
                    </h3>
                    
                    <?php if (empty($wishlist)): ?>
                    <div class="empty-state">
                        <span class="iconify" data-icon="mdi:heart-outline" style="font-size: 48px; color: var(--accent); margin-bottom: 1rem;"></span>
                        <p>Vous n'avez pas encore ajouté d'offres à vos favoris.</p>
                        <a href="offres" class="button button-primary">Parcourir les offres</a>
                    </div>
                    <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Type</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wishlist as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['offre_titre']) ?></td>
                                <td><?= htmlspecialchars($item['entreprise_nom']) ?></td>
                                <td><?= htmlspecialchars($item['offre_type']) ?></td>
                                <td><?= htmlspecialchars($item['offre_lieu']) ?></td>
                                <td><?= htmlspecialchars($item['offre_statut']) ?></td>
                                <td class="dashboard-actions">
                                    <a href="offres/details/<?= $item['offre_id'] ?>" class="action-button" title="Voir les détails">
                                        <span class="iconify" data-icon="mdi:eye"></span>
                                    </a>
                                    <button class="action-button remove-wishlist" data-id="<?= $item['offre_id'] ?>" title="Retirer des favoris">
                                        <span class="iconify" data-icon="mdi:heart-off"></span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="home" class="footer-logo">
                            <img src="public/images/logo.png" alt="D" width="150" height="170">
                        </a>
                        <p class="footer-tagline">Votre passerelle vers des opportunités de carrière.</p>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Pour les Chercheurs d'Emploi</h3>
                        <ul>
                            <li><a href="offres" class="footer-link">Parcourir les Emplois</a></li>
                            <li><a href="mentions-legales" class="footer-link">Mentions Légales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="public/js/mobile-menu.js"></script>                           
    <script src="public/js/dashboard.js"></script>
</body>
</html>