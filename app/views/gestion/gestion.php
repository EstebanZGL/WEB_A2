<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeBonPlan | Gestion</title>
    <meta name="description" content="Interface de gestion des offres, entreprises et étudiants sur la plateforme LeBonPlan." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <link rel="stylesheet" href="public/css/gestion.css">
    <!-- Styles correctifs pour éliminer la colonne vide uniquement dans le tableau des étudiants -->
    <style>
        /* Applique table-layout: fixed uniquement au tableau des étudiants */
        .gestion-table-etudiants {
            table-layout: fixed;
            width: 100%;
        }
        
        /* Règles spécifiques pour le tableau des étudiants */
        .gestion-table-etudiants th:nth-child(1),
        .gestion-table-etudiants td:nth-child(1) {
            width: 16%;
        }
        
        .gestion-table-etudiants th:nth-child(2),
        .gestion-table-etudiants td:nth-child(2) {
            width: 16%;
        }
        
        .gestion-table-etudiants th:nth-child(3),
        .gestion-table-etudiants td:nth-child(3) {
            width: 22%;
        }
        
        .gestion-table-etudiants th:nth-child(4),
        .gestion-table-etudiants td:nth-child(4) {
            width: 13%;
        }
        
        .gestion-table-etudiants th:nth-child(5),
        .gestion-table-etudiants td:nth-child(5) {
            width: 18%;
        }
        
        .gestion-table-etudiants th:nth-child(6),
        .gestion-table-etudiants td:nth-child(6) {
            width: 15%;
            text-align: center;
        }
        
        /* Suppression de toute colonne potentielle après la 6e colonne */
        .gestion-table-etudiants th:nth-child(7),
        .gestion-table-etudiants td:nth-child(7) {
            display: none;
        }
        
        /* Correction spécifique pour les boutons dans la table des étudiants */
        .gestion-table-etudiants .actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        
        /* Style amélioré pour les boutons modifier et supprimer */
        .gestion-table-etudiants .btn-modifier,
        .gestion-table-etudiants .btn-supprimer {
            display: inline-block;
            text-align: center;
            min-width: 70px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="mobile-menu-overlay"></div>
        
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link">Emplois</a>
                <a href="gestion" class="mobile-nav-link active">Gestion</a>
                <a href="admin" class="mobile-nav-link" id="mobile-page-admin" style="display:none;">Administrateur</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="logout" class="button button-primary button-glow">Déconnexion</a>
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
                
                <a href="home">
                    <img src="public/images/logo.png" alt="D" width="150" height="170">
                </a>
                
                <nav class="navbar-nav">
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="gestion" class="nav-link active" id="page-gestion">Gestion</a>
                    <a href="admin" class="nav-link" id="page-admin" style="display:none;">Administrateur</a>
                    <span id="welcome-message" style="display:none;"></span>
                </nav>
                <div id="user-status">
                    <a href="login" id="login-Bouton" class="button button-outline button-glow" style="display:none;">Connexion</a>
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow">Déconnexion</a>
                </div>
            </div>
        </header>
        
        <main>
            <section class="section">
                <div class="container">
                    <h1 class="section-title">Gestion de la plateforme</h1>
                    
                    <!-- Onglets de navigation -->
                    <div class="tabs">
                        <a href="gestion?section=offres<?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" class="tab <?php echo $section === 'offres' ? 'active' : ''; ?>">Offres</a>
                        <a href="gestion?section=entreprises<?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" class="tab <?php echo $section === 'entreprises' ? 'active' : ''; ?>">Entreprises</a>
                        <a href="gestion?section=etudiants<?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" class="tab <?php echo $section === 'etudiants' ? 'active' : ''; ?>">Étudiants</a>
                        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
                        <a href="gestion?section=pilotes<?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" class="tab <?php echo $section === 'pilotes' ? 'active' : ''; ?>">Pilotes</a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Messages d'alerte -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                                $successMessage = '';
                                switch ($_GET['success']) {
                                    case 1:
                                        $successMessage = 'Élément ajouté avec succès.';
                                        break;
                                    case 2:
                                        $successMessage = 'Élément modifié avec succès.';
                                        break;
                                    case 3:
                                        $successMessage = 'Élément supprimé avec succès.';
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
                                        $errorMessage = 'Erreur lors de l\'ajout de l\'élément.';
                                        break;
                                    case 2:
                                        $errorMessage = 'Erreur lors de la modification de l\'élément.';
                                        break;
                                    case 3:
                                        $errorMessage = 'Élément non trouvé.';
                                        break;
                                    case 4:
                                        $errorMessage = 'Erreur lors de la suppression de l\'élément.';
                                        break;
                                    case 5:
                                        $errorMessage = 'Impossible de supprimer cet étudiant car il a des candidatures associées.';
                                        break;
                                }
                                echo $errorMessage;
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Boutons d'action -->
                    <div class="action-buttons">
                        <div>
                            <!-- Correction ici: ajouter un slash avant le nom de section -->
                            <a href="gestion/<?php echo $section; ?>/add" class="button button-primary">Ajouter</a>
                            <?php if ($section !== 'pilotes'): ?>
                                <a href="gestion/<?php echo $section; ?>/stats" class="button button-secondary">Statistiques</a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Formulaire de recherche -->
                        <div>
                            <form action="gestion" method="get" class="search-form-inline">
                                <input type="hidden" name="section" value="<?php echo $section; ?>">
                                <input type="text" name="search" placeholder="Rechercher..." class="search-input-small" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit" class="button button-small">Rechercher</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Information de pagination -->
                    <div class="pagination-info">
                        <?php
                        // S'assurer que les variables de pagination sont définies
                        $currentPage = isset($currentPage) ? $currentPage : 1;
                        $itemsPerPage = isset($itemsPerPage) ? $itemsPerPage : 10;
                        $totalItems = isset($totalItems) ? $totalItems : 0;
                        
                        echo "Affichage de " . min(($currentPage - 1) * $itemsPerPage + 1, $totalItems) . " à " . 
                             min($currentPage * $itemsPerPage, $totalItems) . " sur " . $totalItems . " éléments";
                        ?>
                    </div>
                    
                    <!-- Tableau des données -->
                    <div class="table-responsive">
                        <?php if ($section === 'offres'): ?>
                            <table class="gestion-table gestion-table-offres">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Entreprise</th>
                                        <th>Type</th>
                                        <th>Lieu</th>
                                        <th>Date de début</th>
                                        <th>Durée</th>
                                        <th>Rémunération</th>
                                        <th>Statut</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Aucune offre trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['titre']); ?></td>
                                                <td><?php echo htmlspecialchars($item['nom_entreprise'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['type'] ?? 'Non spécifié'); ?></td>
                                                <td><?php echo htmlspecialchars($item['lieu'] ?? 'Non spécifié'); ?></td>
                                                <td><?php echo isset($item['date_debut']) ? date('d/m/Y', strtotime($item['date_debut'])) : ''; ?></td>
                                                <td><?php echo $item['duree_stage']; ?> mois</td>
                                                <td><?php echo number_format((float)$item['remuneration'], 2, ',', ' '); ?> €</td>
                                                <td>
                                                    <span class="badge badge-<?php echo strtolower($item['statut']); ?>">
                                                        <?php echo $item['statut']; ?>
                                                    </span>
                                                </td>
                                                <td class="actions">
                                                    <a href="gestion/offres/edit?id=<?php echo $item['id']; ?>" class="btn-modifier">Modifier</a>
                                                    <button onclick="confirmDelete('offres', <?php echo $item['id']; ?>)" class="btn-supprimer">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif ($section === 'entreprises'): ?>
                            <table class="gestion-table gestion-table-entreprises">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Adresse</th>
                                        <th>Date de création</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune entreprise trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['nom']); ?></td>
                                                <td><?php echo htmlspecialchars($item['email_contact']); ?></td>
                                                <td><?php echo htmlspecialchars($item['telephone_contact']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($item['adresse'] ?? '', 0, 30) . (strlen($item['adresse'] ?? '') > 30 ? '...' : '')); ?></td>
                                                <td><?php echo isset($item['date_creation']) ? date('d/m/Y', strtotime($item['date_creation'])) : ''; ?></td>
                                                <td class="actions">
                                                    <a href="gestion/entreprises/edit?id=<?php echo $item['id']; ?>" class="btn-modifier">Modifier</a>
                                                    <button onclick="confirmDelete('entreprises', <?php echo $item['id']; ?>)" class="btn-supprimer">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif ($section === 'etudiants'): ?>
                            <table class="gestion-table gestion-table-etudiants">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Promotion</th>
                                        <th>Formation</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun étudiant trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr class="etudiant-row" data-id="<?php echo $item['id']; ?>">
                                                <td>
                                                    <a href="gestion/etudiants/candidatures?id=<?php echo $item['id']; ?>" class="etudiant-link">
                                                        <?php echo htmlspecialchars($item['nom'] ?? ''); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="gestion/etudiants/candidatures?id=<?php echo $item['id']; ?>" class="etudiant-link">
                                                        <?php echo htmlspecialchars($item['prenom'] ?? ''); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['promotion'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['formation'] ?? ''); ?></td>
                                                <td class="actions">
                                                    <a href="gestion/etudiants/edit?id=<?php echo $item['id']; ?>" class="btn-modifier">Modifier</a>
                                                    <button onclick="confirmDelete('etudiants', <?php echo $item['id']; ?>)" class="btn-supprimer">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif ($section === 'pilotes'): ?>
                            <table class="gestion-table gestion-table-pilotes">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Département</th>
                                        <th>Spécialité</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun pilote trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['nom'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['prenom'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['departement'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['specialite'] ?? ''); ?></td>
                                                <td class="actions">
                                                    <a href="gestion/pilotes/edit?id=<?php echo $item['id']; ?>" class="btn-modifier">Modifier</a>
                                                    <button onclick="confirmDelete('pilotes', <?php echo $item['id']; ?>)" class="btn-supprimer">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination avec paramètre de recherche -->
                    <?php 
                    // S'assurer que les variables de pagination sont définies
                    $totalPages = isset($totalPages) ? $totalPages : 1;
                    $currentPage = isset($currentPage) ? (int)$currentPage : 1; // Conversion explicite en entier
                    
                    // Préparer le paramètre de recherche pour les liens de pagination
                    $searchParam = isset($_GET['search']) && !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';

                    if ($totalPages > 1): 
                    ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage - 1; ?><?php echo $searchParam; ?>" class="pagination-item">&laquo;</a>
                            <?php endif; ?>
                            
                            <?php
                            // Afficher un nombre limité de liens de pagination
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            // Toujours afficher la première page
                            if ($startPage > 1) {
                                echo '<a href="gestion?section=' . $section . '&page=1' . $searchParam . '" class="pagination-item ' . ($currentPage === 1 ? 'active' : '') . '">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="pagination-item">...</span>';
                                }
                            }
                            
                            // Afficher les pages intermédiaires
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                $activeClass = ($i === $currentPage) ? 'active' : '';
                                echo '<a href="gestion?section=' . $section . '&page=' . $i . $searchParam . '" class="pagination-item ' . $activeClass . '">' . $i . '</a>';
                            }
                            
                            // Toujours afficher la dernière page
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) {
                                    echo '<span class="pagination-item">...</span>';
                                }
                                echo '<a href="gestion?section=' . $section . '&page=' . $totalPages . $searchParam . '" class="pagination-item ' . ($currentPage === $totalPages ? 'active' : '') . '">' . $totalPages . '</a>';
                            }
                            ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage + 1; ?><?php echo $searchParam; ?>" class="pagination-item">&raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
        
        // Fonction pour confirmer la suppression
        function confirmDelete(section, id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                window.location.href = 'gestion/' + section + '/delete?id=' + id;
            }
        }
        
        // Corriger la pagination active
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer le numéro de page actuel à partir de l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(urlParams.get('page')) || 1;
            
            // Supprimer la classe 'active' de tous les éléments de pagination
            document.querySelectorAll('.pagination-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Ajouter la classe 'active' à l'élément correspondant à la page actuelle
            const activePageItem = document.querySelector(`.pagination-item[href$="page=${currentPage}${urlParams.get('search') ? '&search=' + urlParams.get('search') : ''}"]:not([href*="page=${currentPage+1}"]):not([href*="page=${currentPage-1}"])`);
            if (activePageItem) {
                activePageItem.classList.add('active');
            }
        });
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>