<?php
// Vérifier si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
    header("Location: login");
    exit;
}

// Inclure l'en-tête
require_once 'app/views/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Ajouter un pilote</title>
    <meta name="description" content="Ajouter un nouveau pilote sur la plateforme LeBonPlan." />
    <link rel="stylesheet" href="../../public/css/style.css" />
    <link rel="stylesheet" href="../../public/css/responsive-complete.css">
    <link rel="stylesheet" href="../../public/css/gestion.css">
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="../../public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="../../home" class="mobile-nav-link">Accueil</a>
                <a href="../../offres" class="mobile-nav-link">Emplois</a>
                <a href="../../gestion" class="mobile-nav-link active">Gestion</a>
                <a href="../../admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="../../logout" class="button button-primary button-glow">Déconnexion</a>
                </div>
            </div>
        </div>
        <header class="navbar">
            <div class="container">
                <!-- Bouton burger pour le menu mobile -->
                <button class="mobile-menu-toggle" aria-label="Menu mobile">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
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
                    <div class="section-header">
                        <h1 class="section-title">Ajouter un pilote</h1>
                        <a href="../../gestion?section=pilotes" class="button button-secondary">Retour à la liste</a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Informations du pilote</h2>
        </div>
                        <div class="card-body">
                            <form action="../../gestion/pilotes/add" method="post">
                                <div class="form-group">
                                    <label for="nom">Nom <span class="required">*</span></label>
                                    <input type="text" id="nom" name="nom" class="form-control" required>
    </div>
                                
                                <div class="form-group">
                                    <label for="prenom">Prénom <span class="required">*</span></label>
                                    <input type="text" id="prenom" name="prenom" class="form-control" required>
</div>

                                <div class="form-group">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Laissez vide pour utiliser 'changeme'">
                                </div>
                                
                                <div class="form-group">
                                    <label for="departement">Département</label>
                                    <select id="departement" name="departement" class="form-control">
                                        <option value="">Sélectionnez un département</option>
                                        <option value="Département 1">Département 1</option>
                                        <option value="Département 2">Département 2</option>
                                        <option value="Département 3">Département 3</option>
                                        <option value="Département 4">Département 4</option>
                                        <option value="Département 5">Département 5</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="specialite">Spécialité</label>
                                    <select id="specialite" name="specialite" class="form-control">
                                        <option value="">Sélectionnez une spécialité</option>
                                        <option value="Spécialité 1">Spécialité 1</option>
                                        <option value="Spécialité 2">Spécialité 2</option>
                                        <option value="Spécialité 3">Spécialité 3</option>
                                    </select>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="button button-primary">Enregistrer</button>
                                </div>
                            </form>
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
        
        // Afficher le lien Admin si l'utilisateur est administrateur
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
                document.getElementById('page-admin').style.display = 'block';
                document.getElementById('mobile-page-admin').style.display = 'block';
            <?php endif; ?>
        });
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="../../public/js/mobile-menu.js"></script>
</body>
</html>
