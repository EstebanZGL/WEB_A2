<?php
// Vérifier si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
    header("Location: login");
    exit;
}
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
                        <h1 class="section-title">Ajouter un nouveau pilote</h1>
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
                                <input type="email" id="email" name="email" required>
                                <small>L'email sera généré automatiquement au format prenom.nom@viacesi.fr</small>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="text" id="password" name="password" placeholder="Laissez vide pour utiliser 'changeme'">
                            </div>
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="departement">Département</label>
                                    <select id="departement" name="departement">
                                        <option value="">Sélectionner un département</option>
                                        <option value="Département 1">Département 1</option>
                                        <option value="Département 2">Département 2</option>
                                        <option value="Département 3">Département 3</option>
                                        <option value="Département 4">Département 4</option>
                                        <option value="Département 5">Département 5</option>
                                    </select>
                                </div>
                                <div class="form-group half">
                                    <label for="specialite">Spécialité</label>
                                    <select id="specialite" name="specialite">
                                        <option value="">Sélectionner une spécialité</option>
                                        <option value="Spécialité 1">Spécialité 1</option>
                                        <option value="Spécialité 2">Spécialité 2</option>
                                        <option value="Spécialité 3">Spécialité 3</option>
                                    </select>
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
        
        // Afficher le lien Admin si l'utilisateur est administrateur
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
                document.getElementById('page-admin').style.display = 'block';
            <?php endif; ?>
        });
        
        // Générer automatiquement l'email au format prenom.nom@viacesi.fr
        function generateEmail() {
            const prenom = document.getElementById('prenom').value.trim().toLowerCase();
            const nom = document.getElementById('nom').value.trim().toLowerCase();
            
            if (prenom && nom) {
                // Remplacer les caractères accentués et les espaces
                const prenomNormalise = prenom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/\s+/g, "-");
                const nomNormalise = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/\s+/g, "-");
                
                const email = `${prenomNormalise}.${nomNormalise}@viacesi.fr`;
                document.getElementById('email').value = email;
            } else {
                document.getElementById('email').value = '';
            }
        }
    </script>
</body>
</html>