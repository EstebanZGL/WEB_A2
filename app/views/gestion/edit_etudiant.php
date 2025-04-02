<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Modifier un étudiant</title>
    <meta name="description" content="Modifier un étudiant sur la plateforme LeBonPlan." />
    <link rel="stylesheet" href="../../public/css/style.css" />
    <link rel="stylesheet" href="../../public/css/responsive-complete.css">
    <link rel="stylesheet" href="../../public/css/gestion.css">
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
                    <span id="welcome-message" style="display:none;"></span>
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
                    <div class="form-container">
                        <h1 class="section-title">Modifier un étudiant</h1>
                        <a href="../../gestion?section=etudiants" class="button button-secondary">Retour à la liste</a>
                        
                        <form action="../../gestion/etudiants/edit?id=<?php echo $etudiant['id']; ?>" method="post" class="form">
                            <div class="form-group">
                                <label>Utilisateur</label>
                                <p class="form-static-text"><?php echo htmlspecialchars($etudiant['nom'] . ' ' . $etudiant['prenom'] . ' (' . $etudiant['email'] . ')'); ?></p>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="promotion">Promotion</label>
                                    <select id="promotion" name="promotion" required>
                                        <option value="">Sélectionner une promotion</option>
                                        <option value="Promotion 2021" <?php echo $etudiant['promotion'] === 'Promotion 2021' ? 'selected' : ''; ?>>Promotion 2021</option>
                                        <option value="Promotion 2022" <?php echo $etudiant['promotion'] === 'Promotion 2022' ? 'selected' : ''; ?>>Promotion 2022</option>
                                        <option value="Promotion 2023" <?php echo $etudiant['promotion'] === 'Promotion 2023' ? 'selected' : ''; ?>>Promotion 2023</option>
                                        <option value="Promotion 2024" <?php echo $etudiant['promotion'] === 'Promotion 2024' ? 'selected' : ''; ?>>Promotion 2024</option>
                                        <option value="Promotion 2025" <?php echo $etudiant['promotion'] === 'Promotion 2025' ? 'selected' : ''; ?>>Promotion 2025</option>
                                    </select>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="formation">Formation</label>
                                    <select id="formation" name="formation" required>
                                        <option value="">Sélectionner une formation</option>
                                        <option value="Informatique" <?php echo $etudiant['formation'] === 'Informatique' ? 'selected' : ''; ?>>Informatique</option>
                                        <option value="BTP" <?php echo $etudiant['formation'] === 'BTP' ? 'selected' : ''; ?>>BTP</option>
                                        <option value="Généraliste" <?php echo $etudiant['formation'] === 'Généraliste' ? 'selected' : ''; ?>>Généraliste</option>
                                        <option value="Systèmes Embarqués" <?php echo $etudiant['formation'] === 'Systèmes Embarqués' ? 'selected' : ''; ?>>Systèmes Embarqués</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Enregistrer les modifications</button>
                                <a href="../../gestion?section=etudiants" class="button button-outline">Annuler</a>
                            </div>
                        </form>
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
    </script>
    <script src="../../public/js/app.js"></script>
</body>
</html>