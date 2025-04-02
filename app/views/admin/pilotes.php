<?php
// Vérifier si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
    header("Location: login");
    exit;
}

// Inclure l'en-tête
require_once 'app/views/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Gestion des Pilotes</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="admin/addPilote" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un pilote
            </a>
            <a href="admin/statsPilotes" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php if ($_GET['success'] == 1): ?>
                Le pilote a été ajouté avec succès.
            <?php elseif ($_GET['success'] == 2): ?>
                Le pilote a été modifié avec succès.
            <?php elseif ($_GET['success'] == 3): ?>
                Le pilote a été supprimé avec succès.
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php if ($_GET['error'] == 1): ?>
                Une erreur est survenue lors de l'ajout du pilote.
            <?php elseif ($_GET['error'] == 2): ?>
                Une erreur est survenue lors de la modification du pilote.
            <?php elseif ($_GET['error'] == 3): ?>
                Le pilote demandé n'existe pas.
            <?php elseif ($_GET['error'] == 4): ?>
                Une erreur est survenue lors de la suppression du pilote.
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Liste des pilotes (<?= $totalItems ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pilotes)): ?>
                <div class="alert alert-info">Aucun pilote trouvé.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Département</th>
                                <th>Spécialité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pilotes as $pilote): ?>
                                <tr>
                                    <td><?= $pilote['id'] ?></td>
                                    <td><?= htmlspecialchars($pilote['nom']) ?></td>
                                    <td><?= htmlspecialchars($pilote['prenom']) ?></td>
                                    <td><?= htmlspecialchars($pilote['email']) ?></td>
                                    <td><?= htmlspecialchars($pilote['departement']) ?></td>
                                    <td><?= htmlspecialchars($pilote['specialite']) ?></td>
                                    <td>
                                        <a href="admin/editPilote?id=<?= $pilote['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="admin/deletePilote?id=<?= $pilote['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce pilote ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="admin/pilotes?page=<?= $currentPage - 1 ?>">
                                        <i class="fas fa-chevron-left"></i> Précédent
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i> Précédent</span>
                                </li>
                            <?php endif; ?>
                            
                            <?php
                            // Afficher un nombre limité de pages
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            // Toujours afficher la première page
                            if ($startPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="admin/pilotes?page=1">1</a></li>';
                                if ($startPage > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            // Afficher les pages intermédiaires
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                if ($i == $currentPage) {
                                    echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="admin/pilotes?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                            
                            // Toujours afficher la dernière page
                            if ($endPage < $totalPages) {
                                if ($endPage < $totalPages - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="admin/pilotes?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                            }
                            ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="admin/pilotes?page=<?= $currentPage + 1 ?>">
                                        Suivant <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Suivant <i class="fas fa-chevron-right"></i></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Inclure le pied de page
require_once 'app/views/footer.php';
?>