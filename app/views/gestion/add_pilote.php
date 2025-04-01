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
                    <a href="../../logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
        </header>
        
        <main>
            <section class="section">
                <div class="container">
                    <h1 class="section-title">Ajouter un pilote</h1>
                    
                    <div class="breadcrumb">
                        <a href="../../gestion?section=pilotes">Gestion des pilotes</a> &gt; Ajouter un pilote
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Informations du pilote</h2>
                        </div>
                        <div class="card-body">
                            <form action="../../gestion/pilotes/add" method="post" class="form">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nom">Nom:</label>
                                        <input type="text" id="nom" name="nom" class="form-control" required onkeyup="generateEmail()">
                        </div>
                        
                                    <div class="form-group col-md-6">
                                        <label for="prenom">Prénom:</label>
                                        <input type="text" id="prenom" name="prenom" class="form-control" required onkeyup="generateEmail()">
                        </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control" required readonly>
                                        <small class="form-text text-muted">Format: premièreLettrePrénom+nom@cesi.fr</small>
                        </div>
                        
                                    <div class="form-group col-md-6">
                                        <label for="password">Mot de passe:</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                        <small class="form-text text-muted">Laissez vide pour "changeme"</small>
                        </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="departement">Département:</label>
                                        <select id="departement" name="departement" class="form-control" required>
                                            <option value="">Sélectionnez un département</option>
                                            <option value="Informatique">Informatique</option>
                                            <option value="BTP">BTP</option>
                                            <option value="Ressources Humaines">Ressources Humaines</option>
                                            <option value="Généraliste">Généraliste</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Qualité">Qualité</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="specialite">Spécialité:</label>
                                        <input type="text" id="specialite" name="specialite" class="form-control" required>
                        </div>
                                </div>
                                
                                <div class="form-buttons">
                                    <button type="submit" class="button button-primary">Ajouter le pilote</button>
                                    <a href="../../gestion?section=pilotes" class="button button-secondary">Annuler</a>
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
        
        // Afficher le lien Admin si l'utilisateur est un administrateur
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
            document.getElementById('page-admin').style.display = 'block';
            document.getElementById('mobile-page-admin').style.display = 'block';
            <?php endif; ?>
            
            // Générer l'email au chargement de la page si nom et prénom sont déjà remplis
            generateEmail();
        });
        
        // Fonction pour générer l'email automatiquement
        function generateEmail() {
            const nom = document.getElementById('nom').value.trim().toLowerCase();
            const prenom = document.getElementById('prenom').value.trim().toLowerCase();
            
            if (nom && prenom) {
                // Prendre la première lettre du prénom + nom complet
                const premiereLettrePrenom = prenom.charAt(0);
                const email = premiereLettrePrenom + nom + '@cesi.fr';
                
                // Remplacer les caractères spéciaux et espaces
                const emailSanitized = email
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Supprimer les accents
                    .replace(/[^a-z0-9@\.]/g, ''); // Garder seulement les lettres, chiffres, @ et .
                
                document.getElementById('email').value = emailSanitized;
            }
        }
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="../../public/js/mobile-menu.js"></script>
</body>
</html>