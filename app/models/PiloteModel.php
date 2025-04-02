<?php
class PiloteModel {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }
    
    public function getPilotes($limit = 10, $offset = 0) {
        $query = "SELECT p.*, u.nom, u.prenom, u.email 
                 FROM pilote p 
                 LEFT JOIN utilisateur u ON p.utilisateur_id = u.id 
                 ORDER BY u.nom, u.prenom ASC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countPilotes() {
        $query = "SELECT COUNT(*) as total FROM pilote";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    public function getPiloteById($id) {
        $query = "SELECT p.*, u.nom, u.prenom, u.email 
                 FROM pilote p 
                 LEFT JOIN utilisateur u ON p.utilisateur_id = u.id 
                 WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUtilisateursForSelect() {
        $query = "SELECT id, CONCAT(nom, ' ', prenom, ' (', email, ')') as nom_complet 
                 FROM utilisateur 
                 WHERE id NOT IN (SELECT utilisateur_id FROM pilote) 
                 AND id NOT IN (SELECT utilisateur_id FROM etudiant)
                 AND id NOT IN (SELECT utilisateur_id FROM administrateur)
                 ORDER BY nom, prenom ASC";
        $stmt = $this->db->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createPilote($data) {
        $query = "INSERT INTO pilote (utilisateur_id, departement, specialite) 
                 VALUES (:utilisateur_id, :departement, :specialite)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $data['utilisateur_id'], PDO::PARAM_INT);
        $stmt->bindParam(':departement', $data['departement'], PDO::PARAM_STR);
        $stmt->bindParam(':specialite', $data['specialite'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    public function updatePilote($id, $data) {
        $query = "UPDATE pilote SET 
                 departement = :departement, 
                 specialite = :specialite 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':departement', $data['departement'], PDO::PARAM_STR);
        $stmt->bindParam(':specialite', $data['specialite'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    public function deletePilote($id) {
        try {
            // Commencer une transaction
            $this->db->beginTransaction();
            
            // D'abord, récupérer l'ID de l'utilisateur associé à ce pilote
            $query = "SELECT utilisateur_id FROM pilote WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $pilote = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$pilote || !isset($pilote['utilisateur_id'])) {
                $this->db->rollBack();
                error_log("Erreur: Pilote avec ID $id non trouvé ou sans utilisateur associé");
                return false;
            }
            
            $utilisateur_id = $pilote['utilisateur_id'];
            
            // Supprimer le pilote
            $query = "DELETE FROM pilote WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Supprimer l'utilisateur associé
            $query = "DELETE FROM utilisateur WHERE id = :utilisateur_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler les modifications
            $this->db->rollBack();
            error_log("Erreur lors de la suppression du pilote et de l'utilisateur associé: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPiloteStats() {
        $stats = [];
        
        // Nombre total de pilotes
        $query = "SELECT COUNT(*) as total FROM pilote";
        $stmt = $this->db->query($query);
        $stats['total'] = $stmt->fetchColumn();
        
        // Répartition par département
        $query = "SELECT departement, COUNT(*) as count 
                 FROM pilote 
                 GROUP BY departement 
                 ORDER BY count DESC";
        $stmt = $this->db->query($query);
        $stats['par_departement'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Répartition par spécialité
        $query = "SELECT specialite, COUNT(*) as count 
                 FROM pilote 
                 GROUP BY specialite 
                 ORDER BY count DESC";
        $stmt = $this->db->query($query);
        $stats['par_specialite'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    public function searchPilotes($search, $limit = 10, $offset = 0) {
        try {
            $search = '%' . $search . '%';
            $query = "SELECT p.*, u.nom, u.prenom, u.email
                     FROM pilote p
                     JOIN utilisateur u ON p.utilisateur_id = u.id
                     WHERE u.nom LIKE :search 
                        OR u.prenom LIKE :search 
                        OR u.email LIKE :search 
                        OR p.departement LIKE :search
                        OR p.specialite LIKE :search
                     ORDER BY u.nom, u.prenom ASC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche des pilotes: " . $e->getMessage());
            return [];
        }
    }
    
    public function countPilotesSearch($search) {
        try {
            $search = '%' . $search . '%';
            $query = "SELECT COUNT(*) as total 
                     FROM pilote p
                     JOIN utilisateur u ON p.utilisateur_id = u.id
                     WHERE u.nom LIKE :search 
                        OR u.prenom LIKE :search 
                        OR u.email LIKE :search 
                        OR p.departement LIKE :search
                        OR p.specialite LIKE :search";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['total'];
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des pilotes recherchés: " . $e->getMessage());
            return 0;
        }
    }
}
?>