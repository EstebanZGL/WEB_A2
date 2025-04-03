<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Modifier une offre</title>
    <meta name="description" content="Modifier une offre de stage sur la plateforme LeBonPlan." />
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
                        <h1 class="section-title">Modifier une offre</h1>
                        <a href="../../gestion?section=offres" class="button button-secondary">Retour à la liste</a>
                        
                        <form action="../../gestion/offres/edit?id=<?php echo $offre['id']; ?>" method="post" class="form">
                            <!-- Champ caché pour conserver le createur_id -->
                            <input type="hidden" name="createur_id" value="<?php echo $offre['createur_id']; ?>">
                            
                            <div class="form-group">
                                <label for="entreprise_id">Entreprise</label>
                                <select id="entreprise_id" name="entreprise_id" required>
                                    <option value="">Sélectionner une entreprise</option>
                                    <?php foreach ($entreprises as $entreprise): ?>
                                        <option value="<?php echo $entreprise['id']; ?>" <?php echo $entreprise['id'] == $offre['entreprise_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($entreprise['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="titre">Titre de l'offre</label>
                                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($offre['titre']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($offre['description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="remuneration">Rémunération (€)</label>
                                <input type="number" id="remuneration" name="remuneration" step="0.01" min="0" value="<?php echo $offre['remuneration']; ?>" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="date_debut">Date de début</label>
                                    <input type="date" id="date_debut" name="date_debut" value="<?php echo $offre['date_debut']; ?>" required>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="date_fin">Date de fin</label>
                                    <input type="date" id="date_fin" name="date_fin" value="<?php echo $offre['date_fin']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="duree_stage">Durée du stage (mois)</label>
                                    <input type="number" id="duree_stage" name="duree_stage" min="1" max="12" value="<?php echo $offre['duree_stage']; ?>">
                                </div>
                                
                                <div class="form-group half">
                                    <label for="statut">Statut</label>
                                    <select id="statut" name="statut" required>
                                        <option value="ACTIVE" <?php echo $offre['statut'] == 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                        <option value="POURVUE" <?php echo $offre['statut'] == 'POURVUE' ? 'selected' : ''; ?>>Pourvue</option>
                                        <option value="EXPIREE" <?php echo $offre['statut'] == 'EXPIREE' ? 'selected' : ''; ?>>Expirée</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="type">Type d'offre</label>
                                    <select id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="Informatique & Tech" <?php echo $offre['type'] == 'Informatique & Tech' ? 'selected' : ''; ?>>Informatique & Tech</option>
                                        <option value="BTP & Construction" <?php echo $offre['type'] == 'BTP & Construction' ? 'selected' : ''; ?>>BTP & Construction</option>
                                        <option value="Marketing & Communication" <?php echo $offre['type'] == 'Marketing & Communication' ? 'selected' : ''; ?>>Marketing & Communication</option>
                                        <option value="Finance & Comptabilité" <?php echo $offre['type'] == 'Finance & Comptabilité' ? 'selected' : ''; ?>>Finance & Comptabilité</option>
                                        <option value="Autre" <?php echo $offre['type'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
                                    </select>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="lieu">Lieu</label>
                                    <input type="text" id="lieu" name="lieu" value="<?php echo htmlspecialchars($offre['lieu'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="date_publication">Date de publication</label>
                                <input type="date" id="date_publication" name="date_publication" value="<?php echo $offre['date_publication']; ?>" required>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Enregistrer les modifications</button>
                                <a href="../../gestion?section=offres" class="button button-outline">Annuler</a>
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
        
        // Calculer automatiquement la durée du stage lorsque les dates changent
        document.addEventListener('DOMContentLoaded', function() {
            const dateDebut = document.getElementById('date_debut');
            const dateFin = document.getElementById('date_fin');
            const dureeStage = document.getElementById('duree_stage');
            
            function updateDuree() {
                if (dateDebut.value && dateFin.value) {
                    const debut = new Date(dateDebut.value);
                    const fin = new Date(dateFin.value);
                    
                    if (fin >= debut) {
                        // Calculer la différence en mois
                        const diffMonths = (fin.getFullYear() - debut.getFullYear()) * 12 + 
                                          (fin.getMonth() - debut.getMonth()) + 
                                          (fin.getDate() >= debut.getDate() ? 0 : -1) + 1;
                        
                        dureeStage.value = Math.max(1, diffMonths);
                    }
                }
            }
            
            dateDebut.addEventListener('change', updateDuree);
            dateFin.addEventListener('change', updateDuree);
            
            // Déclencher le calcul au chargement de la page
            updateDuree();
        });
    </script>
</body>
</html>