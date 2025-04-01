<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Ajouter une offre</title>
    <meta name="description" content="Ajouter une nouvelle offre de stage sur la plateforme LeBonPlan." />
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
                        <h1 class="section-title">Ajouter une nouvelle offre</h1>
                        <a href="../../gestion?section=offres" class="button button-secondary">Retour à la liste</a>
                        
                        <form action="../../gestion/offres/add" method="post" class="form">
                            <div class="form-group">
                                <label for="entreprise_id">Entreprise</label>
                                <select id="entreprise_id" name="entreprise_id" required>
                                    <option value="">Sélectionner une entreprise</option>
                                    <?php foreach ($entreprises as $entreprise): ?>
                                        <option value="<?php echo $entreprise['id']; ?>"><?php echo htmlspecialchars($entreprise['nom']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="titre">Titre de l'offre</label>
                                <input type="text" id="titre" name="titre" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="6" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="remuneration">Rémunération (€)</label>
                                <input type="number" id="remuneration" name="remuneration" step="0.01" min="0" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="date_debut">Date de début</label>
                                    <input type="date" id="date_debut" name="date_debut" required>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="date_fin">Date de fin</label>
                                    <input type="date" id="date_fin" name="date_fin" required>
                                </div>
                            </div>
                            <div class="form-row">
                                
                                <div class="form-group half">
                                    <label for="duree_stage">Durée du stage (mois)</label>
                                    <input type="number" id="duree_stage" name="duree_stage" min="1" max="12" required>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="statut">Statut</label>
                                    <select id="statut" name="statut" required>
                                        <option value="ACTIVE">Active</option>
                                        <option value="POURVUE">Pourvue</option>
                                        <option value="EXPIREE">Expirée</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="type">Type d'offre</label>
                                    <select id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="Informatique & Tech">Informatique & Tech</option>
                                        <option value="BTP & Construction">BTP & Construction</option>
                                        <option value="Marketing & Communication">Marketing & Communication</option>
                                        <option value="Finance & Comptabilité">Finance & Comptabilité</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="lieu">Lieu</label>
                                    <input type="text" id="lieu" name="lieu" placeholder="Ex: Paris, Lyon, Marseille..." required>
                                </div>
                            </div>


                            </div>
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Ajouter l'offre</button>
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
                                          (fin.getDate() >= debut.getDate() ? 0 : -1);
                        
                        dureeStage.value = Math.max(1, diffMonths);
                    }
                }
            }
            
            dateDebut.addEventListener('change', updateDuree);
            dateFin.addEventListener('change', updateDuree);
        });
    </script>
</body>
</html>