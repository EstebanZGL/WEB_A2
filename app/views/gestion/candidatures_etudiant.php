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
    <link rel="stylesheet" href="/public/css/candidature.css">
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
                <a href="/dashboard" class="mobile-nav-link" id="mobile-page-dashboard" style="display:none;">Tableau de bord</a>
                <a href="/gestion" class="mobile-nav-link">Gestion</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="/login" id="mobile-login-Bouton" class="button button-primary button-glow" style="display:none;">Connexion</a>
                    <a href="/logout" id="mobile-logout-Bouton" class="button button-primary button-glow">Déconnexion</a>
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
                    <a href="/dashboard" class="nav-link" id="page-dashboard" style="display:none;">Tableau de bord</a>
                    <a href="/gestion" class="nav-link">Gestion</a>
                </nav>

                <div id="user-status">
                    <a href="/login" id="login-Bouton" class="button button-outline button-glow" style="display:none;">Connexion</a>
                    <a href="/logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
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
                                    case 5:
                                        $successMessage = 'Lettre de motivation enregistrée avec succès.';
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
                                    case 6:
                                        $errorMessage = 'Erreur lors de l\'enregistrement de la lettre de motivation.';
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
                                        <th>Documents</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($candidatures)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune candidature trouvée</td>
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
                                                    <!-- CV -->
                                                    <div>
                                                        <?php if (isset($candidature['cv_path']) && !empty($candidature['cv_path'])): ?>
                                                            <a href="/<?php echo $candidature['cv_path']; ?>" class="cv-link" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                                </svg>
                                                                CV spécifique
                                                            </a>
                                                        <?php elseif (isset($etudiant['cv_path']) && !empty($etudiant['cv_path'])): ?>
                                                            <a href="/<?php echo $etudiant['cv_path']; ?>" class="cv-link" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                                </svg>
                                                                CV général
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="cv-unavailable">CV non disponible</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <!-- Lettre de motivation -->
                                                    <div style="margin-top: 10px; border-top: 1px dashed #2c3e50; padding-top: 10px;">
                                                        <?php if (isset($candidature['lettre_motivation']) && !empty($candidature['lettre_motivation'])): ?>
                                                            <div class="lettre-motivation-actions">
                                                                <button type="button" class="lettre-motivation-btn" onclick="showLettreMotivation(<?php echo $candidature['id']; ?>, '<?php echo htmlspecialchars(addslashes($candidature['lettre_motivation'])); ?>')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                        <circle cx="12" cy="12" r="3"></circle>
                                                                    </svg>
                                                                    Voir la lettre
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="cv-unavailable">Lettre non disponible</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="status-container">
                                                        <select class="status-select" data-candidature-id="<?php echo $candidature['id']; ?>" onchange="updateStatut(this)">
                                                            <option value="EN_ATTENTE" <?php echo ($candidature['statut'] == 'EN_ATTENTE') ? 'selected' : ''; ?>>En attente</option>
                                                            <option value="ACCEPTEE" <?php echo ($candidature['statut'] == 'ACCEPTEE') ? 'selected' : ''; ?>>Acceptée</option>
                                                            <option value="REFUSEE" <?php echo ($candidature['statut'] == 'REFUSEE') ? 'selected' : ''; ?>>Refusée</option>
                                                            <option value="ENTRETIEN" <?php echo ($candidature['statut'] == 'ENTRETIEN') ? 'selected' : ''; ?>>Entretien</option>
                                                        </select>
                                                        <span class="loading-indicator" id="loading-<?php echo $candidature['id']; ?>"></span>
                                                    </div>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($wishlist)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune offre dans la wishlist</td>
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
    
    <!-- Modals pour la lettre de motivation -->
    <div id="modal-backdrop" class="modal-backdrop"></div>
    
    <!-- Modal pour afficher la lettre -->
    <div id="view-lettre-modal" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Lettre de motivation</h3>
            <button type="button" class="modal-close" onclick="closeModal('view-lettre-modal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="lettre-preview" class="lettre-motivation-preview"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="button button-outline" onclick="closeModal('view-lettre-modal')">Fermer</button>
        </div>
    </div>

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="/public/js/mobile-menu.js"></script>
    <script src="/public/js/candidatures.js"></script>
</body>
</html>