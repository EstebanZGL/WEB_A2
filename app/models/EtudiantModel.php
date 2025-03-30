<?php
class EtudiantModel {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }
    
    public function getEtudiants($limit = 10, $offset = 0) {
        $query = "SELECT e.*, u.nom, u.prenom, u.email, o.titre as offre_titre 
                 FROM etudiant e 
                 LEFT JOIN utilisateur u ON e.utilisateur_id = u.id 
                 LEFT JOIN offre_stage o ON e.offre_id = o.id 
                 ORDER BY u.nom, u.prenom ASC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countEtudiants() {
        $query = "SELECT COUNT(*) as total FROM etudiant";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    public function getEtudiantById($id) {
        $query = "SELECT e.*, u.nom, u.prenom, u.email 
                 FROM etudiant e 
                 LEFT JOIN utilisateur u ON e.utilisateur_id = u.id 
                 WHERE e.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUtilisateursForSelect() {
        $query = "SELECT id, CONCAT(nom, ' ', prenom, ' (', email, ')') as nom_complet 
                 FROM utilisateur 
                 WHERE id NOT IN (SELECT utilisateur_id FROM etudiant) 
                 ORDER BY nom, prenom ASC";
        $stmt = $this->db->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createEtudiant($data) {
        $query = "INSERT INTO etudiant (utilisateur_id, promotion, formation, offre_id) 
                 VALUES (:utilisateur_id, :promotion, :formation, :offre_id)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':utilisateur_id', $data['utilisateur_id'], PDO::PARAM_INT);
        $stmt->bindParam(':promotion', $data['promotion'], PDO::PARAM_STR);
        $stmt->bindParam(':formation', $data['formation'], PDO::PARAM_STR);
        $stmt->bindParam(':offre_id', $data['offre_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function updateEtudiant($id, $data) {
        $query = "UPDATE etudiant SET 
                 promotion = :promotion, 
                 formation = :formation, 
                 offre_id = :offre_id 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':promotion', $data['promotion'], PDO::PARAM_STR);
        $stmt->bindParam(':formation', $data['formation'], PDO::PARAM_STR);
        $stmt->bindParam(':offre_id', $data['offre_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function deleteEtudiant($id) {
        // Vérifier d'abord s'il y a des candidatures liées à cet étudiant
        $query = "SELECT COUNT(*) as count FROM candidature WHERE etudiant_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Il y a des candidatures liées, ne pas supprimer
            return false;
        }
        
        // Supprimer les entrées de la wishlist
        $query = "DELETE FROM wishlist WHERE etudiant_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Supprimer l'étudiant
        $query = "DELETE FROM etudiant WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function getEtudiantStats() {
        $stats = [];
        
        // Nombre total d'étudiants
        $query = "SELECT COUNT(*) as total FROM etudiant";
        $stmt = $this->db->query($query);
        $stats['total'] = $stmt->fetchColumn();
        
        // Répartition par promotion
        $query = "SELECT promotion, COUNT(*) as count 
                 FROM etudiant 
                 GROUP BY promotion 
                 ORDER BY count DESC";
        $stmt = $this->db->query($query);
        $stats['par_promotion'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Répartition par formation
        $query = "SELECT formation, COUNT(*) as count 
                 FROM etudiant 
                 GROUP BY formation 
                 ORDER BY count DESC";
        $stmt = $this->db->query($query);
        $stats['par_formation'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Nombre d'étudiants avec une offre assignée
        $query = "SELECT COUNT(*) as count FROM etudiant WHERE offre_id IS NOT NULL";
        $stmt = $this->db->query($query);
        $stats['avec_offre'] = $stmt->fetchColumn();
        
        // Nombre de candidatures par étudiant
        $query = "SELECT u.nom, u.prenom, COUNT(c.id) as nb_candidatures 
                 FROM etudiant e 
                 JOIN utilisateur u ON e.utilisateur_id = u.id 
                 LEFT JOIN candidature c ON e.id = c.etudiant_id 
                 GROUP BY e.id 
                 ORDER BY nb_candidatures DESC 
                 LIMIT 10";
        $stmt = $this->db->query($query);
        $stats['candidatures'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
}
?>