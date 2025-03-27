<?php

class WishlistModel {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }

    public function getWishlistByUserId($userId) {
        $sql = "
            SELECT os.*, e.nom as entreprise, 
            GROUP_CONCAT(c.nom SEPARATOR ', ') as competences
            FROM wishlist w
            JOIN etudiant et ON w.etudiant_id = et.id
            JOIN utilisateur u ON et.utilisateur_id = u.id
            JOIN offre_stage os ON w.offre_id = os.id
            JOIN entreprise e ON os.entreprise_id = e.id
            LEFT JOIN offre_competence oc ON os.id = oc.offre_id
            LEFT JOIN competence c ON oc.competence_id = c.id
            WHERE u.id = ?
            GROUP BY os.id
            ORDER BY w.date_ajout DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function addToWishlist($etudiantId, $offreId) {
        // Vérifier si l'offre est déjà dans la wishlist
        $sql = "SELECT id FROM wishlist WHERE etudiant_id = ? AND offre_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $etudiantId);
        $stmt->bindValue(2, $offreId);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            return false; // L'offre est déjà dans la wishlist
        }
        
        // Ajouter l'offre à la wishlist
        $sql = "INSERT INTO wishlist (etudiant_id, offre_id, date_ajout) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $etudiantId);
        $stmt->bindValue(2, $offreId);
        
        return $stmt->execute();
    }

    public function removeFromWishlist($etudiantId, $offreId) {
        $sql = "DELETE FROM wishlist WHERE etudiant_id = ? AND offre_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $etudiantId);
        $stmt->bindValue(2, $offreId);
        
        return $stmt->execute();
    }

    public function getEtudiantIdByUserId($userId) {
        $sql = "SELECT id FROM etudiant WHERE utilisateur_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->id : null;
    }
}