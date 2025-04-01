<?php
class EntrepriseModel {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }
    
    public function getEntreprises($limit = 10, $offset = 0) {
        $query = "SELECT e.*, u.nom as createur_nom, u.prenom as createur_prenom 
                 FROM entreprise e 
                 LEFT JOIN utilisateur u ON e.createur_id = u.id 
                 ORDER BY e.nom ASC 
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countEntreprises() {
        $query = "SELECT COUNT(*) as total FROM entreprise";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
    
    public function getEntrepriseById($id) {
        $query = "SELECT * FROM entreprise WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getEntreprisesForSelect() {
        $query = "SELECT id, nom FROM entreprise ORDER BY nom ASC";
        $stmt = $this->db->query($query);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createEntreprise($data) {
        $query = "INSERT INTO entreprise (createur_id, nom, description, email_contact, 
                 telephone_contact, adresse, lien_site, date_creation) 
                 VALUES (:createur_id, :nom, :description, :email_contact, 
                 :telephone_contact, :adresse, :lien_site, :date_creation)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':createur_id', $data['createur_id'], PDO::PARAM_INT);
        $stmt->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':email_contact', $data['email_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':telephone_contact', $data['telephone_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $data['adresse'], PDO::PARAM_STR);
        $stmt->bindParam(':lien_site', $data['lien_site'], PDO::PARAM_STR);
        $stmt->bindParam(':date_creation', $data['date_creation'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    public function updateEntreprise($id, $data) {
        $query = "UPDATE entreprise SET 
                 nom = :nom, 
                 description = :description, 
                 email_contact = :email_contact, 
                 telephone_contact = :telephone_contact, 
                 adresse = :adresse, 
                 lien_site = :lien_site 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':email_contact', $data['email_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':telephone_contact', $data['telephone_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $data['adresse'], PDO::PARAM_STR);
        $stmt->bindParam(':lien_site', $data['lien_site'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    public function deleteEntreprise($id) {
        // Vérifier d'abord s'il y a des offres liées à cette entreprise
        $query = "SELECT COUNT(*) as count FROM offre_stage WHERE entreprise_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Il y a des offres liées, ne pas supprimer
            return false;
        }
        
        // Sinon, supprimer l'entreprise
        $query = "DELETE FROM entreprise WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    public function getEntrepriseStats() {
        $stats = [];
        
        // Nombre total d'entreprises
        $query = "SELECT COUNT(*) as total FROM entreprise";
        $stmt = $this->db->query($query);
        $stats['total'] = $stmt->fetchColumn();
        
        // Nombre d'offres par entreprise
        $query = "SELECT e.nom, COUNT(o.id) as nb_offres 
                 FROM entreprise e 
                 LEFT JOIN offre_stage o ON e.id = o.entreprise_id 
                 GROUP BY e.id 
                 ORDER BY nb_offres DESC 
                 LIMIT 10";
        $stmt = $this->db->query($query);
        $stats['offres_par_entreprise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Entreprises les plus récentes
        $query = "SELECT nom, date_creation 
                 FROM entreprise 
                 ORDER BY date_creation DESC 
                 LIMIT 5";
        $stmt = $this->db->query($query);
        $stats['recentes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }

    public function searchEntreprises($search, $limit = 10, $offset = 0) {
        try {
            $search = '%' . $search . '%';
            $query = "SELECT e.*, u.nom as createur_nom, u.prenom as createur_prenom 
                     FROM entreprise e 
                     LEFT JOIN utilisateur u ON e.createur_id = u.id 
                     WHERE e.nom LIKE :search 
                        OR e.email_contact LIKE :search 
                        OR e.telephone_contact LIKE :search 
                        OR e.adresse LIKE :search
                     ORDER BY e.nom ASC 
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche des entreprises: " . $e->getMessage());
            return [];
        }
    }
    
    public function countEntreprisesSearch($search) {
        try {
            $search = '%' . $search . '%';
            $query = "SELECT COUNT(*) as total 
                     FROM entreprise e
                     WHERE e.nom LIKE :search 
                        OR e.email_contact LIKE :search 
                        OR e.telephone_contact LIKE :search 
                        OR e.adresse LIKE :search";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['total'];
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des entreprises recherchées: " . $e->getMessage());
            return 0;
        }
    }
}
?>