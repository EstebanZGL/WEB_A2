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
                        <div class="alert alert-error">
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
                        Affichage de <?php echo min(($currentPage - 1) * $itemsPerPage + 1, $totalItems); ?> à 
                        <?php echo min($currentPage * $itemsPerPage, $totalItems); ?> sur <?php echo $totalItems; ?> éléments
                    </div>
                    
                    <!-- Tableau des données -->
                    <div class="table-responsive">
                        <?php if ($section === 'offres'): ?>
                            <table class="table">
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
                                                <td><?php echo htmlspecialchars($item['nom_entreprise']); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($item['date_debut'])); ?></td>
                                                <td><?php echo $item['duree_stage']; ?> mois</td>
                                                <td><?php echo number_format($item['remuneration'], 2, ',', ' '); ?> €</td>
                                                <td>
                                                    <span class="badge badge-<?php echo strtolower($item['statut']); ?>">
                                                        <?php echo $item['statut']; ?>
                                                    </span>
                                                </td>
                                                <td class="actions">
                                                    <a href="gestion/offres/edit?id=<?php echo $item['id']; ?>" class="button button-small button-edit">Modifier</a>
                                                    <button onclick="confirmDelete('offres', <?php echo $item['id']; ?>)" class="button button-small button-delete">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif ($section === 'entreprises'): ?>
                            <table class="table">
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
                                                <td><?php echo htmlspecialchars(substr($item['adresse'], 0, 30) . (strlen($item['adresse']) > 30 ? '...' : '')); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($item['date_creation'])); ?></td>
                                                <td class="actions">
                                                    <a href="gestion/entreprises/edit?id=<?php echo $item['id']; ?>" class="button button-small button-edit">Modifier</a>
                                                    <button onclick="confirmDelete('entreprises', <?php echo $item['id']; ?>)" class="button button-small button-delete">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php elseif ($section === 'etudiants'): ?>
                            <table class="table">
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
                                                <td><?php echo htmlspecialchars($item['nom']); ?></td>
                                                <td><?php echo htmlspecialchars($item['prenom']); ?></td>
                                                <td><?php echo htmlspecialchars($item['email']); ?></td>
                                                <td><?php echo htmlspecialchars($item['promotion']); ?></td>
                                                <td><?php echo htmlspecialchars($item['formation']); ?></td>
                                                <td><?php echo $item['offre_titre'] ? htmlspecialchars($item['offre_titre']) : 'Non assignée'; ?></td>
                                                <td class="actions">
                                                    <a href="gestion/etudiants/edit?id=<?php echo $item['id']; ?>" class="button button-small button-edit">Modifier</a>
                                                    <button onclick="confirmDelete('etudiants', <?php echo $item['id']; ?>)" class="button button-small button-delete">Supprimer</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage - 1; ?>" class="pagination-link">&laquo; Précédent</a>
                            <?php endif; ?>
                            
                            <?php
                            // Afficher un nombre limité de liens de pagination
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            // Toujours afficher la première page
                            if ($startPage > 1) {
                                echo '<a href="gestion?section=' . $section . '&page=1" class="pagination-link">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="pagination-ellipsis">...</span>';
                                }
                            }
                            
                            // Afficher les pages intermédiaires
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                $activeClass = ($i === $currentPage) ? 'active' : '';
                                echo '<a href="gestion?section=' . $section . '&page=' . $i . '" class="pagination-link ' . $activeClass . '">' . $i . '</a>';
                            }
                            
                            // Toujours afficher la dernière page
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) {
                                    echo '<span class="pagination-ellipsis">...</span>';
                                }
                                echo '<a href="gestion?section=' . $section . '&page=' . $totalPages . '" class="pagination-link">' . $totalPages . '</a>';
                            }
                            ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="gestion?section=<?php echo $section; ?>&page=<?php echo $currentPage + 1; ?>" class="pagination-link">Suivant &raquo;</a>
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
        
        // Fonction pour confirmer la suppression d'un élément
        function confirmDelete(section, id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                window.location.href = 'gestion/' + section + '/delete?id=' + id;
            }
        }
    </script>
</body>
</html>