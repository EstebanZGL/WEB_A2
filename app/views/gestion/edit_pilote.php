<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Modifier un pilote</title>
    <meta name="description" content="Modifier les informations d'un pilote sur la plateforme LeBonPlan." />
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
                    <a href="../../logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
        </header>
        
        <main>
            <section class="section">
                <div class="container">
                    <h1 class="section-title">Modifier un pilote</h1>
                    
                    <div class="breadcrumb">
                        <a href="../../gestion?section=pilotes">Gestion des pilotes</a> &gt; Modifier un pilote
                    </div>
                    
                    <?php if (isset($pilote) && $pilote): ?>
                    <form action="../../gestion/pilotes/edit?id=<?php echo $pilote['id']; ?>" method="post" class="form">
                        <div class="form-group">
                            <label for="nom">Nom:</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="<?php echo htmlspecialchars($pilote['nom']); ?>" disabled>
                            <small class="form-text text-muted">Le nom ne peut pas être modifié</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="prenom">Prénom:</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" value="<?php echo htmlspecialchars($pilote['prenom']); ?>" disabled>
                            <small class="form-text text-muted">Le prénom ne peut pas être modifié</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($pilote['email']); ?>" disabled>
                            <small class="form-text text-muted">L'email ne peut pas être modifié</small>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="departement">Ville</label>
                                <input type="text" id="departement" name="departement" required placeholder="Ville du pilote" value="<?php echo htmlspecialchars($pilote['departement']); ?>">
                            </div>
                            <div class="form-group half">
                                <label for="specialite">Spécialité</label>
                                <select id="specialite" name="specialite" required>
                                    <option value="">Sélectionner une spécialité</option>
                                    <option value="Informatique" <?php echo $pilote['specialite'] === 'Informatique' ? 'selected' : ''; ?>>Informatique</option>
                                    <option value="BTP" <?php echo $pilote['specialite'] === 'BTP' ? 'selected' : ''; ?>>BTP</option>
                                    <option value="Ressources Humaines" <?php echo $pilote['specialite'] === 'Ressources Humaines' ? 'selected' : ''; ?>>Ressources Humaines</option>
                                    <option value="Généraliste" <?php echo $pilote['specialite'] === 'Généraliste' ? 'selected' : ''; ?>>Généraliste</option>
                                    <option value="Marketing" <?php echo $pilote['specialite'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                    <option value="Qualité" <?php echo $pilote['specialite'] === 'Qualité' ? 'selected' : ''; ?>>Qualité</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-buttons">
                            <button type="submit" class="button button-primary">Enregistrer</button>
                            <a href="../../gestion?section=pilotes" class="button button-secondary">Annuler</a>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="alert alert-danger">
                        Pilote non trouvé.
                    </div>
                    <div class="form-buttons">
                        <a href="../../gestion?section=pilotes" class="button button-primary">Retour à la liste</a>
                    </div>
                    <?php endif; ?>
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
        
        // Afficher le lien Admin si l'utilisateur est un administrateur
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