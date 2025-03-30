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
        <header class="navbar">
            <div class="container">
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
                            <table class="gestion-table gestion-table-offres">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Entreprise</th>
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
                                            <td colspan="8" class="text-center">Aucune offre trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo $item['id']; ?></td>
                                                <td><?php echo htmlspecialchars($item['titre']); ?></td>
                                                <td><?php echo htmlspecialchars($item['nom_entreprise'] ?? ''); ?></td>
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
                                        <th>ID</th>
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
                                            <td colspan="7" class="text-center">Aucune entreprise trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo $item['id']; ?></td>
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
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Promotion</th>
                                        <th>Formation</th>
                                        <th>Offre assignée</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($items)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun étudiant trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo $item['id']; ?></td>
                                                <td><?php echo htmlspecialchars($item['nom'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['prenom'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['promotion'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($item['formation'] ?? ''); ?></td>
                                                <td><?php echo isset($item['offre_titre']) ? htmlspecialchars($item['offre_titre']) : 'Non assignée'; ?></td>
                                                <td class="actions">
                                                    <a href="gestion/etudiants/edit?id=<?php echo $item['id']; ?>" class="btn-modifier">Modifier</a>
                                                    <button onclick="confirmDelete('etudiants', <?php echo $item['id']; ?>)" class="btn-supprimer">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php 
                    // S'assurer que les variables de pagination sont définies
                    $totalPages = isset($totalPages) ? $totalPages : 1;
                    $currentPage = isset($currentPage) ? (int)$currentPage : 1; // Conversion explicite en entier

                    if ($totalPages > 1): 
                    ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage - 1; ?>" class="pagination-item">&laquo;</a>
                            <?php endif; ?>
                            
                            <?php
                            // Afficher un nombre limité de liens de pagination
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            // Toujours afficher la première page
                            if ($startPage > 1) {
                                echo '<a href="gestion?section=' . $section . '&page=1" class="pagination-item ' . ($currentPage === 1 ? 'active' : '') . '">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="pagination-item">...</span>';
                                }
                            }
                            
                            // Afficher les pages intermédiaires
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                $activeClass = ($i === $currentPage) ? 'active' : '';
                                echo '<a href="gestion?section=' . $section . '&page=' . $i . '" class="pagination-item ' . $activeClass . '">' . $i . '</a>';
                            }
                            
                            // Toujours afficher la dernière page
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) {
                                    echo '<span class="pagination-item">...</span>';
                                }
                                echo '<a href="gestion?section=' . $section . '&page=' . $totalPages . '" class="pagination-item ' . ($currentPage === $totalPages ? 'active' : '') . '">' . $totalPages . '</a>';
                            }
                            ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage + 1; ?>" class="pagination-item">&raquo;</a>
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
</body>
</html>