<?php

require_once 'config/database.php';

class WishlistModel {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
    }

    /**
     * Récupère tous les éléments de la wishlist d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des éléments de la wishlist
     */
    public function getWishlistItems($userId) {
        try {
            // Jointure avec la table des offres pour obtenir les détails des éléments
            $query = "SELECT o.* FROM offres o
                      INNER JOIN wishlist w ON o.id = w.offre_id
                      WHERE w.user_id = :user_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log l'erreur et retourne un tableau vide
            error_log('Erreur lors de la récupération de la wishlist: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Ajoute un élément à la wishlist
     * @param int $userId ID de l'utilisateur
     * @param int $offreId ID de l'offre à ajouter
     * @return bool Succès de l'opération
     */
    public function addToWishlist($userId, $offreId) {
        try {
            // Vérifier si l'élément existe déjà dans la wishlist
            $checkQuery = "SELECT COUNT(*) FROM wishlist 
                          WHERE user_id = :user_id AND offre_id = :offre_id";
            
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                // L'élément est déjà dans la wishlist
                return true;
            }
            
            // Ajouter l'élément à la wishlist
            $query = "INSERT INTO wishlist (user_id, offre_id, date_added) 
                      VALUES (:user_id, :offre_id, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'ajout à la wishlist: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un élément de la wishlist
     * @param int $userId ID de l'utilisateur
     * @param int $offreId ID de l'offre à supprimer
     * @return bool Succès de l'opération
     */
    public function removeFromWishlist($userId, $offreId) {
        try {
            $query = "DELETE FROM wishlist 
                      WHERE user_id = :user_id AND offre_id = :offre_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de la wishlist: ' . $e->getMessage());
            return false;
        }
    }
}
?>