<?php
// Pas besoin de démarrer une session ici car elle est déjà démarrée dans le contrôleur
require_once 'config/database.php';
$conn = getDbConnection();

// Vérifier si l'objet offre existe
if (!isset($offre) || empty($offre)) {
    header('Location: offres');
    exit();
}

// Récupérer les compétences associées à l'offre si nécessaire
// Cette partie peut être déplacée vers le contrôleur plus tard
$offre_id = $offre->id;
$competences = [];

try {
    $sql_competences = "SELECT c.nom, c.description, c.categorie 
                        FROM offre_competence oc 
                        JOIN competence c ON oc.competence_id = c.id 
                        WHERE oc.offre_id = ?";
    $stmt_comp = $conn->prepare($sql_competences);
    $stmt_comp->execute([$offre_id]);
    $competences = $stmt_comp->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gérer l'erreur silencieusement pour ne pas bloquer l'affichage
    // echo "Erreur: " . $e->getMessage();
}

// Variables pour les messages
$message = '';
$messageType = '';

// Traitement du formulaire de candidature
if (isset($_POST['submit_candidature'])) {
    // Code de traitement de candidature...
    // À implémenter si nécessaire
}

// Ajouter à la wishlist
if (isset($_POST['add_wishlist'])) {
    // Ce code sera géré par le contrôleur WishlistController
    // via le formulaire qui pointe vers wishlist/add
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($offre->titre); ?> - LeBonPlan</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <!-- Ajout du fichier CSS pour la wishlist -->
    <link rel="stylesheet" href="public/css/wishlist.css">
    <!-- Ajout d'Iconify pour les icônes -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <style>
        /* Styles spécifiques pour la page de détails */
        .offre-details {
            background-color: var(--bg-card);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .offre-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }
        
        .offre-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .offre-entreprise {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .offre-content {
            padding: 2rem;
        }
        
        .offre-section {
            margin-bottom: 2rem;
        }
        
        .offre-section h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 0.5rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .competences-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1rem;
        }
        
        .competence-badge {
            background-color: rgba(0, 255, 136, 0.1);
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .entreprise-info {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .candidature-form {
            background-color: var(--bg-card);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .form-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            background-color: rgba(0, 0, 0, 0.1);
            color: var(--text-light);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: rgba(0, 255, 136, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .alert-error {
            background-color: rgba(255, 0, 0, 0.1);
            color: #ff5555;
            border: 1px solid rgba(255, 0, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="/public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="/home" class="mobile-nav-link">Accueil</a>
                <a href="/offres" class="mobile-nav-link active">Emplois</a>
                <a href="/gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
                <a href="/admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
                <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
                <a href="/wishlist" class="mobile-nav-link" id="mobile-wishlist-link" style="display:none;">Ma Wishlist</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="/login" id="mobile-login-Bouton" class="button button-primary button-glow">Connexion</a>
                    <a href="/logout" id="mobile-logout-Bouton" class="button button-primary button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
        </div>
        
        <header class="navbar">
            <div class="container">
                <img src="/public/images/logo.png" alt="D" width="150" height="170">

                <!-- Bouton Menu Mobile -->
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="navbar-nav">
                    <a href="/home" class="nav-link">Accueil</a>
                    <a href="/offres" class="nav-link active">Emplois</a>
                    <a href="/gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                    <a href="/admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                    <!-- Le lien wishlist sera ajouté dynamiquement par JavaScript pour les étudiants -->
                    <a href="/wishlist" class="nav-link wishlist-icon-link" id="wishlist-link" style="display:none;" title="Ma Wishlist">
                        <span class="iconify" data-icon="mdi:heart" width="20" height="20"></span>
                    </a>
                </nav>

                <div id="user-status">
                    <a href="/login" id="login-Bouton" class="button button-outline button-glow">Connexion</a>
                    <a href="/logout" id="logout-Bouton" class="button button-outline button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>

        <main>
            <div class="container">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType == 'success' ? 'success' : 'error'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="offre-details">
                    <div class="offre-header">
                        <h1 class="offre-title"><?php echo htmlspecialchars($offre->titre); ?></h1>
                        <div class="offre-entreprise"><?php echo htmlspecialchars($offre->entreprise); ?></div>
                    </div>
                    
                    <div class="offre-content">
                        <section class="offre-section">
                            <h3>Description du poste</h3>
                            <p><?php echo nl2br(htmlspecialchars($offre->description)); ?></p>
                        </section>
                        
                        <section class="offre-section">
                            <h3>Informations clés</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:currency-eur" width="24" height="24"></span>
                                    <div><strong>Rémunération:</strong> <?php echo number_format($offre->remuneration, 0, ',', ' '); ?> €/an</div>
                                </div>
                                
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:calendar" width="24" height="24"></span>
                                    <div><strong>Publication:</strong> <?php echo date('d/m/Y', strtotime($offre->date_publication)); ?></div>
                                </div>
                                
                                <?php if (!empty($offre->ville)): ?>
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:map-marker" width="24" height="24"></span>
                                    <div><strong>Lieu:</strong> <?php echo htmlspecialchars($offre->ville); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </section>
                        
                        <?php if(!empty($competences)): ?>
                        <section class="offre-section">
                            <h3>Compétences requises</h3>
                            <div class="competences-list">
                                <?php foreach ($competences as $competence): ?>
                                    <div class="competence-badge" title="<?php echo htmlspecialchars($competence['description'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($competence['nom']); ?> 
                                        <?php if (!empty($competence['categorie'])): ?>
                                        <small>(<?php echo htmlspecialchars($competence['categorie']); ?>)</small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <?php endif; ?>
                        
                        <section class="offre-section">
                            <h3>À propos de l'entreprise</h3>
                            <p>Pour plus d'informations sur cette entreprise, veuillez contacter directement l'employeur.</p>
                        </section>
                        
                        <div class="action-buttons">
                            <form method="post" action="/wishlist/add">
                                <input type="hidden" name="item_id" value="<?php echo $offre->id; ?>">
                                <button type="submit" class="button button-secondary">
                                    <span class="iconify" data-icon="mdi:heart-outline" width="20" height="20"></span> Ajouter aux favoris
                                </button>
                            </form>
                            <a href="#candidature-form" class="button button-primary button-glow">
                                <span class="iconify" data-icon="mdi:send" width="20" height="20"></span> Postuler maintenant
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="candidature-form" id="candidature-form">
                    <h2 class="form-title">Déposer votre candidature</h2>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="cv">Votre CV (PDF, DOC, DOCX)</label>
                            <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lettre_motivation">Lettre de motivation</label>
                            <textarea id="lettre_motivation" name="lettre_motivation" class="form-control" placeholder="Expliquez pourquoi vous êtes intéressé par cette offre et ce que vous pouvez apporter..." required></textarea>
                        </div>
                        
                        <button type="submit" name="submit_candidature" class="button button-primary button-glow">
                            <span class="iconify" data-icon="mdi:check" width="20" height="20"></span> Soumettre ma candidature
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="/home" class="footer-logo">
                            <img src="/public/images/logo.png" alt="D" width="150" height="170">
                        </a>
                        <p class="footer-tagline">Votre passerelle vers des opportunités de carrière.</p>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Pour les Chercheurs d'Emploi</h3>
                        <ul>
                            <li><a href="/offres" class="footer-link">Parcourir les Emplois</a></li>
                            <li><a href="#" class="footer-link">Ressources de Carrière</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="/public/js/mobile-menu.js"></script>
    <script src="/public/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si l'utilisateur est un étudiant pour afficher la section wishlist
            fetch("app/views/login/session.php")
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in && parseInt(data.utilisateur) === 0) {
                        // L'utilisateur est un étudiant, afficher la section wishlist et les liens
                        const wishlistLink = document.getElementById('wishlist-link');
                        const mobileWishlistLink = document.getElementById('mobile-wishlist-link');
                        
                        if (wishlistLink) {
                            wishlistLink.style.display = 'inline-flex';
                        }
                        
                        if (mobileWishlistLink) {
                            mobileWishlistLink.style.display = 'block';
                        }
                    }
                })
                .catch(error => console.error("Erreur lors de la vérification de la session:", error));
                
            // Mettre à jour l'année dans le copyright
            document.getElementById('current-year').textContent = new Date().getFullYear();
        });
    </script>
</body>
</html>