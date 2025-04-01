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
                        <h1 class="section-title">Ajouter un pilote</h1>
                        <a href="../../gestion?section=pilotes" class="button button-secondary">Retour à la liste</a>
                        <form action="../../gestion/pilotes/add" method="post" class="form" id="add-pilote-form">
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="nom">Nom</label>
                                    <input type="text" id="nom" name="nom" required placeholder="Nom du pilote" oninput="generateEmail()">
                                </div>
                                <div class="form-group half">
                                    <label for="prenom">Prénom</label>
                                    <input type="text" id="prenom" name="prenom" required placeholder="Prénom du pilote" oninput="generateEmail()">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required readonly>
                                <small>L'email sera généré automatiquement au format premièreLettrePrenom+nom@cesi.fr</small>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="text" id="password" name="password" placeholder="Laissez vide pour 'changeme'">
                            </div>
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="departement">Département</label>
                                    <select id="departement" name="departement" required>
                                        <option value="">Sélectionner un département</option>
                                            <option value="Informatique">Informatique</option>
                                            <option value="BTP">BTP</option>
                                            <option value="Ressources Humaines">Ressources Humaines</option>
                                            <option value="Généraliste">Généraliste</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Qualité">Qualité</option>
                                        </select>
                                    </div>
                                <div class="form-group half">
                                    <label for="specialite">Spécialité</label>
                                    <input type="text" id="specialite" name="specialite" required placeholder="Spécialité du pilote">
                        </div>
                                </div>
                                
                            <div class="form-actions">
                                    <button type="submit" class="button button-primary">Ajouter le pilote</button>
                                <a href="../../gestion?section=pilotes" class="button button-outline">Annuler</a>
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
        
        // Générer automatiquement l'email au format premièreLettrePrenom+nom@cesi.fr
        function generateEmail() {
            const prenom = document.getElementById('prenom').value.trim();
            const nom = document.getElementById('nom').value.trim().toLowerCase();
            
            if (prenom && nom) {
                // Prendre la première lettre du prénom + nom complet
                const premiereLettrePrenom = prenom.charAt(0).toLowerCase();
                
                // Normaliser le nom (supprimer les accents et les espaces)
                const nomNormalise = nom.normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "")  // Supprimer les accents
                    .replace(/\s+/g, "");             // Supprimer les espaces
                
                const email = `${premiereLettrePrenom}${nomNormalise}@cesi.fr`;
                document.getElementById('email').value = email;
            } else {
                document.getElementById('email').value = '';
        }
        }
    </script>
</body>
</html>