<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Candidatures de l'étudiant</title>
    <meta name="description" content="Gestion des candidatures de l'étudiant sur la plateforme LeBonPlan." />
    <link rel="stylesheet" href="/public/css/style.css" />
    <link rel="stylesheet" href="/public/css/responsive-complete.css">
    <link rel="stylesheet" href="/public/css/gestion.css">
    <style>
        /* Style pour les liens de CV */
        .cv-link {
            display: inline-flex;
            align-items: center;
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }
        
        .cv-link:hover {
            color: #3498db;
        }
        
        .cv-link svg {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }
        
        .cv-unavailable {
            color: #95a5a6;
            font-style: italic;
            font-size: 0.9rem;
        }
        
        /* Style pour l'upload de CV */
        .cv-upload-form {
            margin-top: 10px;
        }
        
        .cv-upload-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s;
        }
        
        .cv-upload-btn:hover {
            background-color: #e9e9e9;
        }
        
        .file-input {
            display: none;
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
                <a href="/offres" class="mobile-nav-link">Emplois</a>
                <a href="/gestion" class="mobile-nav-link active">Gestion</a>
                <a href="/admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="/logout" class="button button-primary button-glow">Déconnexion</a>
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
                
                <a href="/home">
                    <img src="/public/images/logo.png" alt="D" width="150" height="170">
                </a>
                
                <nav class="navbar-nav">
                    <a href="/home" class="nav-link">Accueil</a>
                    <a href="/offres" class="nav-link">Emplois</a>
                    <a href="/gestion" class="nav-link active" id="page-gestion">Gestion</a>
                    <a href="/admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                </nav>
                <div id="user-status">
                    <a href="/login" id="login-Bouton" class="button button-outline button-glow" style="display:none;">Connexion</a>
                    <a href="/logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
        </header>
        
        <main>
            <section class="section">
                <div class="container">
                    <div class="breadcrumbs">
                        <a href="/gestion?section=etudiants">← Retour à la liste des étudiants</a>
                    </div>
                    
                    <h1 class="section-title">Candidatures de <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h1>
                    
                    <!-- Informations sur l'étudiant -->
                    <div class="form-container">
                        <h2>Informations de l'étudiant</h2>
                        <div class="form-row">
                            <div class="form-group half">
                                <label>Nom</label>
                                <p><?php echo htmlspecialchars($etudiant['nom']); ?></p>
                            </div>
                            <div class="form-group half">
                                <label>Prénom</label>
                                <p><?php echo htmlspecialchars($etudiant['prenom']); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <p><?php echo htmlspecialchars($etudiant['email']); ?></p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label>Promotion</label>
                                <p><?php echo htmlspecialchars($etudiant['promotion']); ?></p>
                            </div>
                            <div class="form-group half">
                                <label>Formation</label>
                                <p><?php echo htmlspecialchars($etudiant['formation']); ?></p>
                            </div>
                        </div>
                        
                        <!-- CV général de l'étudiant -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>CV général</label>
                                <div>
                                    <?php if (isset($etudiant['cv_path']) && !empty($etudiant['cv_path'])): ?>
                                        <a href="/uploads/cv/<?php echo $etudiant['cv_path']; ?>" class="cv-link" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            Voir le CV
                                        </a>
                                    <?php else: ?>
                                        <span class="cv-unavailable">Pas de CV disponible</span>
                                    <?php endif; ?>
                                    
                                    <!-- Formulaire d'upload de CV général -->
                                    <form action="/gestion/etudiants/upload-cv" method="post" enctype="multipart/form-data" class="cv-upload-form">
                                        <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                        <label for="cv-general-upload" class="cv-upload-btn">
                                            <?php if (isset($etudiant['cv_path']) && !empty($etudiant['cv_path'])): ?>
                                                Mettre à jour le CV
                                            <?php else: ?>
                                                Ajouter un CV
                                            <?php endif; ?>
                                        </label>
                                        <input type="file" name="cv_file" id="cv-general-upload" class="file-input" accept="application/pdf" onchange="this.form.submit()">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages d'alerte -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                $successMessage = '';
                                switch ($_GET['success']) {
                                    case 1:
                                        $successMessage = 'Candidature ajoutée avec succès.';
                                        break;
                                    case 2:
                                        $successMessage = 'Statut de la candidature mis à jour avec succès.';
                                        break;
                                    case 3:
                                        $successMessage = 'Candidature supprimée avec succès.';
                                        break;
                                    case 4:
                                        $successMessage = 'CV téléchargé avec succès.';
                                        break;
                                }
                                echo $successMessage;
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                                $errorMessage = '';
                                switch ($_GET['error']) {
                                    case 1:
                                        $errorMessage = 'Erreur lors de l\'ajout de la candidature.';
                                        break;
                                    case 2:
                                        $errorMessage = 'Erreur lors de la mise à jour du statut de la candidature.';
                                        break;
                                    case 3:
                                        $errorMessage = 'Candidature non trouvée.';
                                        break;
                                    case 4:
                                        $errorMessage = 'Erreur lors de la suppression de la candidature.';
                                        break;
                                    case 5:
                                        $errorMessage = 'Erreur lors du téléchargement du CV. Veuillez vérifier que le fichier est bien au format PDF et ne dépasse pas 5 Mo.';
                                        break;
                                }
                                echo $errorMessage;
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Onglets pour naviguer entre candidatures et wishlist -->
                    <div class="tabs">
                        <a href="#candidatures" class="tab active" onclick="showTab('candidatures')">Candidatures</a>
                        <a href="#wishlist" class="tab" onclick="showTab('wishlist')">Wishlist</a>
                    </div>
                    
                    <!-- Section des candidatures -->
                    <div id="candidatures" class="tab-content">
                        <h2>Candidatures de l'étudiant</h2>
                        
                        <!-- Formulaire d'ajout de candidature -->
                        <div class="form-container">
                            <h3>Ajouter une candidature</h3>
                            <form action="/gestion/etudiants/candidatures/add" method="post" class="form" enctype="multipart/form-data">
                                <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="offre_id">Offre</label>
                                        <select name="offre_id" id="offre_id" required>
                                            <option value="">Sélectionnez une offre</option>
                                            <?php
                                            // Charger les offres disponibles
                                            require_once 'app/models/OffreModel.php';
                                            $offreModel = new OffreModel();
                                            $offres = $offreModel->getOffresForSelect();
                                            
                                            foreach ($offres as $offre) {
                                                echo '<option value="' . $offre['id'] . '">' . htmlspecialchars($offre['titre']) . ' - ' . htmlspecialchars($offre['nom_entreprise']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="statut">Statut</label>
                                        <select name="statut" id="statut" required>
                                            <option value="En attente">En attente</option>
                                            <option value="Entretien">Entretien</option>
                                            <option value="Acceptée">Acceptée</option>
                                            <option value="Refusée">Refusée</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="cv_file">CV spécifique (optionnel)</label>
                                        <input type="file" name="cv_file" id="cv_file" accept="application/pdf">
                                        <small>Laissez vide pour utiliser le CV général de l'étudiant</small>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="button button-primary">Ajouter la candidature</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Liste des candidatures -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Type</th>
                                        <th>Lieu</th>
                                        <th>Date de candidature</th>
                                        <th>CV</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($candidatures)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucune candidature trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($candidatures as $candidature): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($candidature['offre_titre']); ?></td>
                                                <td><?php echo htmlspecialchars($candidature['entreprise_nom']); ?></td>
                                                <td><?php echo isset($candidature['offre_type']) ? htmlspecialchars($candidature['offre_type']) : 'Non spécifié'; ?></td>
                                                <td><?php echo isset($candidature['offre_lieu']) ? htmlspecialchars($candidature['offre_lieu']) : 'Non spécifié'; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($candidature['date_candidature'])); ?></td>
                                                <td>
                                                    <?php if (isset($candidature['cv_path']) && !empty($candidature['cv_path'])): ?>
                                                        <a href="/uploads/cv/<?php echo $candidature['cv_path']; ?>" class="cv-link" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                                <polyline points="10 9 9 9 8 9"></polyline>
                                                            </svg>
                                                            Voir
                                                        </a>
                                                        <form action="/gestion/etudiants/candidatures/upload-cv" method="post" enctype="multipart/form-data" class="cv-upload-form">
                                                            <input type="hidden" name="candidature_id" value="<?php echo $candidature['id']; ?>">
                                                            <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                                            <label for="cv-upload-<?php echo $candidature['id']; ?>" class="cv-upload-btn">Mettre à jour</label>
                                                            <input type="file" name="cv_file" id="cv-upload-<?php echo $candidature['id']; ?>" class="file-input" accept="application/pdf" onchange="this.form.submit()">
                                                        </form>
                                                    <?php elseif (isset($etudiant['cv_path']) && !empty($etudiant['cv_path'])): ?>
                                                        <a href="/uploads/cv/<?php echo $etudiant['cv_path']; ?>" class="cv-link" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                                <polyline points="10 9 9 9 8 9"></polyline>
                                                            </svg>
                                                            CV général
                                                        </a>
                                                        <form action="/gestion/etudiants/candidatures/upload-cv" method="post" enctype="multipart/form-data" class="cv-upload-form">
                                                            <input type="hidden" name="candidature_id" value="<?php echo $candidature['id']; ?>">
                                                            <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                                            <label for="cv-upload-<?php echo $candidature['id']; ?>" class="cv-upload-btn">Ajouter spécifique</label>
                                                            <input type="file" name="cv_file" id="cv-upload-<?php echo $candidature['id']; ?>" class="file-input" accept="application/pdf" onchange="this.form.submit()">
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="cv-unavailable">Non disponible</span>
                                                        <form action="/gestion/etudiants/candidatures/upload-cv" method="post" enctype="multipart/form-data" class="cv-upload-form">
                                                            <input type="hidden" name="candidature_id" value="<?php echo $candidature['id']; ?>">
                                                            <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                                            <label for="cv-upload-<?php echo $candidature['id']; ?>" class="cv-upload-btn">Ajouter</label>
                                                            <input type="file" name="cv_file" id="cv-upload-<?php echo $candidature['id']; ?>" class="file-input" accept="application/pdf" onchange="this.form.submit()">
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form action="/gestion/etudiants/candidatures/update-status" method="post" class="status-form">
                                                        <input type="hidden" name="candidature_id" value="<?php echo $candidature['id']; ?>">
                                                        <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                                        <select name="statut" onchange="this.form.submit()" class="status-select">
                                                            <option value="En attente" <?php echo $candidature['statut'] === 'En attente' ? 'selected' : ''; ?>>En attente</option>
                                                            <option value="Entretien" <?php echo $candidature['statut'] === 'Entretien' ? 'selected' : ''; ?>>Entretien</option>
                                                            <option value="Acceptée" <?php echo $candidature['statut'] === 'Acceptée' ? 'selected' : ''; ?>>Acceptée</option>
                                                            <option value="Refusée" <?php echo $candidature['statut'] === 'Refusée' ? 'selected' : ''; ?>>Refusée</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="actions">
                                                    <a href="/gestion/etudiants/candidatures/delete?candidature_id=<?php echo $candidature['id']; ?>&etudiant_id=<?php echo $etudiant['id']; ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?')">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Section de la wishlist -->
                    <div id="wishlist" class="tab-content" style="display: none;">
                        <h2>Wishlist de l'étudiant</h2>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Offre</th>
                                        <th>Entreprise</th>
                                        <th>Type</th>
                                        <th>Lieu</th>
                                        <th>Date d'ajout</th>
                                        <th>Statut de l'offre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($wishlist)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune offre dans la wishlist</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($wishlist as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['offre_titre']); ?></td>
                                                <td><?php echo htmlspecialchars($item['entreprise_nom']); ?></td>
                                                <td><?php echo isset($item['offre_type']) ? htmlspecialchars($item['offre_type']) : 'Non spécifié'; ?></td>
                                                <td><?php echo isset($item['offre_lieu']) ? htmlspecialchars($item['offre_lieu']) : 'Non spécifié'; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($item['date_ajout'])); ?></td>
                                                <td><?php echo htmlspecialchars($item['offre_statut']); ?></td>
                                                <td class="actions">
                                                    <form action="/gestion/etudiants/candidatures/add" method="post" style="display: inline;">
                                                        <input type="hidden" name="etudiant_id" value="<?php echo $etudiant['id']; ?>">
                                                        <input type="hidden" name="offre_id" value="<?php echo $item['offre_id']; ?>">
                                                        <button type="submit" class="btn-modifier">Convertir en candidature</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
        
        // Fonction pour changer d'onglet
        function showTab(tabId) {
            // Masquer tous les contenus d'onglets
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });
            
            // Afficher le contenu de l'onglet sélectionné
            document.getElementById(tabId).style.display = 'block';
            
            // Mettre à jour les classes actives des onglets
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Ajouter la classe active à l'onglet cliqué
            document.querySelector(`.tab[href="#${tabId}"]`).classList.add('active');
            
            // Empêcher le comportement par défaut du lien
            return false;
        }
        
        // Style pour le sélecteur de statut
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('.status-select');
            
            statusSelects.forEach(select => {
                // Appliquer une couleur en fonction du statut sélectionné
                const updateSelectColor = () => {
                    const value = select.value;
                    select.className = 'status-select';
                    
                    if (value === 'En attente') {
                        select.classList.add('status-waiting');
                    } else if (value === 'Entretien') {
                        select.classList.add('status-interview');
                    } else if (value === 'Acceptée') {
                        select.classList.add('status-accepted');
                    } else if (value === 'Refusée') {
                        select.classList.add('status-rejected');
                    }
                };
                
                // Initialiser la couleur
                updateSelectColor();
                
                // Mettre à jour la couleur lors du changement
                select.addEventListener('change', updateSelectColor);
            });
        });
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="/public/js/mobile-menu.js"></script>
</body>
</html>