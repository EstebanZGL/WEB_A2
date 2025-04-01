<?php
require_once 'config/database.php';

class OffreModel {
    private $pdo;

    public function __construct() {
        // Utiliser la fonction getDbConnection pour obtenir la connexion PDO
        $this->pdo = getDbConnection();
    }

    // Méthodes existantes adaptées au nom de table offre_stage
    public function getAllOffres() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM offre_stage ORDER BY date_publication DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres: " . $e->getMessage());
            return [];
        }
    }

    // Méthode modifiée pour accepter un tableau de paramètres de recherche
    public function searchOffres($searchParams = []) {
        try {
            // Extraire les paramètres du tableau
            $jobTitle = isset($searchParams['jobTitle']) ? $searchParams['jobTitle'] : '';
            $location = isset($searchParams['location']) ? $searchParams['location'] : '';
            $filters = isset($searchParams['filters']) ? $searchParams['filters'] : [];

            $query = "SELECT o.*, e.nom as entreprise FROM offre_stage o 
                      LEFT JOIN entreprise e ON o.entreprise_id = e.id
                      WHERE 1=1";
            $params = [];

            // Ajouter des conditions de recherche si elles sont fournies
            if (!empty($jobTitle)) {
                $query .= " AND (o.titre LIKE :jobTitle OR o.description LIKE :jobTitle)";
                $params[':jobTitle'] = "%$jobTitle%";
            }

            if (!empty($location)) {
                $query .= " AND e.nom LIKE :location";
                $params[':location'] = "%$location%";
            }

            // Ajouter des filtres supplémentaires si nécessaire
            if (!empty($filters) && is_array($filters)) {
                // Exemple de traitement des filtres de salaire
                if (isset($filters['salary']) && is_array($filters['salary'])) {
                    foreach ($filters['salary'] as $range) {
                        if ($range === '0-50000') {
                            $query .= " AND o.remuneration BETWEEN 0 AND 50000";
                        } elseif ($range === '50000-100000') {
                            $query .= " AND o.remuneration BETWEEN 50000 AND 100000";
                        } elseif ($range === '100000+') {
                            $query .= " AND o.remuneration >= 100000";
                        }
                    }
                }
            }

            // Trier par date décroissante
            $query .= " ORDER BY o.date_publication DESC";

            // Déboguer la requête SQL
            error_log("Requête SQL: " . $query);
            error_log("Paramètres: " . print_r($params, true));

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
                FROM offre_stage o
                LEFT JOIN entreprise e ON o.entreprise_id = e.id
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
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM offre_stage");
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
                FROM offre_stage o
                LEFT JOIN entreprise e ON o.entreprise_id = e.id
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
                INSERT INTO offre_stage (
                    entreprise_id, createur_id, titre, description, 
                    remuneration, date_debut, date_fin, date_publication, 
                    statut, duree_stage, type, lieu
                ) VALUES (
                    :entreprise_id, :createur_id, :titre, :description, 
                    :remuneration, :date_debut, :date_fin, :date_publication, 
                    :statut, :duree_stage, :type, :lieu
                )
            ");
            
            $stmt->execute([
                ':entreprise_id' => $data['entreprise_id'],
                ':createur_id' => $data['createur_id'] ?? 1, // Valeur par défaut si non fournie
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':remuneration' => $data['remuneration'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'] ?? null,
                ':date_publication' => $data['date_publication'],
                ':statut' => $data['statut'],
                ':duree_stage' => $data['duree_stage'],
                ':type' => $data['type'] ?? null,
                ':lieu' => $data['lieu'] ?? null
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
                UPDATE offre_stage SET 
                entreprise_id = :entreprise_id,
                titre = :titre,
                description = :description,
                remuneration = :remuneration,
                date_debut = :date_debut,
                date_fin = :date_fin,
                statut = :statut,
                duree_stage = :duree_stage,
                type = :type,
                lieu = :lieu
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':entreprise_id' => $data['entreprise_id'],
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':remuneration' => $data['remuneration'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'] ?? null,
                ':statut' => $data['statut'],
                ':duree_stage' => $data['duree_stage'],
                ':type' => $data['type'] ?? null,
                ':lieu' => $data['lieu'] ?? null
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
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM etudiant WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            $count = (int) $stmt->fetchColumn();
            
            if ($count > 0) {
                // Mettre à jour les étudiants pour enlever la référence à cette offre
                $stmtUpdate = $this->pdo->prepare("UPDATE etudiant SET offre_id = NULL WHERE offre_id = :id");
                $stmtUpdate->execute([':id' => $id]);
            }
            
            // Vérifier s'il y a des candidatures liées à cette offre
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM candidature WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            $count = (int) $stmt->fetchColumn();
            
            if ($count > 0) {
                // Supprimer les candidatures liées à cette offre
                $stmtDelete = $this->pdo->prepare("DELETE FROM candidature WHERE offre_id = :id");
                $stmtDelete->execute([':id' => $id]);
            }
            
            // Vérifier s'il y a des entrées dans wishlist liées à cette offre
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            $count = (int) $stmt->fetchColumn();
            
            if ($count > 0) {
                // Supprimer les entrées de wishlist liées à cette offre
                $stmtDelete = $this->pdo->prepare("DELETE FROM wishlist WHERE offre_id = :id");
                $stmtDelete->execute([':id' => $id]);
            }
            
            // Vérifier s'il y a des compétences liées à cette offre
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM offre_competence WHERE offre_id = :id");
            $stmt->execute([':id' => $id]);
            $count = (int) $stmt->fetchColumn();
            
            if ($count > 0) {
                // Supprimer les compétences liées à cette offre
                $stmtDelete = $this->pdo->prepare("DELETE FROM offre_competence WHERE offre_id = :id");
                $stmtDelete->execute([':id' => $id]);
            }
            
            // Supprimer l'offre
            $stmtDelete = $this->pdo->prepare("DELETE FROM offre_stage WHERE id = :id");
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
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM offre_stage");
            $stats['total'] = (int) $stmt->fetchColumn();
            
            // Rémunération moyenne
            $stmt = $this->pdo->query("SELECT AVG(remuneration) FROM offre_stage WHERE remuneration > 0");
            $stats['remuneration_moyenne'] = (float) $stmt->fetchColumn();
            
            // Durée moyenne des stages
            $stmt = $this->pdo->query("SELECT AVG(duree_stage) FROM offre_stage WHERE duree_stage > 0");
            $stats['duree_moyenne'] = (float) $stmt->fetchColumn();
            
            // Répartition par statut
            $stmt = $this->pdo->query("
                SELECT statut, COUNT(*) as count
                FROM offre_stage
                GROUP BY statut
                ORDER BY count DESC
            ");
            $stats['par_statut'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Top entreprises par nombre d'offres
            $stmt = $this->pdo->query("
                SELECT e.nom, COUNT(*) as count
                FROM offre_stage o
                JOIN entreprise e ON o.entreprise_id = e.id
                GROUP BY o.entreprise_id
                ORDER BY count DESC
                LIMIT 5
            ");
            $stats['par_entreprise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Offres les plus récentes
            $stmt = $this->pdo->query("
                SELECT o.titre, e.nom as entreprise, o.date_publication
                FROM offre_stage o
                JOIN entreprise e ON o.entreprise_id = e.id
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
                FROM offre_stage
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