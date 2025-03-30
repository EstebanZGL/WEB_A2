<?php
class OffreModel {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }

    public function getOffres($limit = 10, $offset = 0) {
        $query = "SELECT o.*, e.nom as entreprise_nom, u.nom as createur_nom, u.prenom as createur_prenom 
                 FROM offre_stage o 
                 LEFT JOIN entreprise e ON o.entreprise_id = e.id 
                 LEFT JOIN utilisateur u ON o.createur_id = u.id 
                 ORDER BY o.date_publication DESC 
                 LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOffres() {
        $query = "SELECT COUNT(*) as total FROM offre_stage";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    public function getOffreById($id) {
        $query = "SELECT o.*, e.nom as entreprise_nom 
                 FROM offre_stage o 
                 LEFT JOIN entreprise e ON o.entreprise_id = e.id 
                 WHERE o.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getOffresForSelect() {
        $query = "SELECT o.id, CONCAT(o.titre, ' (', e.nom, ')') as titre_complet 
                 FROM offre_stage o 
                 JOIN entreprise e ON o.entreprise_id = e.id 
                 WHERE o.statut = 'ACTIVE' 
                 ORDER BY o.titre ASC";
        $stmt = $this->db->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
    public function createOffre($data) {
        $query = "INSERT INTO offre_stage (entreprise_id, createur_id, titre, description, 
                 remuneration, date_debut, date_fin, date_publication, statut, duree_stage) 
                 VALUES (:entreprise_id, :createur_id, :titre, :description, 
                 :remuneration, :date_debut, :date_fin, :date_publication, :statut, :duree_stage)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':entreprise_id', $data['entreprise_id'], PDO::PARAM_INT);
        $stmt->bindParam(':createur_id', $data['createur_id'], PDO::PARAM_INT);
        $stmt->bindParam(':titre', $data['titre'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration', $data['remuneration'], PDO::PARAM_STR);
        $stmt->bindParam(':date_debut', $data['date_debut'], PDO::PARAM_STR);
        $stmt->bindParam(':date_fin', $data['date_fin'], PDO::PARAM_STR);
        $stmt->bindParam(':date_publication', $data['date_publication'], PDO::PARAM_STR);
        $stmt->bindParam(':statut', $data['statut'], PDO::PARAM_STR);
        $stmt->bindParam(':duree_stage', $data['duree_stage'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function updateOffre($id, $data) {
        $query = "UPDATE offre_stage SET 
                 entreprise_id = :entreprise_id, 
                 titre = :titre, 
                 description = :description, 
                 remuneration = :remuneration, 
                 date_debut = :date_debut, 
                 date_fin = :date_fin, 
                 statut = :statut, 
                 duree_stage = :duree_stage 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':entreprise_id', $data['entreprise_id'], PDO::PARAM_INT);
        $stmt->bindParam(':titre', $data['titre'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration', $data['remuneration'], PDO::PARAM_STR);
        $stmt->bindParam(':date_debut', $data['date_debut'], PDO::PARAM_STR);
        $stmt->bindParam(':date_fin', $data['date_fin'], PDO::PARAM_STR);
        $stmt->bindParam(':statut', $data['statut'], PDO::PARAM_STR);
        $stmt->bindParam(':duree_stage', $data['duree_stage'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function deleteOffre($id) {
        // Vérifier d'abord s'il y a des candidatures liées à cette offre
        $query = "SELECT COUNT(*) as count FROM candidature WHERE offre_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Il y a des candidatures liées, ne pas supprimer
            return false;
        }
        
        // Vérifier s'il y a des étudiants liés à cette offre
        $query = "SELECT COUNT(*) as count FROM etudiant WHERE offre_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Il y a des étudiants liés, ne pas supprimer
            return false;
        }
        
        // Supprimer les entrées de la wishlist
        $query = "DELETE FROM wishlist WHERE offre_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Supprimer les compétences liées à l'offre
        $query = "DELETE FROM offre_competence WHERE offre_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Supprimer l'offre
        $query = "DELETE FROM offre_stage WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function searchOffres($params) {
        $query = "SELECT o.*, e.nom as entreprise_nom 
                 FROM offre_stage o 
                 JOIN entreprise e ON o.entreprise_id = e.id 
                 WHERE 1=1";
        $queryParams = [];
        
        if (!empty($params['jobTitle'])) {
            $query .= " AND (o.titre LIKE :jobTitle OR o.description LIKE :jobTitle)";
            $queryParams[':jobTitle'] = '%' . $params['jobTitle'] . '%';
        }
        
        if (!empty($params['location'])) {
            $query .= " AND e.adresse LIKE :location";
            $queryParams[':location'] = '%' . $params['location'] . '%';
        }
        
        // Ajouter d'autres filtres selon les besoins
        
        $query .= " ORDER BY o.date_publication DESC";
        
        $stmt = $this->db->prepare($query);
        foreach ($queryParams as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllOffres() {
        $query = "SELECT o.*, e.nom as entreprise_nom 
                 FROM offre_stage o 
                 JOIN entreprise e ON o.entreprise_id = e.id 
                 ORDER BY o.date_publication DESC";
        $stmt = $this->db->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOffreStats() {
        $stats = [];
        
        // Nombre total d'offres
        $query = "SELECT COUNT(*) as total FROM offre_stage";
        $stmt = $this->db->query($query);
        $stats['total'] = $stmt->fetchColumn();
        
        // Répartition par statut
        $query = "SELECT statut, COUNT(*) as count 
                 FROM offre_stage 
                 GROUP BY statut";
        $stmt = $this->db->query($query);
        $stats['par_statut'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Offres par entreprise
        $query = "SELECT e.nom, COUNT(o.id) as count 
                 FROM entreprise e 
                 LEFT JOIN offre_stage o ON e.id = o.entreprise_id 
                 GROUP BY e.id 
                 ORDER BY count DESC 
                 LIMIT 10";
        $stmt = $this->db->query($query);
        $stats['par_entreprise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Rémunération moyenne
        $query = "SELECT AVG(remuneration) as moyenne FROM offre_stage";
        $stmt = $this->db->query($query);
        $stats['remuneration_moyenne'] = $stmt->fetchColumn();
        
        // Durée moyenne des stages
        $query = "SELECT AVG(duree_stage) as moyenne FROM offre_stage";
        $stmt = $this->db->query($query);
        $stats['duree_moyenne'] = $stmt->fetchColumn();
        
        // Offres les plus récentes
        $query = "SELECT o.titre, e.nom as entreprise, o.date_publication 
                 FROM offre_stage o 
                 JOIN entreprise e ON o.entreprise_id = e.id 
                 ORDER BY o.date_publication DESC 
                 LIMIT 5";
        $stmt = $this->db->query($query);
        $stats['recentes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
}
?>