<?php
session_start();
require_once 'config.php';

// Vérifier si l'ID de l'offre est spécifié
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: offres.php');
    exit();
}

$offre_id = intval($_GET['id']);

// Récupérer les détails de l'offre
$sql = "SELECT os.*, e.nom as entreprise_nom, e.description as entreprise_description, 
        e.email_contact, e.telephone_contact, e.adresse, e.lien_site 
        FROM offre_stage os 
        JOIN entreprise e ON os.entreprise_id = e.id 
        WHERE os.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $offre_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: offres.php');
    exit();
}

$offre = $result->fetch_assoc();

// Récupérer les compétences associées à l'offre
$sql_competences = "SELECT c.nom, c.description, c.categorie 
                    FROM offre_competence oc 
                    JOIN competence c ON oc.competence_id = c.id 
                    WHERE oc.offre_id = ?";
$stmt_comp = $conn->prepare($sql_competences);
$stmt_comp->bind_param("i", $offre_id);
$stmt_comp->execute();
$result_comp = $stmt_comp->get_result();
$competences = [];
while ($row = $result_comp->fetch_assoc()) {
    $competences[] = $row;
}

// Traitement du formulaire de candidature
$message = '';
$messageType = '';

if (isset($_POST['submit_candidature'])) {
    // Vérifier si l'utilisateur est connecté et est un étudiant
    if (!isset($_SESSION['user_id'])) {
        $message = "Vous devez être connecté pour postuler.";
        $messageType = "error";
    } else {
        // Récupérer l'ID de l'étudiant
        $sql_etudiant = "SELECT id FROM etudiant WHERE utilisateur_id = ?";
        $stmt_etudiant = $conn->prepare($sql_etudiant);
        $stmt_etudiant->bind_param("i", $_SESSION['user_id']);
        $stmt_etudiant->execute();
        $result_etudiant = $stmt_etudiant->get_result();
        
        if ($result_etudiant->num_rows == 0) {
            $message = "Vous devez être un étudiant pour postuler.";
            $messageType = "error";
        } else {
            $etudiant = $result_etudiant->fetch_assoc();
            $etudiant_id = $etudiant['id'];
            
            // Vérifier si l'étudiant a déjà postulé
            $sql_check = "SELECT id FROM candidature WHERE etudiant_id = ? AND offre_id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ii", $etudiant_id, $offre_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            if ($result_check->num_rows > 0) {
                $message = "Vous avez déjà postulé à cette offre.";
                $messageType = "error";
            } else {
                // Traitement du CV
                $cv_path = '';
                if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
                    $allowed = ['pdf', 'doc', 'docx'];
                    $filename = $_FILES['cv']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    
                    if (!in_array(strtolower($ext), $allowed)) {
                        $message = "Format de CV non valide. Formats acceptés: PDF, DOC, DOCX.";
                        $messageType = "error";
                    } else {
                        $upload_dir = 'uploads/cv/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $new_filename = uniqid('cv_') . '.' . $ext;
                        $destination = $upload_dir . $new_filename;
                        
                        if (move_uploaded_file($_FILES['cv']['tmp_name'], $destination)) {
                            $cv_path = $destination;
                        } else {
                            $message = "Erreur lors de l'upload du CV.";
                            $messageType = "error";
                        }
                    }
                } else {
                    $message = "Veuillez fournir un CV.";
                    $messageType = "error";
                }
                
                // Si tout est OK, enregistrer la candidature
                if ($messageType != "error") {
                    $lettre_motivation = $_POST['lettre_motivation'];
                    $date_candidature = date('Y-m-d H:i:s');
                    $statut = 'EN_ATTENTE';
                    
                    $sql_insert = "INSERT INTO candidature (etudiant_id, offre_id, date_candidature, cv_path, lettre_motivation, statut) 
                                   VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("iissss", $etudiant_id, $offre_id, $date_candidature, $cv_path, $lettre_motivation, $statut);
                    
                    if ($stmt_insert->execute()) {
                        $message = "Votre candidature a été soumise avec succès!";
                        $messageType = "success";
                    } else {
                        $message = "Erreur lors de la soumission de la candidature.";
                        $messageType = "error";
                    }
                }
            }
        }
    }
}

// Ajouter à la wishlist
if (isset($_POST['add_wishlist'])) {
    if (!isset($_SESSION['user_id'])) {
        $message = "Vous devez être connecté pour ajouter à vos favoris.";
        $messageType = "error";
    } else {
        // Récupérer l'ID de l'étudiant
        $sql_etudiant = "SELECT id FROM etudiant WHERE utilisateur_id = ?";
        $stmt_etudiant = $conn->prepare($sql_etudiant);
        $stmt_etudiant->bind_param("i", $_SESSION['user_id']);
        $stmt_etudiant->execute();
        $result_etudiant = $stmt_etudiant->get_result();
        
        if ($result_etudiant->num_rows == 0) {
            $message = "Vous devez être un étudiant pour ajouter à vos favoris.";
            $messageType = "error";
        } else {
            $etudiant = $result_etudiant->fetch_assoc();
            $etudiant_id = $etudiant['id'];
            
            // Vérifier si l'offre est déjà dans la wishlist
            $sql_check = "SELECT id FROM wishlist WHERE etudiant_id = ? AND offre_id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ii", $etudiant_id, $offre_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            if ($result_check->num_rows > 0) {
                $message = "Cette offre est déjà dans vos favoris.";
                $messageType = "error";
            } else {
                $date_ajout = date('Y-m-d H:i:s');
                
                $sql_insert = "INSERT INTO wishlist (etudiant_id, offre_id, date_ajout) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("iis", $etudiant_id, $offre_id, $date_ajout);
                
                if ($stmt_insert->execute()) {
                    $message = "Offre ajoutée à vos favoris!";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de l'ajout aux favoris.";
                    $messageType = "error";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($offre['titre']); ?> - LeBonPlan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #00ff88;
            --primary-dark: #00cc6a;
            --secondary-color: #ff00ff;
            --secondary-dark: #cc00cc;
            --accent-color: #00ffff;
            --background-dark: #121212;
            --background-medium: #1e1e1e;
            --background-light: #2d2d2d;
            --text-light: #ffffff;
            --text-medium: #cccccc;
            --text-dark: #999999;
            --border-radius: 8px;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            --neon-shadow: 0 0 10px var(--primary-color), 0 0 20px var(--primary-color);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: var(--background-dark);
            color: var(--text-light);
            line-height: 1.6;
        }
        
        header {
            background-color: rgba(0, 0, 0, 0.8);
            color: var(--text-light);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
        }
        
        .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        
        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        .nav-links a:hover {
            background-color: rgba(0, 255, 136, 0.2);
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .offre-details {
            background-color: var(--background-medium);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .offre-header {
            background-color: var(--background-light);
            color: var(--text-light);
            padding: 1.5rem;
            position: relative;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .offre-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            text-shadow: 0 0 5px rgba(0, 255, 136, 0.5);
        }
        
        .offre-entreprise {
            font-size: 1.2rem;
            opacity: 0.9;
            color: var(--text-medium);
        }
        
        .offre-status {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background-color: var(--secondary-color);
            color: var(--text-light);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: 0 0 10px var(--secondary-color);
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
            border-bottom: 1px solid rgba(0, 255, 136, 0.3);
            padding-bottom: 0.5rem;
            text-shadow: 0 0 5px rgba(0, 255, 136, 0.3);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .info-item i {
            color: var(--accent-color);
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            text-shadow: 0 0 5px var(--accent-color);
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
            box-shadow: 0 0 5px rgba(0, 255, 136, 0.2);
        }
        
        .entreprise-info {
            background-color: var(--background-light);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-top: 1rem;
            border: 1px solid rgba(0, 255, 136, 0.2);
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--background-dark);
            box-shadow: 0 0 10px var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 0 15px var(--primary-color);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            box-shadow: 0 0 10px var(--secondary-color);
        }
        
        .btn-outline:hover {
            background-color: rgba(255, 0, 255, 0.1);
            box-shadow: 0 0 15px var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .candidature-form {
            background-color: var(--background-medium);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-top: 2rem;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .form-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 0 5px rgba(0, 255, 136, 0.5);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-medium);
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: var(--border-radius);
            font-size: 1rem;
            background-color: var(--background-light);
            color: var(--text-light);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px var(--primary-color);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
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
        
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: var(--text-medium);
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(0, 255, 136, 0.2);
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .offre-status {
                position: static;
                display: inline-block;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">LeBonPlan</div>
            <nav class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="offres.php">Offres</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Tableau de bord</a>
                    <a href="logout.php">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php">Connexion</a>
                    <a href="register.php">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType == 'success' ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="offre-details">
            <div class="offre-header">
                <h1 class="offre-title"><?php echo htmlspecialchars($offre['titre']); ?></h1>
                <div class="offre-entreprise"><?php echo htmlspecialchars($offre['entreprise_nom']); ?></div>
                <div class="offre-status"><?php echo htmlspecialchars($offre['statut']); ?></div>
            </div>
            
            <div class="offre-content">
                <section class="offre-section">
                    <h3>Description du stage</h3>
                    <p><?php echo nl2br(htmlspecialchars($offre['description'])); ?></p>
                </section>
                
                <section class="offre-section">
                    <h3>Informations clés</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Période:</strong> 
                                Du <?php echo date('d/m/Y', strtotime($offre['date_debut'])); ?> 
                                au <?php echo date('d/m/Y', strtotime($offre['date_fin'])); ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div><strong>Durée:</strong> <?php echo $offre['duree_stage']; ?> mois</div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-euro-sign"></i>
                            <div><strong>Rémunération:</strong> <?php echo number_format($offre['remuneration'], 2, ',', ' '); ?> €</div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-check"></i>
                            <div><strong>Publication:</strong> <?php echo date('d/m/Y', strtotime($offre['date_publication'])); ?></div>
                        </div>
                    </div>
                </section>
                
                <section class="offre-section">
                    <h3>Compétences requises</h3>
                    <div class="competences-list">
                        <?php foreach ($competences as $competence): ?>
                            <div class="competence-badge" title="<?php echo htmlspecialchars($competence['description']); ?>">
                                <?php echo htmlspecialchars($competence['nom']); ?> 
                                <small>(<?php echo htmlspecialchars($competence['categorie']); ?>)</small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <section class="offre-section">
                    <h3>À propos de l'entreprise</h3>
                    <p><?php echo nl2br(htmlspecialchars($offre['entreprise_description'])); ?></p>
                    
                    <div class="entreprise-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <div><strong>Email:</strong> <?php echo htmlspecialchars($offre['email_contact']); ?></div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <div><strong>Téléphone:</strong> <?php echo htmlspecialchars($offre['telephone_contact']); ?></div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div><strong>Adresse:</strong> <?php echo htmlspecialchars($offre['adresse']); ?></div>
                            </div>
                            <?php if (!empty($offre['lien_site'])): ?>
                            <div class="info-item">
                                <i class="fas fa-globe"></i>
                                <div><strong>Site web:</strong> <a href="<?php echo htmlspecialchars($offre['lien_site']); ?>" target="_blank" style="color: var(--accent-color); text-decoration: none;"><?php echo htmlspecialchars($offre['lien_site']); ?></a></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
                <div class="action-buttons">
                    <form method="post">
                        <button type="submit" name="add_wishlist" class="btn btn-outline">
                            <i class="far fa-heart"></i> Ajouter aux favoris
                        </button>
                    </form>
                    <a href="#candidature-form" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Postuler maintenant
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
                
                <button type="submit" name="submit_candidature" class="btn btn-primary">
                    <i class="fas fa-check"></i> Soumettre ma candidature
                </button>
            </form>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> LeBonPlan - Tous droits réservés</p>
    </footer>
</body>
</html>