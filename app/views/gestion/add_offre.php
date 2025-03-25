<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Ajouter une offre</title>
    <meta name="description" content="Ajouter une nouvelle offre d'emploi" />
    <link rel="stylesheet" href="public/css/style.css" />
</head>
<body>
    <div id="app">
        <header class="navbar">
            <div class="container">
                <img src="public/images/logo.png" alt="D" width="150" height="170">

                <nav class="navbar-nav">
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="gestion" class="nav-link active">Gestion</a>
                    <a href="admin" class="nav-link" id="page-admin">Administrateur</a>
                </nav>

                <div id="user-status">
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            
                <script src="public/js/app.js"></script>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>

        <main>
            <div class="container">
                <div class="form-header">
                    <h1>Ajouter une nouvelle offre d'emploi</h1>
                    <a href="gestion" class="button button-outline">Retour à la gestion</a>
                </div>

                <form action="gestion/add" method="post" class="form-container">
                    <div class="form-group">
                        <label for="entreprise">Entreprise</label>
                        <input type="text" id="entreprise" name="entreprise" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="titre">Titre du poste</label>
                        <input type="text" id="titre" name="titre" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required class="form-control" rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="competences">Compétences requises (séparées par des virgules)</label>
                        <input type="text" id="competences" name="competences" required class="form-control" placeholder="ex: PHP, MySQL, JavaScript">
                    </div>

                    <div class="form-group">
                        <label for="remuneration">Rémunération annuelle (€)</label>
                        <input type="number" id="remuneration" name="remuneration" required class="form-control" min="0" step="1000">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-primary button-glow">Ajouter l'offre</button>
                        <a href="gestion" class="button button-outline">Annuler</a>
                    </div>
                </form>
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
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        // Mettre à jour l'année dans le copyright
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>