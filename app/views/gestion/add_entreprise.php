<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Ajouter une entreprise</title>
    <meta name="description" content="Ajouter une nouvelle entreprise sur la plateforme LeBonPlan." />
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
                        <h1 class="section-title">Ajouter une nouvelle entreprise</h1>
                        <a href="../../gestion?section=entreprises" class="button button-secondary">Retour à la liste</a>
                        
                        <form action="../../gestion/entreprises/add" method="post" class="form">
                            <div class="form-group">
                                <label for="nom">Nom de l'entreprise</label>
                                <input type="text" id="nom" name="nom" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="email_contact">Email de contact</label>
                                    <input type="email" id="email_contact" name="email_contact" required>
                                </div>
                                
                                <div class="form-group half">
                                    <label for="telephone_contact">Téléphone de contact</label>
                                    <input type="tel" id="telephone_contact" name="telephone_contact" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <textarea id="adresse" name="adresse" rows="2" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="lien_site">Site web</label>
                                <input type="url" id="lien_site" name="lien_site" placeholder="https://www.exemple.com">
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Ajouter l'entreprise</button>
                                <a href="../../gestion?section=entreprises" class="button button-outline">Annuler</a>
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
</body>
</html>