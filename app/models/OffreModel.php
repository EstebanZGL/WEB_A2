<?php
require_once 'config/database.php';

class OffreModel {
    private $pdo;

    public function __construct() {
        // Utiliser la fonction getDbConnection pour obtenir la connexion PDO
        $this->pdo = getDbConnection();
    }

    // Méthodes existantes
    public function getAllOffres() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM offres ORDER BY date_offre DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres: " . $e->getMessage());
            return [];
        }
    }

    public function searchOffres($jobTitle = '', $location = '') {
        try {
            $query = "SELECT * FROM offres WHERE 1=1";
            $params = [];

            // Ajouter des conditions de recherche si elles sont fournies
            if (!empty($jobTitle)) {
                $query .= " AND (titre LIKE :jobTitle OR description LIKE :jobTitle OR competences LIKE :jobTitle)";
                $params[':jobTitle'] = "%$jobTitle%";
            }

            if (!empty($location)) {
                $query .= " AND entreprise LIKE :location";
                $params[':location'] = "%$location%";
            }

            // Trier par date décroissante
            $query .= " ORDER BY date_offre DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche d'offres: " . $e->getMessage());
            return [];
        }
    }

    // Méthodes pour la gestion avec pagination
    public function getOffres($limit = 10, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, e.nom as nom_entreprise
                FROM offres o
                LEFT JOIN entreprises e ON o.entreprise_id = e.id
                ORDER BY o.date_publication DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres avec pagination: " . $e->getMessage());
            return [];
        }
    }

    public function countOffres() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM offres");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des offres: " . $e->getMessage());
            return 0;
        }
    }

    public function getOffreById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.*, e.nom as nom_entreprise
                FROM offres o
                LEFT JOIN entreprises e ON o.entreprise_id = e.id
                WHERE o.id = :id
            ");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'offre: " . $e->getMessage());
            return null;
        }
    }

    public function createOffre($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO offres (
                    entreprise_id, createur_id, titre, description, 
                    remuneration, date_debut, date_fin, date_publication, 
                    statut, duree_stage
                ) VALUES (
                    :entreprise_id, :createur_id, :titre, :description, 
                    :remuneration, :date_debut, :date_fin, :date_publication, 
                    :statut, :duree_stage
                )
            ");
            
            $stmt->execute([
                ':entreprise_id' => $data['entreprise_id'],
                ':createur_id' => $data['createur_id'],
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':remuneration' => $data['remuneration'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':date_publication' => $data['date_publication'],
                ':statut' => $data['statut'],
                ':duree_stage' => $data['duree_stage']
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'offre: " . $e->getMessage());
            return false;
        }
    }

    public function updateOffre($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE offres SET 
                entreprise_id = :entreprise_id,
                titre = :titre,
                description = :description,
                remuneration = :remuneration,
                date_debut = :date_debut,
                date_fin = :date_fin,
                statut = :statut,
                duree_stage = :duree_stage
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':entreprise_id' => $data['entreprise_id'],
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':remuneration' => $data['remuneration'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':statut' => $data['statut'],
                ':duree_stage' => $data['duree_stage']
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'offre: " . $e->getMessage());
            return false;
        }
    }

    public function deleteOffre($id) {
        try {
            // Vérifier s'il y a des étudiants liés à cette offre
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            $count = (int) $stmt->fetchColumn();
            
            if ($count > 0) {
                // Mettre à jour les étudiants pour enlever la référence à cette offre
                $stmtUpdate = $this->pdo->prepare("UPDATE etudiants SET offre_id = NULL WHERE offre_id = :id");
                $stmtUpdate->execute([':id' => $id]);
            }
            
            // Supprimer l'offre
            $stmtDelete = $this->pdo->prepare("DELETE FROM offres WHERE id = :id");
            $stmtDelete->execute([':id' => $id]);
            
            return $stmtDelete->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'offre: " . $e->getMessage());
            return false;
        }
    }

    // Méthode pour obtenir les statistiques des offres
    public function getOffreStats() {
        $stats = [
            'total' => 0,
            'remuneration_moyenne' => 0,
            'duree_moyenne' => 0,
            'par_statut' => [],
            'par_entreprise' => [],
            'recentes' => []
        ];
        
        try {
            // Nombre total d'offres
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM offres");
            $stats['total'] = (int) $stmt->fetchColumn();
            
            // Rémunération moyenne
            $stmt = $this->pdo->query("SELECT AVG(remuneration) FROM offres WHERE remuneration > 0");
            $stats['remuneration_moyenne'] = (float) $stmt->fetchColumn();
            
            // Durée moyenne des stages
            $stmt = $this->pdo->query("SELECT AVG(duree_stage) FROM offres WHERE duree_stage > 0");
            $stats['duree_moyenne'] = (float) $stmt->fetchColumn();
            
            // Répartition par statut
            $stmt = $this->pdo->query("
                SELECT statut, COUNT(*) as count
                FROM offres
                GROUP BY statut
                ORDER BY count DESC
            ");
            $stats['par_statut'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Top entreprises par nombre d'offres
            $stmt = $this->pdo->query("
                SELECT e.nom, COUNT(*) as count
                FROM offres o
                JOIN entreprises e ON o.entreprise_id = e.id
                GROUP BY o.entreprise_id
                ORDER BY count DESC
                LIMIT 5
            ");
            $stats['par_entreprise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Offres les plus récentes
            $stmt = $this->pdo->query("
                SELECT o.titre, e.nom as entreprise, o.date_publication
                FROM offres o
                JOIN entreprises e ON o.entreprise_id = e.id
                ORDER BY o.date_publication DESC
                LIMIT 5
            ");
            $stats['recentes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques des offres: " . $e->getMessage());
            return $stats;
        }
    }

    // Méthode pour obtenir la liste des offres pour un select
    public function getOffresForSelect() {
        try {
            $stmt = $this->pdo->query("
                SELECT id, titre
                FROM offres
                WHERE statut = 'ACTIVE'
                ORDER BY titre ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres pour select: " . $e->getMessage());
            return [];
        }
    }
}
?>
