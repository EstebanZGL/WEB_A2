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
    <link rel="stylesheet" href="/public/css/candidatures.css">
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
                                                            <a href="/<?php echo "/var/www/cesi-lebonplan".$etudiant['cv_path']; ?>" class="cv-link" target="_blank">
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
        
        // Fonctions pour gérer les modals de lettre de motivation
        function showLettreMotivation(candidatureId, lettreContent) {
            document.getElementById('lettre-preview').textContent = lettreContent.replace(/\\'/g, "'");
            document.getElementById('modal-backdrop').style.display = 'block';
            document.getElementById('view-lettre-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById('modal-backdrop').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Empêcher que le clic sur le contenu du modal ferme le modal
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
        
        // Fermer les modals en cliquant sur le backdrop
        document.getElementById('modal-backdrop').addEventListener('click', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
            this.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Fonction pour mettre à jour le statut d'une candidature
        function updateStatut(selectElement) {
            const candidatureId = selectElement.dataset.candidatureId;
            const nouveauStatut = selectElement.value;
            const loadingIndicator = document.getElementById('loading-' + candidatureId);
            
            // Afficher l'indicateur de chargement
            loadingIndicator.style.visibility = 'visible';
            
            // Envoi de la requête AJAX pour mettre à jour le statut
            fetch('/gestion/etudiants/candidatures/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `candidature_id=${candidatureId}&status=${nouveauStatut}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Masquer l'indicateur de chargement
                loadingIndicator.style.visibility = 'hidden';
                
                if (data.success) {
                    // Afficher un message de succès temporaire
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success';
                    successMessage.textContent = 'Statut mis à jour avec succès';
                    successMessage.style.position = 'fixed';
                    successMessage.style.bottom = '20px';
                    successMessage.style.right = '20px';
                    successMessage.style.padding = '10px 20px';
                    successMessage.style.borderRadius = '4px';
                    successMessage.style.backgroundColor = '#2ecc71';
                    successMessage.style.color = '#ffffff';
                    successMessage.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
                    successMessage.style.zIndex = '9999';
                    
                    document.body.appendChild(successMessage);
                    
                    // Faire disparaître le message après 3 secondes
                    setTimeout(() => {
                        successMessage.style.opacity = '0';
                        successMessage.style.transition = 'opacity 0.5s ease';
                        
                        setTimeout(() => {
                            document.body.removeChild(successMessage);
                        }, 500);
                    }, 3000);
                } else {
                    // En cas d'erreur, remettre la sélection précédente
                    alert('Erreur lors de la mise à jour du statut: ' + data.message);
                }
            })
            .catch(error => {
                // Masquer l'indicateur de chargement
                loadingIndicator.style.visibility = 'hidden';
                
                // Afficher l'erreur
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la mise à jour du statut.');
            });
        }
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="/public/js/mobile-menu.js"></script>
</body>
</html>