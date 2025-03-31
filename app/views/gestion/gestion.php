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
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay - Déplacé ici comme dans home.php -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile - Déplacé ici comme dans home.php -->
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
                        <a href="gestion?section=offres" class="tab <?php echo $section === 'offres' ? 'active' : ''; ?>">Offres</a>
                        <a href="gestion?section=entreprises" class="tab <?php echo $section === 'entreprises' ? 'active' : ''; ?>">Entreprises</a>
                        <a href="gestion?section=etudiants" class="tab <?php echo $section === 'etudiants' ? 'active' : ''; ?>">Étudiants</a>
                        <?php if (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
                        <a href="gestion?section=pilotes" class="tab <?php echo $section === 'pilotes' ? 'active' : ''; ?>">Pilotes</a>
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
                            <a href="gestion/<?php echo $section; ?>/add" class="button button-primary">Ajouter</a>
                            <a href="gestion/<?php echo $section; ?>/stats" class="button button-secondary">Statistiques</a>
                        </div>
                        
                        <!-- Formulaire de recherche (à implémenter plus tard) -->
                        <div>
                            <form action="gestion" method="get" class="search-form-inline">
                                <input type="hidden" name="section" value="<?php echo $section; ?>">
                                <input type="text" name="search" placeholder="Rechercher..." class="search-input-small">
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
                            <!-- Table des offres (code existant) -->
                            <table class="gestion-table gestion-table-offres">
                                <!-- ... Code existant ... -->
                            </table>
                        <?php elseif ($section === 'entreprises'): ?>
                            <!-- Table des entreprises (code existant) -->
                            <table class="gestion-table gestion-table-entreprises">
                                <!-- ... Code existant ... -->
                            </table>
                        <?php elseif ($section === 'etudiants'): ?>
                            <!-- Table des étudiants (code existant) -->
                            <table class="gestion-table gestion-table-etudiants">
                                <!-- ... Code existant ... -->
                            </table>
                        <?php elseif ($section === 'pilotes' && isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2): ?>
                            <!-- Table des pilotes (nouveau) -->
                            <table class="gestion-table gestion-table-pilotes">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                            <td colspan="7" class="text-center">Aucun pilote trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo $item['id']; ?></td>
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
                    
                    <!-- Pagination (code existant) -->
                    <?php 
                    // S'assurer que les variables de pagination sont définies
                    $totalPages = isset($totalPages) ? $totalPages : 1;
                    $currentPage = isset($currentPage) ? (int)$currentPage : 1; // Conversion explicite en entier

                    if ($totalPages > 1): 
                    ?>
                        <div class="pagination">
                            <!-- ... Code de pagination existant ... -->
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
            const activePageItem = document.querySelector(`.pagination-item[href$="page=${currentPage}"]`);
            if (activePageItem) {
                activePageItem.classList.add('active');
            }
        });
    </script>
    
    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
</body>
</html>