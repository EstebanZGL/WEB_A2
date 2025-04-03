<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/models/OffreModel.php';
require_once __DIR__ . '/../../app/models/CandidatureModel.php';
require_once __DIR__ . '/../../app/models/CompetenceModel.php';

// Initialiser les variables
$message = '';
$messageType = '';
$offre = [];
$competences = [];
$alreadyApplied = false;

// Récupérer l'ID de l'offre depuis l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $offre_id = $_GET['id'];
    
    // Récupérer les détails de l'offre
    $offreModel = new OffreModel();
    $offre = $offreModel->getOffreById($offre_id);
    
    // Récupérer les compétences associées à cette offre
    $competenceModel = new CompetenceModel();
    $competences = $competenceModel->getCompetencesForOffre($offre_id);
    
    // Vérifier si l'utilisateur est connecté et a déjà postulé
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 0) {
        $etudiantId = $_SESSION['user_id'];
        $candidatureModel = new CandidatureModel();
        $alreadyApplied = $candidatureModel->checkIfAlreadyApplied($etudiantId, $offre_id);
    }
    
    // Traitement du formulaire de candidature
    if (isset($_POST['submit_candidature']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 0) {
        $etudiantId = $_SESSION['user_id'];
        $lettreMotivation = $_POST['lettre_motivation'] ?? '';
        
        // Traitement du CV uploadé
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $fileType = $_FILES['cv']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                // Créer le dossier uploads s'il n'existe pas
                $uploadDir = __DIR__ . '/../../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Générer un nom de fichier unique
                $fileName = uniqid() . '_' . basename($_FILES['cv']['name']);
                $filePath = $uploadDir . $fileName;
                
                // Déplacer le fichier uploadé
                if (move_uploaded_file($_FILES['cv']['tmp_name'], $filePath)) {
                    $cvPath = '/uploads/' . $fileName;
                    
                    // Enregistrer la candidature
                    $candidatureModel = new CandidatureModel();
                    $result = $candidatureModel->createCandidature($etudiantId, $offre_id, $cvPath, $lettreMotivation);
                    
                    if ($result) {
                        $message = 'Votre candidature a été soumise avec succès.';
                        $messageType = 'success';
                        $alreadyApplied = true;
                    } else {
                        $message = 'Une erreur est survenue lors de la soumission de votre candidature.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Une erreur est survenue lors du téléchargement de votre CV.';
                    $messageType = 'error';
                }
            } else {
                $message = 'Format de fichier non autorisé. Veuillez télécharger un fichier PDF, DOC ou DOCX.';
                $messageType = 'error';
            }
        } else {
            $message = 'Veuillez télécharger votre CV.';
            $messageType = 'error';
        }
    }
} else {
    // Rediriger vers la liste des offres si aucun ID n'est spécifié
    header('Location: ' . $basePath . '/offres');
    exit;
}

// Fonction utilitaire pour récupérer une valeur d'un tableau avec une clé par défaut
function getValue($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
}

// Si l'offre n'existe pas, rediriger vers la liste des offres
if (empty($offre)) {
    header('Location: ' . $basePath . '/offres');
    exit;
}

// Titre de la page
$pageTitle = htmlspecialchars(getValue($offre, 'titre')) . ' - LeBonPlan';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/public/css/styles.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/public/css/offre-details.css">
    <link rel="icon" href="<?php echo $basePath; ?>/public/images/favicon.ico" type="image/x-icon">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="header">
            <div class="container header-container">
                <div class="logo-container">
                    <a href="<?php echo $basePath; ?>/home" class="logo">
                        <img src="<?php echo $basePath; ?>/public/images/logo.png" alt="LeBonPlan Logo" width="150" height="170">
                    </a>
                </div>
                <div class="nav-container">
                    <nav class="main-nav">
                        <ul class="nav-list">
                            <li class="nav-item"><a href="<?php echo $basePath; ?>/home" class="nav-link">Accueil</a></li>
                            <li class="nav-item"><a href="<?php echo $basePath; ?>/offres" class="nav-link">Offres</a></li>
                            <li class="nav-item"><a href="<?php echo $basePath; ?>/entreprises" class="nav-link">Entreprises</a></li>
                            <li class="nav-item"><a href="<?php echo $basePath; ?>/contact" class="nav-link">Contact</a></li>
                        </ul>
                    </nav>
                    <div class="auth-buttons">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                            <a href="<?php echo $basePath; ?>/dashboard" class="button button-secondary">
                                <span class="iconify" data-icon="mdi:account" width="20" height="20"></span>
                                Mon compte
                            </a>
                            <a href="<?php echo $basePath; ?>/wishlist" id="wishlist-link" class="button button-icon" style="display: none;">
                                <span class="iconify" data-icon="mdi:heart" width="24" height="24"></span>
                            </a>
                            <a href="<?php echo $basePath; ?>/logout" class="button button-text">Déconnexion</a>
                        <?php else: ?>
                            <a href="<?php echo $basePath; ?>/login" class="button button-secondary">Connexion</a>
                            <a href="<?php echo $basePath; ?>/register" class="button button-primary">Inscription</a>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="mobile-menu-toggle" aria-label="Menu">
                    <span class="iconify" data-icon="mdi:menu" width="24" height="24"></span>
                </button>
                <div class="mobile-menu">
                    <div class="mobile-menu-header">
                        <a href="<?php echo $basePath; ?>/home" class="logo">
                            <img src="<?php echo $basePath; ?>/public/images/logo.png" alt="LeBonPlan Logo" width="100" height="120">
                        </a>
                        <button class="mobile-menu-close" aria-label="Fermer le menu">
                            <span class="iconify" data-icon="mdi:close" width="24" height="24"></span>
                        </button>
                    </div>
                    <nav class="mobile-nav">
                        <ul class="mobile-nav-list">
                            <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/home" class="mobile-nav-link">Accueil</a></li>
                            <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/offres" class="mobile-nav-link">Offres</a></li>
                            <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/entreprises" class="mobile-nav-link">Entreprises</a></li>
                            <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/contact" class="mobile-nav-link">Contact</a></li>
                            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/dashboard" class="mobile-nav-link">Mon compte</a></li>
                                <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/wishlist" id="mobile-wishlist-link" class="mobile-nav-link" style="display: none;">Mes favoris</a></li>
                                <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/logout" class="mobile-nav-link">Déconnexion</a></li>
                            <?php else: ?>
                                <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/login" class="mobile-nav-link">Connexion</a></li>
                                <li class="mobile-nav-item"><a href="<?php echo $basePath; ?>/register" class="mobile-nav-link">Inscription</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
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
                        <h1 class="offre-title"><?php echo htmlspecialchars(getValue($offre, 'titre')); ?></h1>
                        <div class="offre-entreprise"><?php echo htmlspecialchars(getValue($offre, 'entreprise')); ?></div>
                    </div>
                    
                    <div class="offre-content">
                        <section class="offre-section">
                            <h3>Description du poste</h3>
                            <p><?php echo nl2br(htmlspecialchars(getValue($offre, 'description'))); ?></p>
                        </section>
                        
                        <section class="offre-section">
                            <h3>Informations clés</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:currency-eur" width="24" height="24"></span>
                                    <div><strong>Rémunération:</strong> <?php echo number_format((float)getValue($offre, 'remuneration'), 0, ',', ' '); ?> €/an</div>
                                </div>
                                
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:calendar" width="24" height="24"></span>
                                    <div><strong>Publication:</strong> <?php echo date('d/m/Y', strtotime(getValue($offre, 'date_publication'))); ?></div>
                                </div>
                                
                                <?php if (!empty(getValue($offre, 'ville'))): ?>
                                <div class="info-item">
                                    <span class="iconify" data-icon="mdi:map-marker" width="24" height="24"></span>
                                    <div><strong>Lieu:</strong> <?php echo htmlspecialchars(getValue($offre, 'ville')); ?></div>
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
                            <a href="<?php echo $basePath; ?>/dashboard" class="button button-secondary">
                                <span class="iconify" data-icon="mdi:account" width="20" height="20"></span> Accéder au tableau de bord
                            </a>
                            <a href="#candidature-form" class="button button-primary button-glow" id="btn-postuler">
                                <span class="iconify" data-icon="mdi:send" width="20" height="20"></span> Postuler maintenant
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="candidature-form" id="candidature-form">
                    <h2 class="form-title">Déposer votre candidature</h2>
                    
                    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
                        <div class="alert alert-error">
                            Vous devez être <a href="<?php echo $basePath; ?>/login" style="color: #ff5555; text-decoration: underline;">connecté</a> en tant qu'étudiant pour postuler à cette offre.
                        </div>
                    <?php elseif (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] != 0): ?>
                        <div class="alert alert-error">
                            Seuls les étudiants peuvent postuler à cette offre.
                        </div>
                    <?php elseif ($alreadyApplied): ?>
                        <div class="alert alert-success">
                            Vous avez déjà postulé à cette offre. Vous serez notifié de la suite donnée à votre candidature.
                        </div>
                    <?php else: ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="cv">Votre CV (PDF, DOC, DOCX)</label>
                                <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="lettre_motivation">Lettre de motivation</label>
                                <textarea id="lettre_motivation" name="lettre_motivation" class="form-control" rows="8" placeholder="Expliquez pourquoi vous êtes intéressé par cette offre et ce que vous pouvez apporter..." required></textarea>
                            </div>
                            
                            <button type="submit" name="submit_candidature" class="button button-primary button-glow">
                                <span class="iconify" data-icon="mdi:check" width="20" height="20"></span> Soumettre ma candidature
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="<?php echo $basePath; ?>/home" class="footer-logo">
                            <img src="<?php echo $basePath; ?>/public/images/logo.png" alt="D" width="150" height="170">
                        </a>
                        <p class="footer-tagline">Votre passerelle vers des opportunités de carrière.</p>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Pour les Chercheurs d'Emploi</h3>
                        <ul>
                            <li><a href="<?php echo $basePath; ?>/offres" class="footer-link">Parcourir les Emplois</a></li>
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
    <script src="<?php echo $basePath; ?>/public/js/mobile-menu.js"></script>
    <script src="<?php echo $basePath; ?>/public/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si l'utilisateur est un étudiant pour afficher la section wishlist
            fetch("<?php echo $basePath; ?>/app/views/login/session.php")
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
            
            // Faire défiler jusqu'au formulaire de candidature si le bouton est cliqué
            const btnPostuler = document.getElementById('btn-postuler');
            if (btnPostuler) {
                btnPostuler.addEventListener('click', function(e) {
                    const candidatureForm = document.getElementById('candidature-form');
                    if (candidatureForm) {
                        // Empêcher le comportement par défaut du lien
                        e.preventDefault();
                        // Faire défiler jusqu'au formulaire
                        candidatureForm.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
</body>
</html>