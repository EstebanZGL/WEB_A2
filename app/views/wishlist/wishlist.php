<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ma Wishlist | LeBonPlan</title>
    <meta name="description" content="Consultez et gérez votre liste de souhaits d'offres d'emploi." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <link rel="stylesheet" href="public/css/wishlist.css">
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
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
                <a href="wishlist" class="mobile-nav-link active">Ma Wishlist</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                <a href="admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="login" id="mobile-login-Bouton" class="button button-primary button-glow">Connexion</a>
                    <a href="logout" id="mobile-logout-Bouton" class="button button-primary button-glow" style="display:none;">Déconnexion</a>
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
                    <a href="wishlist" class="nav-link wishlist-icon-link active" title="Ma Wishlist">
                        <svg class="wishlist-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </a>
                    <a href="gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                    <a href="admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                </nav>

                <div id="user-status">
                    <a href="login" id="login-Bouton" class="button button-outline button-glow">Connexion</a>
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>

        <main>
            <div class="container">
                <div class="wishlist-container">
                    <h1 class="wishlist-title">Ma Liste de Souhaits</h1>

                    <?php if (isset($_SESSION['status_message'])): ?>
                        <div class="status-message <?= $_SESSION['status_type'] ?>">
                            <?= $_SESSION['status_message'] ?>
                        </div>
                        <?php 
                        // Effacer le message après l'affichage
                        unset($_SESSION['status_message']);
                        unset($_SESSION['status_type']);
                        ?>
                    <?php endif; ?>

                    <?php if (empty($wishlistItems)): ?>
                        <div class="wishlist-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <h3>Votre liste de souhaits est vide</h3>
                            <p>Vous n'avez pas encore ajouté d'offres de stage à votre liste de souhaits.</p>
                            <a href="offres" class="btn btn-primary">Découvrir nos offres</a>
                        </div>
                    <?php else: ?>
                        <div class="wishlist-items">
                            <?php foreach ($wishlistItems as $item): ?>
                                <div class="wishlist-item">
                                    <div class="item-details">
                                        <h3><?= htmlspecialchars($item->titre) ?></h3>
                                        <p class="item-price"><?= number_format($item->remuneration, 0, ',', ' ') ?> €/an</p>
                                        <p class="item-description"><?= htmlspecialchars($item->entreprise) ?> - <?= htmlspecialchars($item->competences) ?></p>
                                        <p class="item-dates">Du <?= date('d/m/Y', strtotime($item->date_debut)) ?> au <?= date('d/m/Y', strtotime($item->date_fin)) ?> (<?= $item->duree_stage ?> mois)</p>
                                    </div>
                                    <div class="item-actions">
                                        <a href="offres/details/<?= $item->id ?>" class="btn btn-info">Voir détails</a>
                                        <form action="wishlist/remove" method="POST" class="remove-form">
                                            <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                            <button type="submit" class="btn btn-danger">Retirer</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

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
                            <li><a href="#" class="footer-link">Ressources de Carrière</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mettre à jour l'année dans le copyright
            document.getElementById('current-year').textContent = new Date().getFullYear();
        });
    </script>
    
    <!-- Charger le script app.js à la fin du body -->
    <script src="public/js/app.js"></script>
</body>
</html>