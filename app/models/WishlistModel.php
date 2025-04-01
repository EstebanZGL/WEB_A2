<?php
require_once 'config/database.php';

class WishlistModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getDbConnection();
    }

    // Méthode originale pour ajouter à la wishlist
    public function addToWishlist($etudiantId, $offreId) {
        try {
            // Vérifier si l'offre n'est pas déjà dans la wishlist
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM wishlist 
                WHERE etudiant_id = :etudiant_id AND offre_id = :offre_id
            ");
            $stmt->execute([
                ':etudiant_id' => $etudiantId,
                ':offre_id' => $offreId
            ]);
            
            if ($stmt->fetchColumn() > 0) {
                return false; // L'offre est déjà dans la wishlist
            }
        
            // Ajouter l'offre à la wishlist
            $stmt = $this->pdo->prepare("
                INSERT INTO wishlist (etudiant_id, offre_id, date_ajout) 
                VALUES (:etudiant_id, :offre_id, NOW())
            ");
            return $stmt->execute([
                ':etudiant_id' => $etudiantId,
                ':offre_id' => $offreId
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout à la wishlist: " . $e->getMessage());
            return false;
        }
    }

    // Méthode originale pour supprimer de la wishlist
    public function removeFromWishlist($etudiantId, $offreId) {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM wishlist 
                WHERE etudiant_id = :etudiant_id AND offre_id = :offre_id
            ");
            return $stmt->execute([
                ':etudiant_id' => $etudiantId,
                ':offre_id' => $offreId
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la wishlist: " . $e->getMessage());
            return false;
        }
    }

    // Méthode originale pour obtenir l'ID étudiant par user ID
    public function getEtudiantIdByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id FROM etudiant WHERE user_id = :user_id
            ");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'ID étudiant: " . $e->getMessage());
            return null;
        }
    }

    // Méthode modifiée pour inclure la pagination
    public function getWishlistByUserId($userId, $limit = 5, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT w.*, o.titre, e.nom as nom_entreprise
                FROM wishlist w
                JOIN offre_stage o ON w.offre_id = o.id
                JOIN entreprise e ON o.entreprise_id = e.id
                WHERE w.etudiant_id = :userId
                ORDER BY w.date_ajout DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la wishlist: " . $e->getMessage());
            return [];
        }
    }

    // Nouvelle méthode pour compter les items de la wishlist
    public function countWishlistItems($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM wishlist 
                WHERE etudiant_id = :userId
            ");
            $stmt->execute([':userId' => $userId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des items de la wishlist: " . $e->getMessage());
            return 0;
        }
    }
}
?>
